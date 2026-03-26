<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KernelCalculation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kernel_calculations';

    protected $fillable = [
        'user_id',
        'office',
        'rounded_time',
        'kode',
        'jenis',
        'operator',
        'sampel_boy',
        'pengulangan',
        'berat_sampel',
        'nut_utuh_nut',
        'nut_utuh_kernel',
        'nut_pecah_nut',
        'nut_pecah_kernel',
        'kernel_utuh',
        'kernel_pecah',
        'kernel_to_sampel_nut_utuh',
        'kernel_to_sampel_nut_pecah',
        'kernel_utuh_to_sampel',
        'kernel_pecah_to_sampel',
        'kernel_losses',
    ];

    protected $casts = [
        'rounded_time' => 'datetime',
        'pengulangan' => 'boolean',
        'berat_sampel' => 'decimal:4',
        'nut_utuh_nut' => 'decimal:4',
        'nut_utuh_kernel' => 'decimal:4',
        'nut_pecah_nut' => 'decimal:4',
        'nut_pecah_kernel' => 'decimal:4',
        'kernel_utuh' => 'decimal:4',
        'kernel_pecah' => 'decimal:4',
        'kernel_to_sampel_nut_utuh' => 'decimal:6',
        'kernel_to_sampel_nut_pecah' => 'decimal:6',
        'kernel_utuh_to_sampel' => 'decimal:6',
        'kernel_pecah_to_sampel' => 'decimal:6',
        'kernel_losses' => 'decimal:6',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('created_at', $date);
    }
}
