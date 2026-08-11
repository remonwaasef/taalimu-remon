<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    /**
     * Permission groups reserved for the super-admin portal only.
     * They are hidden from tenants and rejected by RoleController::safePermissions().
     */
    public const SYSTEM_GROUPS = [
        'centers',
        'tenants',
    ];

    /**
     * Get all permissions grouped by category.
     * Category is derived from the second word of the permission name (e.g., "view users" -> "users").
     *
     * @return \Illuminate\Support\Collection<string, Collection<int, Permission>>
     */
    public function getGroupedPermissions(int $cacheSeconds = 3600): Collection
    {
        return Cache::remember('grouped_permissions', $cacheSeconds, function () {
            return $this->groupPermissions(Permission::select('id', 'name', 'guard_name')->get());
        });
    }

    /**
     * Get permissions visible to a tenant (center scope only).
     * System-wide groups (e.g. "centers") are excluded so the tenant matrix
     * only ever shows assignable permissions — nothing is silently dropped on save.
     *
     * @return \Illuminate\Support\Collection<string, Collection<int, Permission>>
     */
    public function getTenantGroupedPermissions(int $cacheSeconds = 3600): Collection
    {
        return Cache::remember('grouped_permissions_tenant', $cacheSeconds, function () {
            return $this->groupPermissions(
                Permission::select('id', 'name', 'guard_name')
                    ->get()
                    ->reject(fn (Permission $permission) => $this->isSystemPermission($permission->name))
            );
        });
    }

    protected function isSystemPermission(string $name): bool
    {
        $parts = explode(' ', $name);

        return in_array($parts[1] ?? '', self::SYSTEM_GROUPS, true);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Permission>  $permissions
     * @return \Illuminate\Support\Collection<string, Collection<int, Permission>>
     */
    protected function groupPermissions($permissions): Collection
    {
        return $permissions->groupBy(function ($item) {
            $parts = explode(' ', $item->name);

            return $parts[1] ?? 'General';
        });
    }
}
