<?php

namespace App\Policies;

use App\Models\User;
use Modules\Center\Models\Branch;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['center_admin', 'admin']) || $user->checkPermissionTo('manage settings');
    }

    public function view(User $user, Branch $branch): bool
    {
        return $user->tenant_id === $branch->tenant_id &&
               ($user->hasRole(['center_admin', 'admin']) || $user->checkPermissionTo('manage settings'));
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['center_admin', 'admin']) || $user->checkPermissionTo('manage settings');
    }

    public function update(User $user, Branch $branch): bool
    {
        return $user->tenant_id === $branch->tenant_id &&
               ($user->hasRole(['center_admin', 'admin']) || $user->checkPermissionTo('manage settings'));
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->tenant_id === $branch->tenant_id &&
               ($user->hasRole(['center_admin', 'admin']) || $user->checkPermissionTo('manage settings'));
    }
}
