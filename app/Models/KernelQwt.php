<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KernelQwt extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kernel_qwt';

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
        'sampel_setelah_kuarter',
        'berat_nut_utuh',
        'berat_nut_pecah',
        'berat_kernel_utuh',
        'berat_kernel_pecah',
        'berat_cangkang',
        'berat_batu',
        'berat_fiber',
        'berat_broken_nut',
        'total_berat_nut',
        'bn_tn',
        'moisture',
        'ampere_screw',
        'tekanan_hydraulic',
        'kecepatan_screw',
        'bn_tn_limit_operator',
        'bn_tn_limit_value',
        'moist_limit_operator',
        'moist_limit_value',
    ];

    protected $casts = [
        'rounded_time' => 'datetime',
        'pengulangan' => 'boolean',
        'sampel_setelah_kuarter' => 'decimal:4',
        'berat_nut_utuh' => 'decimal:4',
        'berat_nut_pecah' => 'decimal:4',
        'berat_kernel_utuh' => 'decimal:4',
        'berat_kernel_pecah' => 'decimal:4',
        'berat_cangkang' => 'decimal:4',
        'berat_batu' => 'decimal:4',
        'berat_fiber' => 'decimal:6',
        'berat_broken_nut' => 'decimal:6',
        'total_berat_nut' => 'decimal:6',
        'bn_tn' => 'decimal:6',
        'moisture' => 'decimal:6',
        'ampere_screw' => 'decimal:4',
        'tekanan_hydraulic' => 'decimal:4',
        'kecepatan_screw' => 'decimal:4',
        'bn_tn_limit_value' => 'decimal:3',
        'moist_limit_value' => 'decimal:3',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}