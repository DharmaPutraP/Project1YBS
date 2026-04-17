<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OilRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'oil_records';

    protected $fillable = [
        'user_id',
        'office',       // Office/PT (YBS, SUN, SJN)
        'oil_master_id',
        'kode',
        'tanggal_sampel',
        'column_name',
        'pivot',
        'jenis',
        'operator',
        'sampel_boy',
        'parameter_lain',
    ];

    protected $casts = [
        'tanggal_sampel' => 'date:Y-m-d',
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
        return $this->belongsTo(OilMasterData::class, 'oil_master_id');
    }

    /**
     * Scope untuk filter berdasarkan tanggal sampel.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('tanggal_sampel', $date);
    }
}
