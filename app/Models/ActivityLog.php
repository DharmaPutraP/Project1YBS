<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    const UPDATED_AT = null; // We only track created_at

    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'metadata',
        'ip_address',
        'user_agent',
        'url',
        'method',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user who performed the activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted action badge color
     */
    public function getActionBadgeColorAttribute(): string
    {
        return match ($this->action) {
            'create', 'input' => 'green',
            'update', 'edit' => 'yellow',
            'delete', 'destroy' => 'red',
            'login' => 'blue',
            'logout' => 'gray',
            'export' => 'indigo',
            'approve' => 'teal',
            'reject' => 'orange',
            default => 'gray',
        };
    }

    /**
     * Get human-readable action label
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'create' => 'Buat',
            'input' => 'Input Data',
            'update' => 'Update',
            'edit' => 'Edit',
            'delete' => 'Hapus',
            'destroy' => 'Hapus',
            'login' => 'Login',
            'logout' => 'Logout',
            'export' => 'Export',
            default => ucfirst($this->action),
        };
    }

    /**
     * Scope: Filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filter by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: Filter by model type
     */
    public function scopeByModelType($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope: Recent activities (last 30 days)
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
