<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class RoleRepository
{
    protected const CACHE_TTL = 3600; // 1 Hour

    /**
     * Get all roles available for a specific tenant (Global + Local).
     *
     * @param int|null $tenantId
     * @return Collection
     */
    public function getAllForTenant(?int $tenantId): Collection
    {
        if (!$tenantId) {
            return collect([]);
        }

        $cacheKey = "roles_tenant_{$tenantId}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($tenantId) {
            // Fetch Global Roles (NULL tenant_id) AND Local Roles (tenant_id = $tenantId)
            $roles = Role::withoutGlobalScope(\App\Scopes\TenantScope::class)
                ->where(function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId)
                      ->orWhereNull('tenant_id');
            })
            ->where('guard_name', 'web')
            ->orderBy('tenant_id', 'desc') // Local roles first (non-null)
            ->get();

            // Deduplicate by name, keeping the first one found (the tenant one, due to ordering)
            return $roles->unique('name')->map(function ($role) {
                 // Add 'type' attribute for UI logic
                 $role->type = is_null($role->tenant_id) ? 'system' : 'custom';
                 return $role;
            })->values(); // Reset keys for clean array/collection
        });
    }

    /**
     * Find a role ensuring it belongs to the tenant or is global.
     */
    public function findForTenant(int $roleId, int $tenantId): ?Role
    {
        return Role::withoutGlobalScope(\App\Scopes\TenantScope::class)
            ->where('id', $roleId)
            ->where(function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId)
                      ->orWhereNull('tenant_id');
            })->first();
    }

    /**
     * Clear the role cache for a specific tenant.
     * Should be called on Create/Update/Delete.
     */
    public function clearCache(int $tenantId): void
    {
        Cache::forget("roles_tenant_{$tenantId}");
    }
}
