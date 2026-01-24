<?php

namespace App\Services;

use App\Models\User;
use App\Models\Enrollment;
use App\Models\LessonProgress;

class StudentProgressService
{
    /**
     * Calculate the student's rank based on points within their tenant.
     */
    public function getStudentRank(User $user): int
    {
        return User::where('tenant_id', $user->tenant_id)
            ->where('role', 'student')
            ->where('points', '>', $user->points)
            ->count() + 1;
    }

    /**
     * Determine the next lesson for a given enrollment based on progress.
     */
    public function getNextLesson(Enrollment $enrollment)
    {
        $lastProgress = LessonProgress::where('enrollment_id', $enrollment->id)
            ->latest('updated_at')
            ->first();

        if (!$lastProgress) {
            // Start from the first lesson
            return $enrollment->course->sections->first()?->lessons?->first();
        }

        // Find next lesson after the last completed one
        // Note: This assumes lessons are loaded or need to be loaded. 
        // Optimized to not load everything if possible, but flatMap approach requires loading sections.
        // For standard course sizes this is acceptable.
        
        $allLessons = $enrollment->course->sections->flatMap->lessons;
        
        $currentIndex = $allLessons->search(function($item) use ($lastProgress) {
            return $item->id == $lastProgress->lesson_id;
        });

        if ($currentIndex !== false && $currentIndex < $allLessons->count() - 1) {
            return $allLessons[$currentIndex + 1];
        }
        
        return $lastProgress->lesson;
    }
}
