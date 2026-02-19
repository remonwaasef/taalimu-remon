<?php

namespace App\Policies;

use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['center_admin', 'admin']);
    }

    public function view(User $user, Role $role): bool
    {
        return is_null($role->tenant_id) || (int)$user->tenant_id === (int)$role->tenant_id;
    }

    public function create(User $user): bool
    {
        // Only center_admin can create new roles for their tenant
        return $user->hasAnyRole(['center_admin', 'admin']);
    }

    public function update(User $user, Role $role): bool
    {
        // Cannot edit global roles, and must belong to same tenant
        return !is_null($role->tenant_id) && 
               $user->tenant_id === $role->tenant_id && 
               $user->hasAnyRole(['center_admin', 'admin']);
    }

    public function delete(User $user, Role $role): bool
    {
        // Cannot delete global roles, and must belong to same tenant
        return !is_null($role->tenant_id) && 
               $user->tenant_id === $role->tenant_id && 
               $user->hasAnyRole(['center_admin', 'admin']);
    }
}
