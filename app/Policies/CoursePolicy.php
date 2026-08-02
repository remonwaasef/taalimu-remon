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
        return $user->role === 'super_admin' || $this->hasAnyRole($user, ['center_admin', 'admin', 'center_owner', 'instructor', 'secretary', 'staff']) || $user->checkPermissionTo('view courses');
    }

    /**
     * Determine if the user can view the course.
     */
    public function view(User $user, Course $course): bool
    {
        return $course->tenant_id === $user->tenant_id &&
               ($user->role === 'super_admin' || $this->hasAnyRole($user, ['center_admin', 'admin', 'center_owner', 'instructor', 'secretary', 'staff']) || $user->checkPermissionTo('view courses'));
    }

    /**
     * Determine if the user can create a course.
     */
    public function create(User $user): bool
    {
        return $user->role === 'super_admin' || $this->hasAnyRole($user, ['center_admin', 'admin', 'center_owner', 'instructor', 'secretary', 'staff']) || $user->checkPermissionTo('create courses');
    }

    public function update(User $user, Course $course): bool
    {
        if ($user->role === 'super_admin' || $this->hasAnyRole($user, ['center_admin', 'admin', 'center_owner']) || $user->checkPermissionTo('edit courses')) {
            return $course->tenant_id === $user->tenant_id;
        }

        if ($this->hasAnyRole($user, ['instructor'])) {
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
               ($user->role === 'super_admin' || $this->hasAnyRole($user, ['center_admin', 'admin', 'center_owner']) || $user->checkPermissionTo('delete courses'));
    }

    /**
     * Determine if the user can enroll students in the course.
     */
    public function enroll(User $user, Course $course): bool
    {
        return $course->tenant_id === $user->tenant_id &&
               ($user->role === 'super_admin' || $this->hasAnyRole($user, ['center_admin', 'admin', 'center_owner', 'secretary']) || $user->checkPermissionTo('manage students'));
    }
}
