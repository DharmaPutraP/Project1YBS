<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BobotConfig extends Model
{
    protected $fillable = [
        'jenis',
        'limit_100',
        'limit_90',
        'limit_80',
        'limit_70',
        'limit_60',
        'limit_50',
    ];
}
