<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TenantModelObserver
{
    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model): void
    {
        $this->updateCache($model, 1);
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $this->updateCache($model, -1);
    }

    /**
     * Update the cached counter.
     */
    protected function updateCache(Model $model, int $change): void
    {
        // Ensure the model has a tenant_id
        if (! isset($model->tenant_id)) {
            return;
        }

        $tenantId = $model->tenant_id;
        $featureCode = $this->getFeatureCodeEx(get_class($model), $model);

        if ($featureCode) {
            $cacheKey = "tenant_{$tenantId}_usage_{$featureCode}";

            // Only update if the key exists (meaning it's been cached)
            // If it doesn't exist, the next getUsage() call will calculate and cache it correctly.
            if (Cache::has($cacheKey)) {
                Cache::increment($cacheKey, $change);
            }
        }
    }

    /**
     * Map model class to feature code.
     */
    protected function getFeatureCodeEx(string $className, Model $model): ?string
    {
        // Normalize class name (remove leading backslash)
        $className = ltrim($className, '\\');

        return match ($className) {
            'App\Models\User' => $model->role === 'student' ? 'max_students' : null,
            'App\Models\Instructor' => 'max_instructors',
            'App\Models\Course' => 'max_courses',
            'App\Models\Classroom' => 'max_classrooms',
            'Modules\Center\Models\Branch' => 'max_branches',
            default => null,
        };
    }
}
