<?php

namespace App\Services;

use App\Models\OilCalculation;
use App\Models\OilMasterData;
use App\Models\OilRecord;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OilService
{
    /**
     * Validasi dan simpan data lab dengan dual-mode input
     * 
     * Mode 1: Non-numeric data (kode, jenis, operator, dll) - bisa multiple per day
     * Mode 2: Numeric data (kode_mode2, cawan_kosong, berat_basah, dll) - hanya 1 per kode per day per office
     * User BISA mengisi kedua mode sekaligus dalam satu submission
     * 
     * @param array $data
     * @param int $userId
     * @param string $userOffice Office dari user yang login (YBS, SUN, SJN)
     * @return array
     * @throws Exception
     */
    public function store(array $data, int $userId, string $userOffice): array
    {
        $isMode1 = !empty($data['kode']); // Mode 1: Non-numeric
        $mode2NumericFields = [
            'cawan_kosong',
            'berat_basah',
            'cawan_sample_kering',
            'labu_kosong',
            'oil_labu',
        ];

        // Mode 2 aktif jika kode dipilih dan minimal 1 field angka terisi.
        // Gunakan pengecekan ketat agar nilai 0 tetap dianggap valid.
        $isMode2 = !empty($data['kode_mode2'])
            && collect($mode2NumericFields)->contains(function ($field) use ($data) {
                return array_key_exists($field, $data) && $data[$field] !== null && $data[$field] !== '';
            });

        if (!$isMode1 && !$isMode2) {
            throw new Exception('Minimal salah satu mode harus diisi.');
        }

        DB::beginTransaction();
        try {
            $results = [];
            $messages = [];

            // Mode 1: Simpan non-numeric data (jika diisi)
            if ($isMode1) {
                $result1 = $this->storeNonNumericData($data, $userId, $userOffice);
                $results['mode1'] = $result1['record'];
                $messages[] = $result1['message'];
            }

            // Mode 2: Simpan numeric data (jika diisi)
            if ($isMode2) {
                $result2 = $this->storeNumericData($data, $userId, $userOffice);
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
    public function calculate(array $data, string $kode, ?string $office = null): array
    {
        // Ambil master data dari kode
        $officeCode = strtoupper(trim((string) $office));

        $masterData = OilMasterData::where('kode', $kode)
            ->when($officeCode !== '' && $officeCode !== 'ALL', fn($q) => $q->where('office', $officeCode))
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
     * @param string $userOffice
     * @return array
     */
    private function storeNonNumericData(array $data, int $userId, string $userOffice): array
    {
        // VLOOKUP equivalent: ambil data dari master berdasarkan kode
        $masterData = OilMasterData::where('kode', $data['kode'])
            ->where('office', $userOffice)
            ->where('is_active', true)
            ->first();

        if (!$masterData) {
            throw new Exception("Kode '{$data['kode']}' tidak ditemukan di master data.");
        }

        // Simpan ke lab_records (tidak ada unique constraint, bisa multiple per day)
        $record = OilRecord::create([
            'user_id' => $userId,
            'office' => $userOffice,  // Multi-tenancy: office dari user
            'oil_master_id' => $masterData->id,
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
     * Simpan data numeric (Mode 2: hanya 1 per kode per hari produksi per office)
     * 
     * FASE 1: Validasi kode_mode2 harus diisi
     * FASE 2: Cek apakah kombinasi (office + tanggal + kode) sudah ada -> TOLAK jika sudah ada
     * FASE 3: Ambil master data dari kode
     * FASE 4: Hitung semua nilai (moist, dmwm, olwb, oldb, oil_losses)
     * FASE 5: Simpan ke lab_calculations
     * 
     * @param array $data
     * @param int $userId
     * @param string $userOffice
     * @return array
     */
    private function storeNumericData(array $data, int $userId, string $userOffice): array
    {
        // FASE 1: Validasi - kode_mode2 harus diisi untuk mode 2
        if (empty($data['kode_mode2'])) {
            throw new Exception('Kode harus dipilih untuk input data angka (Mode 2).');
        }

        $kode = $data['kode_mode2'];
        $phase = $this->detectMode2Phase($data);

        $step1Data = [];
        foreach (['cawan_kosong', 'berat_basah', 'labu_kosong'] as $field) {
            if (array_key_exists($field, $data) && $data[$field] !== null && $data[$field] !== '') {
                $step1Data[$field] = $data[$field];
            }
        }

        $step2Data = [];
        if (array_key_exists('cawan_sample_kering', $data) && $data['cawan_sample_kering'] !== null && $data['cawan_sample_kering'] !== '') {
            $step2Data['cawan_sample_kering'] = $data['cawan_sample_kering'];
        }

        $finalData = [];
        if (array_key_exists('oil_labu', $data) && $data['oil_labu'] !== null && $data['oil_labu'] !== '') {
            $finalData['oil_labu'] = $data['oil_labu'];
        }

        $existingOpenBatch = OilCalculation::where('office', $userOffice)
            ->where('kode', $kode)
            ->where('status', 'partial')
            ->latest('id')
            ->first();

        $existingCompletedBatch = OilCalculation::where('office', $userOffice)
            ->where('kode', $kode)
            ->where('status', 'complete')
            ->whereBetween('created_at', $this->resolveProductionRangeFor($this->resolveProductionDateKey(Carbon::now())))
            ->latest('id')
            ->first();

        if ($phase === 'initial' && $existingCompletedBatch && !$existingOpenBatch) {
            throw new Exception("Kode '{$kode}' untuk office {$userOffice} pada hari produksi ini (07:00-06:59) sudah selesai diproses. Gunakan kode lain atau buka data yang sudah ada untuk koreksi.");
        }

        if ($phase === 'complete' && $existingCompletedBatch && !$existingOpenBatch) {
            throw new Exception("Kode '{$kode}' untuk office {$userOffice} pada hari produksi ini (07:00-06:59) sudah ada. Setiap kode hanya boleh diinput sekali per hari produksi per office.");
        }

        // FASE 3: Ambil master data dari kode
        $masterData = OilMasterData::where('kode', $kode)
            ->where('office', $userOffice)
            ->where('is_active', true)
            ->first();

        if (!$masterData) {
            throw new Exception("Kode '{$kode}' tidak ditemukan di master data.");
        }

        $basePayload = [
            'user_id' => $existingOpenBatch?->user_id ?? $userId,
            'office' => $userOffice,
            'oil_master_id' => $masterData->id,
            'kode' => $masterData->kode,
            'initial_user_id' => $existingOpenBatch?->initial_user_id ?? ($phase === 'initial' || $phase === 'complete' ? $userId : null),
            'final_user_id' => $existingOpenBatch?->final_user_id ?? ($phase === 'complete' ? $userId : null),
        ];

        if ($phase === 'initial') {
            // Tahap awal tidak boleh menimpa draft lama untuk kode yang sama.
            if ($existingOpenBatch) {
                throw new Exception("Kode '{$kode}' untuk office {$userOffice} sudah memiliki data tahap awal yang belum diselesaikan. Selesaikan dengan menginput tahap akhir terlebih dahulu, atau hubungi administrator untuk koreksi.");
            }

            $payload = array_merge($basePayload, [
                'phase' => 'initial',
                'status' => 'partial',
            ], $this->normalizeInitialPayload($step1Data));

            $calculation = OilCalculation::create($payload);

            return [
                'type' => 'numeric',
                'calculation' => $calculation,
                'phase' => 'initial',
                'status' => 'partial',
                'message' => "Tahap 1 (cawan kosong, berat sampel basah, labu kosong) untuk kode {$kode} berhasil disimpan sebagai draft.",
            ];
        }

        // Mapping enum lama: gunakan phase "final" untuk tahap ke-2.
        if ($phase === 'final') {
            if (!$existingOpenBatch) {
                throw new Exception("Kode '{$kode}' belum memiliki data Tahap 1 yang aktif. Simpan Tahap 1 terlebih dahulu sebelum input Tahap 2.");
            }

            $mergedData = array_merge($existingOpenBatch->only([
                'cawan_kosong',
                'berat_basah',
                'labu_kosong',
                'cawan_sample_kering',
                'oil_labu',
            ]), $step2Data);

            if ($mergedData['cawan_kosong'] === null || $mergedData['berat_basah'] === null || $mergedData['labu_kosong'] === null) {
                throw new Exception("Data Tahap 1 untuk kode '{$kode}' belum lengkap. Lengkapi Tahap 1 terlebih dahulu.");
            }

            $payload = array_merge($basePayload, $this->normalizeInitialPayload($mergedData), $this->normalizeFinalPayload($mergedData), [
                'phase' => 'final',
                'status' => 'partial',
            ]);

            $existingOpenBatch->update($payload);

            return [
                'type' => 'numeric',
                'calculation' => $existingOpenBatch->fresh(),
                'phase' => 'final',
                'status' => 'partial',
                'message' => "Tahap 2 (cawan + sampel kering) untuk kode {$kode} berhasil disimpan. Lanjutkan Tahap Akhir untuk hasil perhitungan.",
            ];
        }

        $mergedForComplete = array_merge(
            $existingOpenBatch?->only([
                'cawan_kosong',
                'berat_basah',
                'labu_kosong',
                'cawan_sample_kering',
                'oil_labu',
            ]) ?? [],
            $step1Data,
            $step2Data,
            $finalData
        );

        $requiredCompleteFields = ['cawan_kosong', 'berat_basah', 'labu_kosong', 'cawan_sample_kering', 'oil_labu'];
        foreach ($requiredCompleteFields as $field) {
            if (!array_key_exists($field, $mergedForComplete) || $mergedForComplete[$field] === null || $mergedForComplete[$field] === '') {
                throw new Exception("Tahap akhir membutuhkan data lengkap Tahap 1 dan Tahap 2. Field '{$field}' belum tersedia untuk kode '{$kode}'.");
            }
        }

        $calculations = $this->calculateAllValues($mergedForComplete, $masterData);
        $payload = array_merge($basePayload, $calculations, $this->normalizeInitialPayload($mergedForComplete), $this->normalizeFinalPayload($mergedForComplete), [
            'phase' => 'complete',
            'status' => 'complete',
            'final_user_id' => $userId,
        ]);

        $calculation = $existingOpenBatch
            ? tap($existingOpenBatch)->update($payload)
            : OilCalculation::create($payload);

        return [
            'type' => 'numeric',
            'calculation' => $existingOpenBatch?->fresh() ?? $calculation,
            'phase' => 'complete',
            'status' => 'complete',
            'message' => "Tahap akhir (oil + labu) untuk kode {$kode} berhasil disimpan. Hasil perhitungan sudah lengkap.",
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
            throw new Exception('Berat Sampel Basah tidak boleh 0.');
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
            'limit_operator' => $masterData->limit_operator,
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

    /**
     * Normalize early-phase payload for storage.
     */
    private function normalizeInitialPayload(array $data): array
    {
        return [
            'cawan_kosong' => array_key_exists('cawan_kosong', $data) && $data['cawan_kosong'] !== null && $data['cawan_kosong'] !== ''
                ? $this->parseNum($data['cawan_kosong'])
                : null,
            'berat_basah' => array_key_exists('berat_basah', $data) && $data['berat_basah'] !== null && $data['berat_basah'] !== ''
                ? $this->parseNum($data['berat_basah'])
                : null,
            'labu_kosong' => array_key_exists('labu_kosong', $data) && $data['labu_kosong'] !== null && $data['labu_kosong'] !== ''
                ? $this->parseNum($data['labu_kosong'])
                : null,
        ];
    }

    /**
     * Normalize late-phase payload for storage.
     */
    private function normalizeFinalPayload(array $data): array
    {
        return [
            'cawan_sample_kering' => array_key_exists('cawan_sample_kering', $data) && $data['cawan_sample_kering'] !== null && $data['cawan_sample_kering'] !== ''
                ? $this->parseNum($data['cawan_sample_kering'])
                : null,
            'oil_labu' => array_key_exists('oil_labu', $data) && $data['oil_labu'] !== null && $data['oil_labu'] !== ''
                ? $this->parseNum($data['oil_labu'])
                : null,
        ];
    }

    /**
     * Detect phase otomatis dari field Mode 2 yang terisi.
     */
    private function detectMode2Phase(array $data): string
    {
        $hasStep1 = collect(['cawan_kosong', 'berat_basah', 'labu_kosong'])
            ->contains(fn($key) => array_key_exists($key, $data) && $data[$key] !== null && $data[$key] !== '');

        $hasStep2 = array_key_exists('cawan_sample_kering', $data) && $data['cawan_sample_kering'] !== null && $data['cawan_sample_kering'] !== '';
        $hasFinal = array_key_exists('oil_labu', $data) && $data['oil_labu'] !== null && $data['oil_labu'] !== '';

        if ($hasFinal) {
            return 'complete';
        }

        // Mapping enum lama: "final" dipakai sebagai tahap ke-2.
        if ($hasStep2) {
            return 'final';
        }

        if ($hasStep1) {
            return 'initial';
        }

        return 'initial';
    }

    private function resolveProductionRangeFor(string $productionDate): array
    {
        $start = Carbon::parse($productionDate)->setTime(7, 0, 0);
        $end = Carbon::parse($productionDate)->addDay()->setTime(6, 59, 59);

        return [$start, $end];
    }

    private function resolveProductionDateKey(Carbon $timestamp): string
    {
        $productionDate = $timestamp->copy();
        if ((int) $productionDate->format('H') < 7) {
            $productionDate->subDay();
        }

        return $productionDate->format('Y-m-d');
    }
}
