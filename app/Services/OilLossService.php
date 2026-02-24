<?php

namespace App\Services;

use App\Models\OilLoss;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk menghitung Oil Losses dari data produksi CPO.
 * 
 * Rumus Perhitungan:
 * 1. Oil to TBS (OER) = (CPO Produced / TBS Weight) × 100%
 * 2. Kernel to TBS (KER) = (Kernel Produced / TBS Weight) × 100%
 * 3. Oil Losses = Standard OER - Actual OER
 * 4. Total Losses (kg) = (Oil Losses% / 100) × TBS Weight
 */
class OilLossService
{
    /**
     * Standard Oil Extraction Rate (OER) - bisa disesuaikan per pabrik
     * Default: 22% (standar industri untuk fresh fruit bunch)
     */
    private const STANDARD_OER = 22.0;

    /**
     * Standard Kernel Extraction Rate (KER)
     * Default: 5% (standar industri)
     */
    private const STANDARD_KER = 5.0;

    /**
     * Calculate oil losses dari input data.
     *
     * @param array $data Input data dari form
     * @return array Calculated data
     */
    public function calculate(array $data): array
    {
        $tbsWeight = (float) $data['tbs_weight'];
        $cpoProduced = (float) $data['cpo_produced'];
        $kernelProduced = (float) ($data['kernel_produced'] ?? 0);

        // Validasi input
        if ($tbsWeight <= 0) {
            throw new \InvalidArgumentException('Berat TBS harus lebih besar dari 0');
        }

        if ($cpoProduced < 0) {
            throw new \InvalidArgumentException('CPO yang dihasilkan tidak boleh negatif');
        }

        // 1. Hitung Oil to TBS Ratio (OER - Oil Extraction Rate)
        $oilToTbs = $tbsWeight > 0 ? ($cpoProduced / $tbsWeight) * 100 : 0;

        // 2. Hitung Kernel to TBS Ratio (KER - Kernel Extraction Rate)
        $kernelToTbs = $tbsWeight > 0 ? ($kernelProduced / $tbsWeight) * 100 : 0;

        // 3. Hitung Oil Losses (selisih dari standar)
        $oilLosses = self::STANDARD_OER - $oilToTbs;

        // 4. Hitung Total Losses dalam kg
        $totalLosses = ($oilLosses / 100) * $tbsWeight;

        // Jika losses negatif, berarti produksi melebihi standar (good performance)
        // Tapi tetap dicatat untuk analisis

        return [
            'oil_to_tbs' => round($oilToTbs, 2),
            'kernel_to_tbs' => round($kernelToTbs, 2),
            'oil_losses' => round($oilLosses, 2),
            'total_losses' => round($totalLosses, 2),
        ];
    }

    /**
     * Store oil loss record dengan perhitungan otomatis.
     *
     * @param array $data
     * @param int $userId
     * @return OilLoss
     */
    public function store(array $data, int $userId): OilLoss
    {
        try {
            DB::beginTransaction();

            // Calculate losses
            $calculated = $this->calculate($data);

            // Merge dengan data input
            $oilLossData = array_merge($data, [
                'user_id' => $userId,
                'oil_to_tbs' => $calculated['oil_to_tbs'],
                'kernel_to_tbs' => $calculated['kernel_to_tbs'],
                'oil_losses' => $calculated['oil_losses'],
                'total_losses' => $calculated['total_losses'],
                'status' => 'submitted', // Langsung submitted, butuh approval
            ]);

            $oilLoss = OilLoss::create($oilLossData);

            DB::commit();

            Log::info('Oil loss record created', [
                'id' => $oilLoss->id,
                'user_id' => $userId,
                'oil_losses' => $calculated['oil_losses'],
            ]);

            return $oilLoss;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create oil loss record', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
            ]);
            throw $e;
        }
    }

    /**
     * Update oil loss record.
     *
     * @param OilLoss $oilLoss
     * @param array $data
     * @return OilLoss
     */
    public function update(OilLoss $oilLoss, array $data): OilLoss
    {
        // Check if can be edited
        if (!$oilLoss->canBeEdited()) {
            throw new \Exception('Record yang sudah approved tidak dapat diedit');
        }

        try {
            DB::beginTransaction();

            // Recalculate losses
            $calculated = $this->calculate($data);

            // Update data
            $oilLoss->update(array_merge($data, [
                'oil_to_tbs' => $calculated['oil_to_tbs'],
                'kernel_to_tbs' => $calculated['kernel_to_tbs'],
                'oil_losses' => $calculated['oil_losses'],
                'total_losses' => $calculated['total_losses'],
            ]));

            DB::commit();

            Log::info('Oil loss record updated', [
                'id' => $oilLoss->id,
                'oil_losses' => $calculated['oil_losses'],
            ]);

            return $oilLoss->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update oil loss record', [
                'id' => $oilLoss->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Approve oil loss record.
     *
     * @param OilLoss $oilLoss
     * @param int $approverId
     * @return OilLoss
     */
    public function approve(OilLoss $oilLoss, int $approverId): OilLoss
    {
        if ($oilLoss->status === 'approved') {
            throw new \Exception('Record sudah di-approve sebelumnya');
        }

        $oilLoss->update([
            'status' => 'approved',
            'approved_by' => $approverId,
            'approved_at' => now(),
        ]);

        Log::info('Oil loss record approved', [
            'id' => $oilLoss->id,
            'approved_by' => $approverId,
        ]);

        return $oilLoss->fresh();
    }

    /**
     * Reject oil loss record.
     *
     * @param OilLoss $oilLoss
     * @param string|null $reason
     * @return OilLoss
     */
    public function reject(OilLoss $oilLoss, ?string $reason = null): OilLoss
    {
        $oilLoss->update([
            'status' => 'rejected',
            'notes' => $reason ? "REJECTED: {$reason}" : $oilLoss->notes,
        ]);

        Log::info('Oil loss record rejected', [
            'id' => $oilLoss->id,
            'reason' => $reason,
        ]);

        return $oilLoss->fresh();
    }

    /**
     * Get performance analysis untuk periode tertentu.
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getPerformanceAnalysis(string $startDate, string $endDate): array
    {
        $records = OilLoss::query()
            ->status('approved')
            ->dateBetween($startDate, $endDate)
            ->get();

        if ($records->isEmpty()) {
            return [
                'total_records' => 0,
                'avg_oil_losses' => 0,
                'total_tbs_processed' => 0,
                'total_losses_kg' => 0,
            ];
        }

        return [
            'total_records' => $records->count(),
            'avg_oil_losses' => round($records->avg('oil_losses'), 2),
            'avg_oer' => round($records->avg('oil_to_tbs'), 2),
            'total_tbs_processed' => round($records->sum('tbs_weight'), 2),
            'total_cpo_produced' => round($records->sum('cpo_produced'), 2),
            'total_losses_kg' => round($records->sum('total_losses'), 2),
            'standard_oer' => self::STANDARD_OER,
        ];
    }

    /**
     * Get standard OER value.
     *
     * @return float
     */
    public static function getStandardOER(): float
    {
        return self::STANDARD_OER;
    }

    /**
     * Get standard KER value.
     *
     * @return float
     */
    public static function getStandardKER(): float
    {
        return self::STANDARD_KER;
    }
}
