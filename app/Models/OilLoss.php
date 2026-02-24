<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OilLoss extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'analysis_date',
        'analysis_time',
        'tbs_weight',
        'moisture_content',
        'ffa_content',
        'cpo_produced',
        'kernel_produced',
        'oil_to_tbs',
        'kernel_to_tbs',
        'oil_losses',
        'total_losses',
        'batch_number',
        'notes',
        'status',
        'approved_by',
        'approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'analysis_date' => 'date',
        'analysis_time' => 'datetime:H:i',
        'tbs_weight' => 'decimal:2',
        'moisture_content' => 'decimal:2',
        'ffa_content' => 'decimal:2',
        'cpo_produced' => 'decimal:2',
        'kernel_produced' => 'decimal:2',
        'oil_to_tbs' => 'decimal:2',
        'kernel_to_tbs' => 'decimal:2',
        'oil_losses' => 'decimal:2',
        'total_losses' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user who created this oil loss record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who approved this oil loss record.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope untuk filter berdasarkan status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan tanggal.
     */
    public function scopeDateBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('analysis_date', [$startDate, $endDate]);
    }

    /**
     * Check if record is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if record can be edited.
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, ['draft', 'rejected']);
    }
}
