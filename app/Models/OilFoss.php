<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OilFoss extends Model
{
    use HasFactory;

    protected $table = 'oil_foss';

    protected $fillable = [
        'user_id',
        'created_by',
        'tanggal',
        'waktu',
        'operator',
        'shift',
        'machine_group',
        'machine_name',
        'moist',
        'olwb',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'shift' => 'integer',
        'moist' => 'decimal:2',
        'olwb' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
