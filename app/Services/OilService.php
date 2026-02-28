<?php

namespace App\Services;

use App\Models\OilCalculation;
use App\Models\OilMasterData;
use App\Models\OilRecord;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OilService
{
    /**
     * Validasi dan simpan data lab dengan dual-mode input
     * 
     * Mode 1: Non-numeric data (kode, jenis, operator, dll) - bisa multiple per day
     * Mode 2: Numeric data (kode_mode2, cawan_kosong, berat_basah, dll) - hanya 1 per kode per day
     * User BISA mengisi kedua mode sekaligus dalam satu submission
     * 
     * @param array $data
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function store(array $data, int $userId): array
    {
        $isMode1 = !empty($data['kode']); // Mode 1: Non-numeric
        $isMode2 = !empty($data['kode_mode2']) && (!empty($data['cawan_kosong']) || !empty($data['berat_basah'])); // Mode 2: Numeric

        if (!$isMode1 && !$isMode2) {
            throw new Exception('Minimal salah satu mode harus diisi.');
        }

        DB::beginTransaction();
        try {
            $results = [];
            $messages = [];

            // Mode 1: Simpan non-numeric data (jika diisi)
            if ($isMode1) {
                $result1 = $this->storeNonNumericData($data, $userId);
                $results['mode1'] = $result1['record'];
                $messages[] = $result1['message'];
            }

            // Mode 2: Simpan numeric data (jika diisi)
            if ($isMode2) {
                $result2 = $this->storeNumericData($data, $userId);
                $results['mode2'] = $result2['calculation'];
                $messages[] = $result2['message'];
            }

            DB::commit();

            return [
                'results' => $results,
                'message' => implode(' ', $messages),
            ];

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Lab Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Calculate all values for numeric data only (untuk edit/update)
     * Tidak perlu unique check dan database save
     * 
     * @param array $data Data numeric yang akan dihitung
     * @param string $kode Kode untuk mengambil master data
     * @return array Hasil perhitungan
     * @throws Exception
     */
    public function calculate(array $data, string $kode): array
    {
        // Ambil master data dari kode
        $masterData = OilMasterData::where('kode', $kode)
            ->where('is_active', true)
            ->first();

        if (!$masterData) {
            throw new Exception("Kode '{$kode}' tidak ditemukan di master data.");
        }

        // Hitung semua nilai
        return $this->calculateAllValues($data, $masterData);
    }

    /**
     * Simpan data non-numeric (Mode 1: bisa multiple per day)
     * 
     * @param array $data
     * @param int $userId
     * @return array
     */
    private function storeNonNumericData(array $data, int $userId): array
    {
        // VLOOKUP equivalent: ambil data dari master berdasarkan kode
        $masterData = OilMasterData::where('kode', $data['kode'])
            ->where('is_active', true)
            ->first();

        if (!$masterData) {
            throw new Exception("Kode '{$data['kode']}' tidak ditemukan di master data.");
        }

        // Simpan ke lab_records (tidak ada unique constraint, bisa multiple per day)
        $record = OilRecord::create([
            'user_id' => $userId,
            'lab_master_id' => $masterData->id,
            'kode' => $masterData->kode,
            'column_name' => $masterData->column_name,
            'pivot' => $masterData->pivot,
            'jenis' => $data['jenis'] ?? $masterData->jenis,
            'operator' => $data['operator'] ?? null,
            'sampel_boy' => $data['sampel_boy'] ?? null,
            'parameter_lain' => $data['parameter_lain'] ?? null,
        ]);

        return [
            'type' => 'non_numeric',
            'record' => $record,
            'message' => "Data Jenis & Sampel (Mode 1) untuk kode {$data['kode']} berhasil disimpan.",
        ];
    }

    /**
     * Simpan data numeric (Mode 2: hanya 1 per kode per hari)
     * 
     * FASE 1: Validasi kode_mode2 harus diisi
     * FASE 2: Cek apakah kombinasi (tanggal created_at + kode) sudah ada -> TOLAK jika sudah ada
     * FASE 3: Ambil master data dari kode
     * FASE 4: Hitung semua nilai (moist, dmwm, olwb, oldb, oil_losses)
     * FASE 5: Simpan ke lab_calculations
     * 
     * @param array $data
     * @param int $userId
     * @return array
     */
    private function storeNumericData(array $data, int $userId): array
    {
        // FASE 1: Validasi - kode_mode2 harus diisi untuk mode 2
        if (empty($data['kode_mode2'])) {
            throw new Exception('Kode harus dipilih untuk input data angka (Mode 2).');
        }

        $kode = $data['kode_mode2'];

        // FASE 2: Cek apakah kombinasi (tanggal hari ini + kode) sudah ada
        $existing = OilCalculation::whereDate('created_at', today())
            ->where('kode', $kode)
            ->first();

        if ($existing) {
            throw new Exception("Kode '{$kode}' untuk hari ini sudah ada. Setiap kode hanya boleh diinput sekali per hari.");
        }

        // FASE 3: Ambil master data dari kode
        $masterData = OilMasterData::where('kode', $kode)
            ->where('is_active', true)
            ->first();

        if (!$masterData) {
            throw new Exception("Kode '{$kode}' tidak ditemukan di master data.");
        }

        // FASE 4: Hitung semua nilai menggunakan parseNum
        $calculations = $this->calculateAllValues($data, $masterData);

        // FASE 5: Simpan ke lab_calculations
        $calculation = OilCalculation::create(
            array_merge($calculations, [
                'user_id' => $userId,
                'lab_master_id' => $masterData->id,
                'kode' => $masterData->kode,
            ])
        );

        return [
            'type' => 'numeric',
            'calculation' => $calculation,
            'message' => "Data perhitungan (Mode 2) untuk kode {$kode} berhasil disimpan.",
        ];
    }

    /**
     * Hitung semua nilai berdasarkan Apps Script logic
     * 
     * Rumus dari Apps Script:
     * - totalCawanBasah = cawanKosong + beratBasah
     * - sampelSetelahOven = cawanSampleKering - cawanKosong
     * - minyak = oilLabu - labuKosong
     * - moist = ((totalCawanBasah - cawanSampleKering) / beratBasah) * 100
     * - dmwm (Dry Matter Wet Mess) = 100 - moist
     * - olwb (Oil Losses Wet Basis) = (minyak / beratBasah) * 100
     * - oldb (Oil Losses Dry Basis) = (olwb / dmwm) * 100
     * - oillosses = (oldb / persen) * 100
     * 
     * @param array $data
     * @param OilMasterData $masterData
     * @return array
     */
    private function calculateAllValues(array $data, OilMasterData $masterData): array
    {
        // Parse numbers (Apps Script parseNum function)
        $cawanKosong = $this->parseNum($data['cawan_kosong'] ?? 0);
        $beratBasah = $this->parseNum($data['berat_basah'] ?? 0);
        $cawanSampleKering = $this->parseNum($data['cawan_sample_kering'] ?? 0);
        $labuKosong = $this->parseNum($data['labu_kosong'] ?? 0);
        $oilLabu = $this->parseNum($data['oil_labu'] ?? 0);

        // Validasi pembagi tidak boleh nol
        if ($beratBasah == 0) {
            throw new Exception('Berat Basah tidak boleh 0.');
        }

        // Hitung intermediate values
        $totalCawanBasah = $cawanKosong + $beratBasah;
        $sampelSetelahOven = $cawanSampleKering - $cawanKosong;
        $minyak = $oilLabu - $labuKosong;

        // Hitung moist dan dmwm
        $moist = (($beratBasah - $sampelSetelahOven) / $beratBasah) * 100;

        $moist == 0 ? $dmwm = 0 : $dmwm = 100 - $moist;

        // Hitung oil losses
        $olwb = ($minyak / $beratBasah) * 100;
        $oldb = $dmwm / 100 != 0 ? ($olwb / ($dmwm / 100)) : 0;

        // Get persen dari master data
        $persen = $this->parseNum($masterData->persen);
        $persen4 = $this->parseNum($masterData->persen4);

        // Hitung oil_losses jika persen tersedia
        // Beberapa kode (JBP, CD, COT, HP, dll) tidak memerlukan perhitungan ini

        $persen > 0 ? $oilLosses = (($oldb * ($dmwm / 100) * $persen) - $persen4) : $oilLosses = 0;

        // Get limits dari master data
        $limitOLWB = $this->parseNum($masterData->limitOLWB);
        $limitOLDB = $this->parseNum($masterData->limitOLDB);
        $limitOL = $this->parseNum($masterData->limitOL);

        return [
            'cawan_kosong' => $cawanKosong,
            'berat_basah' => $beratBasah,
            'cawan_sample_kering' => $cawanSampleKering,
            'labu_kosong' => $labuKosong,
            'oil_labu' => $oilLabu,
            'total_cawan_basah' => $totalCawanBasah,
            'sampel_setelah_oven' => $sampelSetelahOven,
            'minyak' => $minyak,
            'moist' => round($moist, 2),
            'dmwm' => round($dmwm, 2),
            'olwb' => round($olwb, 4),
            'oldb' => round($oldb, 4),
            'oil_losses' => round($oilLosses, 2),
            'limitOLWB' => $limitOLWB,
            'limitOLDB' => $limitOLDB,
            'limitOL' => $limitOL,
            'persen' => $persen,
            'persen4' => $persen4,
        ];
    }

    /**
     * Parse number dari string (Apps Script parseNum equivalent)
     * Menghandle null, NaN, empty string
     * 
     * @param mixed $value
     * @return float
     */
    private function parseNum($value): float
    {
        if (is_null($value) || $value === '' || $value === 'NaN') {
            return 0.0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        // Try to clean the string
        $cleaned = preg_replace('/[^0-9.-]/', '', (string) $value);
        return is_numeric($cleaned) ? (float) $cleaned : 0.0;
    }
}
