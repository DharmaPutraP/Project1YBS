<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'analysis_date',
        'analysis_time',
        'lab_master_id',
        'kode',
        'column_name',
        'pivot',
        'jenis',
        'operator',
        'sampel_boy',
        'parameter_lain',
    ];

    protected $casts = [
        'analysis_date' => 'date',
        'analysis_time' => 'datetime:H:i',
    ];

    /**
     * Get the user who created this record.
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
        return $this->belongsTo(LabMasterData::class, 'lab_master_id');
    }

    /**
     * Scope untuk filter berdasarkan tanggal.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('analysis_date', $date);
    }
}
