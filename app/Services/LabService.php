<?php

namespace App\Services;

use App\Models\LabCalculation;
use App\Models\LabMasterData;
use App\Models\LabRecord;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LabService
{
    /**
     * Validasi dan simpan data lab dengan dual-mode input
     * 
     * Mode 1: Non-numeric data (kode, jenis, operator, dll) - bisa multiple per day
     * Mode 2: Numeric data (cawan_kosong, berat_basah, dll) - hanya 1 per day
     * 
     * @param array $data
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function store(array $data, int $userId): array
    {
        // Validasi dual-mode: user tidak boleh isi keduanya sekaligus
        $isDataBaru = !empty($data['kode']); // Mode 1: Non-numeric
        $isDataAngka = !empty($data['cawan_kosong']) || !empty($data['berat_basah']); // Mode 2: Numeric

        if ($isDataBaru && $isDataAngka) {
            throw new Exception('Pilih: Isi data Jam/Kode ATAU isi Angkanya! Tidak boleh keduanya sekaligus.');
        }

        if (!$isDataBaru && !$isDataAngka) {
            throw new Exception('Minimal salah satu harus diisi: data Jam/Kode atau data Angka.');
        }

        DB::beginTransaction();
        try {
            $result = [];

            if ($isDataBaru) {
                // Mode 1: Simpan non-numeric data (bisa multiple per day)
                $result = $this->storeNonNumericData($data, $userId);
            } else {
                // Mode 2: Simpan numeric data (hanya 1 per day dengan smart shifting)
                $result = $this->storeNumericData($data, $userId);
            }

            DB::commit();
            return $result;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Lab Service Error: ' . $e->getMessage());
            throw $e;
        }
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
        $masterData = LabMasterData::where('kode', $data['kode'])
            ->where('is_active', true)
            ->first();

        if (!$masterData) {
            throw new Exception("Kode '{$data['kode']}' tidak ditemukan di master data.");
        }

        // Simpan ke lab_records (tidak ada unique constraint, bisa multiple per day)
        $record = LabRecord::create([
            'user_id' => $userId,
            'analysis_date' => $data['analysis_date'],
            'analysis_time' => $data['analysis_time'] ?? now()->format('H:i'),
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
            'message' => 'Data non-numeric berhasil disimpan. Bisa input lagi untuk tanggal yang sama.',
        ];
    }

    /**
     * Simpan data numeric (Mode 2: hanya 1 per kode per hari)
     * 
     * FASE 1: Validasi kode harus diisi
     * FASE 2: Cek apakah kombinasi (tanggal + kode) sudah ada -> TOLAK jika sudah ada
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
        // FASE 1: Validasi - kode harus diisi untuk mode 2
        if (empty($data['kode'])) {
            throw new Exception('Kode harus dipilih untuk input data angka.');
        }

        // FASE 2: Cek apakah kombinasi (analysis_date, kode) sudah ada
        $existing = LabCalculation::where('analysis_date', $data['analysis_date'])
            ->where('kode', $data['kode'])
            ->first();

        if ($existing) {
            throw new Exception("Kode '{$data['kode']}' untuk tanggal {$data['analysis_date']} sudah ada. Setiap kode hanya boleh diinput sekali per hari.");
        }

        // FASE 3: Ambil master data dari kode
        $masterData = LabMasterData::where('kode', $data['kode'])
            ->where('is_active', true)
            ->first();

        if (!$masterData) {
            throw new Exception("Kode '{$data['kode']}' tidak ditemukan di master data.");
        }

        // FASE 4: Hitung semua nilai menggunakan parseNum
        $calculations = $this->calculateAllValues($data, $masterData);

        // FASE 5: Simpan ke lab_calculations
        $calculation = LabCalculation::create(
            array_merge($calculations, [
                'user_id' => $userId,
                'analysis_date' => $data['analysis_date'],
                'lab_master_id' => $masterData->id,
                'kode' => $masterData->kode,
            ])
        );

        return [
            'type' => 'numeric',
            'calculation' => $calculation,
            'message' => "Data numeric untuk kode {$data['kode']} berhasil disimpan.",
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
     * @param LabMasterData $masterData
     * @return array
     */
    private function calculateAllValues(array $data, LabMasterData $masterData): array
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
        $moist = (($totalCawanBasah - $cawanSampleKering) / $beratBasah) * 100;
        $dmwm = 100 - $moist;

        // Validasi dmwm tidak boleh nol
        if ($dmwm == 0) {
            throw new Exception('DMWM tidak boleh 0.');
        }

        // Hitung oil losses
        $olwb = ($minyak / $beratBasah) * 100;
        $oldb = ($olwb / $dmwm) * 100;

        // Get persen dari master data
        $persen = $this->parseNum($masterData->persen);
        $persen4 = $this->parseNum($masterData->persen4);

        // Validasi persen tidak boleh nol
        if ($persen == 0) {
            throw new Exception('Persen tidak boleh 0.');
        }

        $oilLosses = (($oldb * ($dmwm / 100) * $persen) - $persen4);

        // Get limits dari master data
        $limit = $this->parseNum($masterData->limit);
        $limit2 = $this->parseNum($masterData->limit2);
        $limit3 = $this->parseNum($masterData->limit3);

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
            'limit' => $limit,
            'limit2' => $limit2,
            'limit3' => $limit3,
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
