<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyBobotAverage extends Model
{
    protected $fillable = [
        'date',
        'average_score',
    ];

    protected $casts = [
        'date' => 'date',
        'average_score' => 'decimal:2',
    ];
}
