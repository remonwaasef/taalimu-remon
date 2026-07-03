<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\User;
use App\Traits\HasRoleCheck;

class ClassroomPolicy
{
    use HasRoleCheck;

    public function viewAny(User $user): bool
    {
        return $this->hasAnyRole($user, ['center_admin', 'admin', 'secretary']) || $user->checkPermissionTo('manage settings');
    }

    public function view(User $user, Classroom $classroom): bool
    {
        return $user->tenant_id == $classroom->tenant_id &&
               ($this->hasAnyRole($user, ['center_admin', 'admin', 'secretary']) || $user->checkPermissionTo('manage settings'));
    }

    public function create(User $user): bool
    {
        return $this->hasAnyRole($user, ['center_admin', 'admin', 'secretary']) || $user->checkPermissionTo('manage settings');
    }

    public function update(User $user, Classroom $classroom): bool
    {
        return $user->tenant_id == $classroom->tenant_id &&
               ($this->hasAnyRole($user, ['center_admin', 'admin', 'secretary']) || $user->checkPermissionTo('manage settings'));
    }

    public function delete(User $user, Classroom $classroom): bool
    {
        return $user->tenant_id == $classroom->tenant_id &&
               ($this->hasAnyRole($user, ['center_admin', 'admin']) || $user->checkPermissionTo('manage settings'));
    }
}
