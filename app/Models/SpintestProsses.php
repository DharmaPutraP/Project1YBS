<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SpintestMesin;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpintestProsses extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'spintest_prosses';

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
        'team_1_other_conditions',
        'team_2_start_time',
        'team_2_end_time',
        'team_2_start_downtime',
        'team_2_end_downtime',
        'team_2_downtime',
        'team_2_members',
        'team_2_other_conditions',
    ];

    protected $casts = [
        'process_date' => 'date',
        'team_1_members' => 'array',
        'team_2_members' => 'array',
        'team_1_other_conditions' => 'array',
        'team_2_other_conditions' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mesin(): HasMany
    {
        return $this->hasMany(SpintestMesin::class, 'spintest_prosses_id');
    }
}
