<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    /**
     * Determine if the user can view the assignment.
     */
    public function view(User $user, Assignment $assignment): bool
    {
        if ($user->hasAnyRole(['center_admin', 'staff'])) {
            return $this->belongsToSameTenant($user, $assignment);
        }

        if ($user->hasRole('instructor')) {
            $course = $assignment->lesson?->section?->course;

            return $course && $user->instructor_id && $course->instructor_id === $user->instructor_id;
        }

        // Student can view if from the same tenant
        // (Enrollment check could be added here if needed, but tenant check is a good baseline)
        return $this->belongsToSameTenant($user, $assignment);
    }

    /**
     * Determine if the user can update the assignment.
     */
    public function update(User $user, Assignment $assignment): bool
    {
        if ($user->hasRole('center_admin')) {
            return $this->belongsToSameTenant($user, $assignment);
        }

        if ($user->hasRole('instructor')) {
            $course = $assignment->lesson->section->course;

            return $course && $user->instructor_id && $course->instructor_id === $user->instructor_id;
        }

        return false;
    }

    /**
     * Determine if index can be viewed.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['center_admin', 'instructor', 'staff', 'secretary']);
    }

    /**
     * Determine if the user can delete the assignment.
     */
    public function delete(User $user, Assignment $assignment): bool
    {
        return $this->belongsToSameTenant($user, $assignment) &&
               $user->hasAnyRole(['center_admin', 'admin']);
    }

    /**
     * Check if assignment belongs to the same tenant as the user.
     */
    private function belongsToSameTenant(User $user, Assignment $assignment): bool
    {
        $lesson = $assignment->lesson;
        if (! $lesson) {
            return false;
        }

        $section = $lesson->section;
        if (! $section) {
            return false;
        }

        $course = $section->course;
        if (! $course) {
            return false;
        }

        return (int) $course->tenant_id === (int) $user->tenant_id;
    }
}
