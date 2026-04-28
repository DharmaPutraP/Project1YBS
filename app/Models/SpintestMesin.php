<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpintestMesin extends Model
{
    use HasFactory;

    protected $table = 'informasi_mesin_spintest';

    protected $fillable = [
        'spintest_prosses_id',
        'team_name',
        'machine_group',
        'machine_name',
        'production_start_time',
        'production_end_time',
        'total_production_hours',
        'is_spare_input',
        'is_spare',
    ];

    public function proses(): BelongsTo
    {
        return $this->belongsTo(SpintestProsses::class, 'spintest_prosses_id');
    }
}
