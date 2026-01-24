<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

use App\Traits\HasRoleCheck;

class StudentPolicy
{
    use HasRoleCheck;

    /**
     * Determine if the user can view any students.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasAnyRole($user, ['center_admin', 'instructor', 'secretary']);
    }

    /**
     * Determine if the user can view the student.
     */
    public function view(User $user, Student $student): bool
    {
        // Allow if tenant matches AND user has appropriate role
        // Also allow the student themselves (if they had a user account)
        return $student->tenant_id === $user->tenant_id && 
               ($this->hasAnyRole($user, ['center_admin', 'instructor', 'secretary']) || $user->id === $student->user_id);
    }

    /**
     * Determine if the user can create a student.
     */
    public function create(User $user): bool
    {
        return $this->hasAnyRole($user, ['center_admin', 'secretary']);
    }

    /**
     * Determine if the user can update the student.
     */
    public function update(User $user, Student $student): bool
    {
        return $student->tenant_id === $user->tenant_id && 
               $this->hasAnyRole($user, ['center_admin', 'secretary']);
    }

    /**
     * Determine if the user can delete the student.
     */
    public function delete(User $user, Student $student): bool
    {
        return $student->tenant_id === $user->tenant_id && 
               $this->hasAnyRole($user, 'center_admin');
    }
}
