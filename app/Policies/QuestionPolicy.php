<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;

class QuestionPolicy
{
    use \App\Traits\HasRoleCheck;

    /**
     * Determine if the user can view any questions.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasAnyRole($user, ['center_admin', 'admin', 'center_owner', 'instructor']);
    }

    /**
     * Determine if the user can view the question.
     */
    public function view(User $user, Question $question): bool
    {
        if ($this->hasAnyRole($user, ['center_admin', 'admin', 'center_owner'])) {
            return $question->tenant_id === $user->tenant_id;
        }

        if ($user->hasRole('instructor')) {
            // If the question belongs to a quiz, check if the instructor teaches the course
            if ($question->quiz) {
                $course = $question->quiz->lesson->section->course;

                return $course && $user->instructor_id && $course->instructor_id === $user->instructor_id;
            }

            return $question->tenant_id === $user->tenant_id;
        }

        return $question->tenant_id === $user->tenant_id;
    }

    /**
     * Determine if the user can create questions.
     */
    public function create(User $user): bool
    {
        return $this->hasAnyRole($user, ['center_admin', 'admin', 'center_owner', 'instructor']);
    }

    /**
     * Determine if the user can update the question.
     */
    public function update(User $user, Question $question): bool
    {
        if ($this->hasAnyRole($user, ['center_admin', 'admin', 'center_owner'])) {
            return $question->tenant_id === $user->tenant_id;
        }

        if ($user->hasRole('instructor')) {
            if ($question->quiz) {
                $course = $question->quiz->lesson->section->course;

                return $course && $user->instructor_id && $course->instructor_id === $user->instructor_id;
            }

            return $question->tenant_id === $user->tenant_id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the question.
     */
    public function delete(User $user, Question $question): bool
    {
        return $question->tenant_id === $user->tenant_id &&
               $this->hasAnyRole($user, ['center_admin', 'admin', 'center_owner']);
    }
}
