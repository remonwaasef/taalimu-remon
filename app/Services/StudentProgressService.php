<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class StudentProgressService
{
    /**
     * Calculate the student's rank based on points within their tenant.
     */
    public function getStudentRank(User $user): int
    {
        return Cache::remember("student_rank_{$user->tenant_id}_{$user->id}", 300, function () use ($user) {
            return User::where('tenant_id', $user->tenant_id)
                ->where('role', 'student')
                ->where('points', '>', $user->points)
                ->count() + 1;
        });
    }

    /**
     * Determine the next lesson for a given enrollment based on progress.
     */
    public function getNextLesson(Enrollment $enrollment)
    {
        $enrollment->loadMissing('course.sections.lessons');
        $allLessons = $enrollment->course->sections->flatMap->lessons;
        $courseLessonIds = $allLessons->pluck('id');

        $lastProgress = LessonProgress::where('user_id', $enrollment->user_id)
            ->whereIn('lesson_id', $courseLessonIds)
            ->latest('updated_at')
            ->first();

        if (! $lastProgress) {
            // Start from the first lesson
            return $allLessons->first();
        }

        // Find next lesson after the last completed one
        $currentIndex = $allLessons->search(function ($item) use ($lastProgress) {
            return $item->id == $lastProgress->lesson_id;
        });

        if ($currentIndex !== false && $currentIndex < $allLessons->count() - 1) {
            return $allLessons[$currentIndex + 1];
        }

        return $lastProgress->lesson;
    }
}
