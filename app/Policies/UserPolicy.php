<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\HasRoleCheck;

class UserPolicy
{
    use HasRoleCheck;

    public function viewAny(User $user): bool
    {
        return $this->hasAnyRole($user, ['center_admin', 'admin', 'super_admin']) || $user->checkPermissionTo('manage users');
    }

    public function view(User $user, User $model): bool
    {
        if ($this->hasAnyRole($user, 'super_admin')) {
            return true;
        }

        return $user->tenant_id === $model->tenant_id && ($this->hasAnyRole($user, ['center_admin', 'admin']) || $user->checkPermissionTo('manage users'));
    }

    public function create(User $user): bool
    {
        return $this->hasAnyRole($user, ['center_admin', 'admin', 'super_admin']) || $user->checkPermissionTo('manage users');
    }

    public function update(User $user, User $model): bool
    {
        if ($this->hasAnyRole($user, 'super_admin')) {
            return true;
        }

        return $user->tenant_id === $model->tenant_id &&
               ($this->hasAnyRole($user, ['center_admin', 'admin']) || $user->checkPermissionTo('manage users'));
    }

    public function delete(User $user, User $model): bool
    {
        if ($this->hasAnyRole($user, 'super_admin')) {
            return true;
        }

        return $user->tenant_id === $model->tenant_id &&
               ($this->hasAnyRole($user, ['center_admin', 'admin']) || $user->checkPermissionTo('manage users'));
    }
}
