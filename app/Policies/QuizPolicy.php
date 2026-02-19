<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;

class QuizPolicy
{
    /**
     * Determine if the user can view any quizzes.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['center_admin', 'instructor']);
    }

    public function view(User $user, Quiz $quiz): bool
    {
        if ($user->hasRole('center_admin')) {
            return $this->belongsToSameTenant($user, $quiz);
        }

        if ($user->hasRole('instructor')) {
            $course = $quiz->lesson->section->course;
            return $course && $user->instructor_id && $course->instructor_id === $user->instructor_id;
        }

        return $this->belongsToSameTenant($user, $quiz);
    }

    /**
     * Determine if the user can update the quiz.
     */
    public function update(User $user, Quiz $quiz): bool
    {
        if ($user->hasRole('center_admin')) {
            return $this->belongsToSameTenant($user, $quiz);
        }

        if ($user->hasRole('instructor')) {
            $course = $quiz->lesson->section->course;
            return $course && $user->instructor_id && $course->instructor_id === $user->instructor_id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the quiz.
     */
    public function delete(User $user, Quiz $quiz): bool
    {
        return $this->belongsToSameTenant($user, $quiz) && 
               $user->hasRole('center_admin');
    }

    /**
     * Check if quiz belongs to the same tenant as the user.
     */
    private function belongsToSameTenant(User $user, Quiz $quiz): bool
    {
        // Get the lesson's course tenant_id through relationships
        $lesson = $quiz->lesson;
        if (!$lesson) {
            return false;
        }
        
        $section = $lesson->section;
        if (!$section) {
            return false;
        }
        
        $course = $section->course;
        if (!$course) {
            return false;
        }
        
        return $course->tenant_id === $user->tenant_id;
    }
}
