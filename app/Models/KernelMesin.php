<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KernelMesin extends Model
{
    use HasFactory;

    protected $table = 'kernel_mesin';

    protected $fillable = [
        'kernel_prosses_id',
        'team_name',
        'machine_group',
        'machine_name',
        'production_start_time',
        'production_end_time',
        'total_produsction_hours',
        'is_spare',
        'is_spare_input',
    ];

    protected $casts = [
        'total_produsction_hours' => 'decimal:2',
        'is_spare' => 'decimal:2',
        'is_spare_input' => 'boolean',
    ];

    public function process(): BelongsTo
    {
        return $this->belongsTo(KernelProsses::class, 'kernel_prosses_id');
    }
}
