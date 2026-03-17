<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KernelDirtMoistCalculation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kernel_dirt_moist_calculations';

    protected $fillable = [
        'user_id',
        'kode',
        'jenis',
        'operator',
        'sampel_boy',
        'berat_sampel',
        'berat_dirty',
        'dirty_to_sampel',
        'moist_percent',
        'dirty_limit_operator',
        'dirty_limit_value',
        'moist_limit_operator',
        'moist_limit_value',
    ];

    protected $casts = [
        'berat_sampel' => 'decimal:4',
        'berat_dirty' => 'decimal:4',
        'dirty_to_sampel' => 'decimal:6',
        'moist_percent' => 'decimal:6',
        'dirty_limit_value' => 'decimal:3',
        'moist_limit_value' => 'decimal:3',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
