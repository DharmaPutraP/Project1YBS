<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OilCalculation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'oil_calculations';

    protected $fillable = [
        'user_id',
        'oil_master_id',
        'kode',
        'cawan_kosong',
        'berat_basah',
        'cawan_sample_kering',
        'labu_kosong',
        'oil_labu',
        'total_cawan_basah',
        'sampel_setelah_oven',
        'minyak',
        'moist',
        'dmwm',
        'olwb',
        'oldb',
        'oil_losses',
        'limitOLWB',
        'limitOLDB',
        'limitOL',
        'persen',
        'persen4',
    ];

    protected $casts = [
        'cawan_kosong' => 'decimal:6',
        'berat_basah' => 'decimal:6',
        'cawan_sample_kering' => 'decimal:6',
        'labu_kosong' => 'decimal:6',
        'oil_labu' => 'decimal:6',
        'total_cawan_basah' => 'decimal:6',
        'sampel_setelah_oven' => 'decimal:6',
        'minyak' => 'decimal:6',
        'moist' => 'decimal:6',
        'dmwm' => 'decimal:6',
        'olwb' => 'decimal:6',
        'oldb' => 'decimal:6',
        'oil_losses' => 'decimal:6',
        'limitOLWB' => 'decimal:6',
        'limitOLDB' => 'decimal:6',
        'limitOL' => 'decimal:6',
        'persen' => 'decimal:6',
        'persen4' => 'decimal:6',
    ];

    /**
     * Get the user who created this calculation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the master data reference.
     */
    public function masterData(): BelongsTo
    {
        return $this->belongsTo(OilMasterData::class, 'oil_master_id');
    }

    /**
     * Scope untuk filter berdasarkan tanggal.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('created_at', $date);
    }
}
