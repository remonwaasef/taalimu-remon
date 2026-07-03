<?php

namespace App\Policies;

use App\Models\Instructor;
use App\Models\User;

use App\Traits\HasRoleCheck;

class InstructorPolicy
{
    use HasRoleCheck;

    /**
     * Determine if the user can view any instructors.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasAnyRole($user, ['center_admin', 'admin']) || $user->checkPermissionTo('view instructors');
    }

    /**
     * Determine if the user can view the instructor.
     */
    public function view(User $user, Instructor $instructor): bool
    {
        return $user->tenant_id === $instructor->tenant_id &&
               ($this->hasAnyRole($user, ['center_admin', 'admin', 'secretary']) || $user->checkPermissionTo('view instructors'));
    }

    /**
     * Determine if the user can create instructors.
     */
    public function create(User $user): bool
    {
        return $this->hasAnyRole($user, ['center_admin', 'admin']) || $user->checkPermissionTo('create instructors');
    }

    /**
     * Determine if the user can update the instructor.
     */
    public function update(User $user, Instructor $instructor): bool
    {
        return $user->tenant_id === $instructor->tenant_id && 
               ($this->hasAnyRole($user, ['center_admin', 'admin']) || $user->checkPermissionTo('edit instructors'));
    }

    /**
     * Determine if the user can delete the instructor.
     */
    public function delete(User $user, Instructor $instructor): bool
    {
        return $user->tenant_id === $instructor->tenant_id && 
               ($this->hasAnyRole($user, ['center_admin', 'admin']) || $user->checkPermissionTo('delete instructors'));
    }
}
