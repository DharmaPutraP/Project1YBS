<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpintestFeedDecanter extends Model
{
    use HasFactory;

    protected $table = 'spintest_feed_decanter';

    protected $fillable = [
        'user_id',
        'tanggal',
        'jam',
        'machine_name',
        'oil',
        'emulsi',
        'air',
        'nos',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'oil' => 'decimal:2',
        'emulsi' => 'decimal:2',
        'air' => 'decimal:2',
        'nos' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
