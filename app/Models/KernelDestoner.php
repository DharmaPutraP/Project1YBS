<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KernelDestoner extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kernel_destoner';

    protected $fillable = [
        'user_id',
        'office',
        'kode',
        'jenis',
        'rounded_time',
        'kegiatan_dispek',
        'operator',
        'sampel_boy',
        'pengulangan',
        'remarks',
        'berat_sampel',
        'time',
        'berat_nut',
        'berat_kernel',
        'konversi_kg',
        'rasio_jam_kg',
        'persen_nut',
        'persen_kernel',
        'total_losses_kernel',
        'loss_kernel_jam',
        'loss_kernel_tbs',
        'limit_operator',
        'limit_value',
    ];

    protected $casts = [
        'rounded_time' => 'datetime',
        'kegiatan_dispek' => 'boolean',
        'pengulangan' => 'boolean',
        'berat_sampel' => 'float',
        'time' => 'float',
        'berat_nut' => 'float',
        'berat_kernel' => 'float',
        'konversi_kg' => 'float',
        'rasio_jam_kg' => 'float',
        'persen_nut' => 'float',
        'persen_kernel' => 'float',
        'total_losses_kernel' => 'float',
        'loss_kernel_jam' => 'float',
        'loss_kernel_tbs' => 'float',
        'limit_value' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
