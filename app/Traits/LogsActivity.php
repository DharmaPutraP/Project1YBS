<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    /**
     * Log any activity with full context
     * 
     * @param string $action Action type (create, update, delete, etc)
     * @param string $description Human-readable description
     * @param mixed $model The model instance being logged (optional)
     * @param array $oldValues Old values before change (optional)
     * @param array $newValues New values after change (optional)
     * @param array $metadata Additional context (optional)
     * @return ActivityLog|null
     */
    protected function logActivity(
        string $action,
        string $description,
        $model = null,
        array $oldValues = null,
        array $newValues = null,
        array $metadata = null
    ): ?ActivityLog {
        try {
            $user = Auth::user();

            return ActivityLog::create([
                'user_id' => $user?->id,
                'user_name' => $user?->name ?? 'System',
                'action' => $action,
                'model_type' => $model ? get_class($model) : null,
                'model_id' => $model?->id ?? null,
                'description' => $description,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'metadata' => $metadata,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'url' => Request::fullUrl(),
                'method' => Request::method(),
            ]);
        } catch (\Exception $e) {
            // Jangan sampai logging error mengganggu flow aplikasi
            \Log::error('Failed to log activity: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Log CREATE action
     */
    protected function logCreate($model, string $description = null, array $metadata = null): ?ActivityLog
    {
        $modelName = class_basename($model);
        $description = $description ?? "{$modelName} berhasil dibuat";

        return $this->logActivity(
            action: 'create',
            description: $description,
            model: $model,
            newValues: $this->getRelevantAttributes($model),
            metadata: $metadata
        );
    }

    /**
     * Log UPDATE action dengan diff detection
     */
    protected function logUpdate($model, array $oldAttributes, string $description = null, array $metadata = null): ?ActivityLog
    {
        $modelName = class_basename($model);
        $description = $description ?? "{$modelName} berhasil diupdate";

        // Detect what changed
        $changes = [];
        foreach ($oldAttributes as $key => $oldValue) {
            $newValue = $model->$key ?? null;
            if ($oldValue != $newValue) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $this->logActivity(
            action: 'update',
            description: $description,
            model: $model,
            oldValues: $oldAttributes,
            newValues: $this->getRelevantAttributes($model),
            metadata: array_merge(['changes' => $changes], $metadata ?? [])
        );
    }

    /**
     * Log DELETE action
     */
    protected function logDelete($model, string $description = null, array $metadata = null): ?ActivityLog
    {
        $modelName = class_basename($model);
        $description = $description ?? "{$modelName} berhasil dihapus";

        return $this->logActivity(
            action: 'delete',
            description: $description,
            model: $model,
            oldValues: $this->getRelevantAttributes($model),
            metadata: $metadata
        );
    }

    /**
     * Log EXPORT action
     */
    protected function logExport(string $exportType, array $filters = null): ?ActivityLog
    {
        return $this->logActivity(
            action: 'export',
            description: "Export {$exportType} ke Excel",
            metadata: ['filters' => $filters, 'export_type' => $exportType]
        );
    }

    /**
     * Log LOGIN action
     */
    protected function logLogin(): ?ActivityLog
    {
        return $this->logActivity(
            action: 'login',
            description: Auth::user()->name . " berhasil login"
        );
    }

    /**
     * Log LOGOUT action
     */
    protected function logLogout(): ?ActivityLog
    {
        return $this->logActivity(
            action: 'logout',
            description: Auth::user()->name . " logout"
        );
    }

    /**
     * Get relevant attributes from model (exclude timestamps, etc)
     */
    private function getRelevantAttributes($model): array
    {
        $attributes = $model->getAttributes();

        // Remove sensitive/irrelevant fields
        $exclude = ['password', 'remember_token', 'created_at', 'updated_at', 'deleted_at'];

        return array_diff_key($attributes, array_flip($exclude));
    }
}
