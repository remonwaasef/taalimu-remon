<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

use App\Traits\HasRoleCheck;

class CoursePolicy
{
    use HasRoleCheck;

    /**
     * Determine if the user can view any courses.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasAnyRole($user, ['center_admin', 'instructor']);
    }

    /**
     * Determine if the user can view the course.
     */
    public function view(User $user, Course $course): bool
    {
        return $course->tenant_id === $user->tenant_id;
    }

    /**
     * Determine if the user can create a course.
     */
    public function create(User $user): bool
    {
        return $this->hasAnyRole($user, ['center_admin', 'instructor']);
    }

    public function update(User $user, Course $course): bool
    {
        if ($this->hasAnyRole($user, ['center_admin'])) {
            return $course->tenant_id === $user->tenant_id;
        }

        if ($this->hasAnyRole($user, ['instructor'])) {
            // Fixed: Use instructor_id directly to avoid N+1 query
            return $user->instructor_id && 
                   $course->instructor_id === $user->instructor_id &&
                   $course->tenant_id === $user->tenant_id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the course.
     */
    public function delete(User $user, Course $course): bool
    {
        return $course->tenant_id === $user->tenant_id && 
               $this->hasAnyRole($user, ['center_admin']);
    }

    /**
     * Determine if the user can enroll students in the course.
     */
    public function enroll(User $user, Course $course): bool
    {
        // Only admins and center_admins can enroll students
        // Must be from the same tenant
        return $course->tenant_id === $user->tenant_id && 
               $this->hasAnyRole($user, ['center_admin', 'admin', 'secretary']);
    }
}
