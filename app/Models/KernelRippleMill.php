<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KernelRippleMill extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kernel_ripple_mill';

    protected $fillable = [
        'user_id',
        'office',
        'rounded_time',
        'kode',
        'jenis',
        'operator',
        'sampel_boy',
        'pengulangan',
        'remarks',
        'berat_sampel',
        'berat_nut_utuh',
        'berat_nut_pecah',
        'sample_nut_utuh',
        'sample_nut_pecah',
        'efficiency',
        'limit_operator',
        'limit_value',
    ];

    protected $casts = [
        'rounded_time' => 'datetime',
        'pengulangan' => 'boolean',
        'berat_sampel' => 'decimal:4',
        'berat_nut_utuh' => 'decimal:4',
        'berat_nut_pecah' => 'decimal:4',
        'sample_nut_utuh' => 'decimal:6',
        'sample_nut_pecah' => 'decimal:6',
        'efficiency' => 'decimal:6',
        'limit_value' => 'decimal:3',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}