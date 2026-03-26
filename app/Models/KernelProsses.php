<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class KernelProsses extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kernel_prosses';

    protected $fillable = [
        'user_id',
        'office',
        'process_date',
        'input_team',
        'team_1_start_time',
        'team_1_end_time',
        'team_1_start_downtime',
        'team_1_end_downtime',
        'team_1_downtime',
        'team_1_members',
        'team_2_start_time',
        'team_2_end_time',
        'team_2_start_downtime',
        'team_2_end_downtime',
        'team_2_downtime',
        'team_2_members',
    ];

    protected $casts = [
        'process_date' => 'date',
        'team_1_members' => 'array',
        'team_2_members' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mesin(): HasMany
    {
        return $this->hasMany(KernelMesin::class, 'kernel_prosses_id');
    }
}
