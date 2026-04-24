<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FfaMoisture extends Model
{
    use HasFactory;

    protected $table = 'FFA_Moisture';

    protected $fillable = [
        'user_id',
        'created_by',
        'tanggal',
        'jam',
        'moisture',
        'bst1_ffa',
        'bst2_ffa',
        'bst3_ffa',
        'impurities',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'bst1_ffa' => 'decimal:2',
        'bst2_ffa' => 'decimal:2',
        'bst3_ffa' => 'decimal:2',
        'impurities' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
