<?php

namespace App\Policies;

use App\Models\Tenant;
use App\Models\User;

class TenantPolicy
{
    /**
     * Determine if the user can view any tenants.
     */
    public function viewAny(User $user): bool
    {
        // Only super admins can view the list of all tenants
        return $user->hasRole('super_admin');
    }

    /**
     * Determine if the user can view the tenant.
     */
    public function view(User $user, Tenant $tenant): bool
    {
        // Super admins can view any tenant
        // Regular admins can only view their own tenant
        return $user->hasRole('super_admin') || (int) $user->tenant_id === (int) $tenant->id;
    }

    /**
     * Determine if the user can create a tenant.
     */
    public function create(User $user): bool
    {
        // Only super admins can create new tenants
        return $user->hasRole('super_admin');
    }

    /**
     * Determine if the user can update the tenant.
     */
    public function update(User $user, Tenant $tenant): bool
    {
        // DEBUG LOGGING
        \Illuminate\Support\Facades\Log::info('TenantPolicy@update Check', [
            'user_id' => $user->id,
            'user_tenant' => $user->tenant_id,
            'target_tenant' => $tenant->id,
            'is_super' => $user->hasRole('super_admin'),
            'is_center_admin' => $user->hasRole('center_admin'),
            'has_role_check' => $user->hasRole('center_admin'),
        ]);

        // Super admins can update any tenant
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Center and Instructor admins can update their own tenant
        return $user->hasAnyRole(['center_owner', 'center_admin', 'admin', 'instructor']) && (int) $user->tenant_id === (int) $tenant->id;
    }

    /**
     * Determine if the user can delete the tenant.
     */
    public function delete(User $user, Tenant $tenant): bool
    {
        // Only super admins can delete tenants
        return $user->hasRole('super_admin');
    }

    /**
     * Determine if the user can impersonate a tenant admin.
     */
    public function impersonate(User $user, Tenant $tenant): bool
    {
        // Only super admins can impersonate tenant admins
        // Additional check: tenant must be active
        return $user->hasRole('super_admin') && $tenant->status === 'active';
    }

    /**
     * Determine if the user can update admin notes for a tenant.
     */
    public function updateNotes(User $user, Tenant $tenant): bool
    {
        // Only super admins can update admin notes
        return $user->hasRole('super_admin');
    }

    /**
     * Determine if the user can reset a tenant admin's password.
     * This is a highly sensitive operation.
     */
    public function resetPassword(User $user, Tenant $tenant): bool
    {
        // Only super admins can reset tenant admin passwords
        return $user->hasRole('super_admin');
    }
}
