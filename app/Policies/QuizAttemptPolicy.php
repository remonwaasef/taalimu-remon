<?php

namespace App\Policies;

use App\Models\User;
use App\Models\QuizAttempt;

class QuizAttemptPolicy
{
    /**
     * Determine if the user can view the quiz attempt.
     * Users can view their own attempts or instructors/admins from same tenant can view all.
     */
    public function view(User $user, QuizAttempt $attempt): bool
    {
        // User can view their own attempt
        if ($attempt->user_id === $user->id) {
            return true;
        }

        // Admins and instructors from the same tenant can view attempts
        $quiz = $attempt->quiz;
        $course = $quiz->lesson->section->course;
        
        return $course->tenant_id === $user->tenant_id && 
               $user->hasAnyRole(['center_admin', 'admin', 'instructor']);
    }

    /**
     * Determine if the user can create a quiz attempt (take the quiz).
     */
    public function create(User $user, \App\Models\Quiz $quiz): bool
    {
        // Students can take quizzes if enrolled in the course
        $course = $quiz->lesson->section->course;
        
        // Check enrollment
        $enrolled = \App\Models\Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->exists();
            
        return $enrolled && $course->tenant_id === $user->tenant_id;
    }
}
