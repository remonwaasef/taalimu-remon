<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    /**
     * Get all permissions grouped by category.
     * Category is derived from the second word of the permission name (e.g., "view users" -> "users").
     *
     * @return \Illuminate\Support\Collection
     */
    public function getGroupedPermissions(int $cacheSeconds = 3600)
    {
        return Cache::remember('grouped_permissions', $cacheSeconds, function () {
            return Permission::select('id', 'name', 'guard_name')->get()->groupBy(function ($item) {
                // Example: "manage users" -> "users", "view reports" -> "reports"
                $parts = explode(' ', $item->name);

                return $parts[1] ?? 'General';
            });
        });
    }
}
