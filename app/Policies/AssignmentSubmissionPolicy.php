<?php

namespace App\Policies;

use App\Models\AssignmentSubmission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignmentSubmissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can download the submission.
     */
    public function download(User $user, AssignmentSubmission $submission): bool
    {
        // 1. Owner can download
        if ((int)$user->id === (int)$submission->user_id) {
            return true;
        }

        // 2. Instructor of the course can download
        $course = $submission->assignment?->lesson?->section?->course;
        if ($user->hasRole('instructor') && $course && $user->instructor_id && (int)$course->instructor_id === (int)$user->instructor_id) {
            return true;
        }

        // 3. Admin of the tenant can download
        if ($user->hasAnyRole(['center_admin', 'admin'])) {
            $assignment = $submission->assignment;
            if (!$assignment) return false;
            
            $lesson = $assignment->lesson;
            if (!$lesson) return false;
            
            $section = $lesson->section;
            if (!$section) return false;
            
            $course = $section->course;
            if (!$course) return false;

            return (int)$course->tenant_id === (int)$user->tenant_id;
        }

        return false;
    }
}
