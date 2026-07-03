<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;

class CoursePlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show(\App\Models\Course $course, ?\App\Models\Lesson $lesson = null)
    {
        $user = auth()->user();

        // Ensure enrollment exists (Middleware should handle this, but double check)
        $enrollment = \App\Models\Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        // Authorization: verify user can view this course
        $this->authorize('view', $course);

        $course->load(['sections.lessons' => function ($query) {
            $query->orderBy('sort_order');
        }]);

        // If no lesson specified, redirect to the first lesson
        if (! $lesson) {
            $firstLesson = $course->sections->first()->lessons->first();
            if ($firstLesson) {
                return redirect()->route('center.courses.player', ['course' => $course->id, 'lesson' => $firstLesson->id]);
            }

            return redirect()->route('center.courses.show', $course)->with('error', __('center::messages.msg_032'));
        }

        // Check if lesson belongs to course
        if ($lesson->section->course_id !== $course->id) {
            abort(404);
        }

        // Get completed lessons IDs
        $completedLessonIds = $enrollment->lessonProgress()->pluck('lesson_id')->toArray();

        return view('center::courses.player', compact('course', 'lesson', 'completedLessonIds', 'enrollment'));
    }

    public function markAsComplete(\App\Models\Course $course, \App\Models\Lesson $lesson)
    {
        $user = auth()->user();

        // Authorization: verify user can view this course
        $this->authorize('view', $course);

        $enrollment = \App\Models\Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        // Record progress
        $enrollment->lessonProgress()->firstOrCreate([
            'lesson_id' => $lesson->id,
        ], [
            'completed_at' => now(),
        ]);

        // Update overall progress
        $totalLessons = \App\Models\Lesson::whereHas('section', function ($q) use ($course) {
            $q->where('course_id', $course->id);
        })->count();

        $completedCount = $enrollment->lessonProgress()->count();

        if ($totalLessons > 0) {
            $enrollment->update([
                'progress' => round(($completedCount / $totalLessons) * 100),
            ]);
        }

        return back()->with('success', __('center::messages.msg_033'));
    }

    // Unused resource methods removed
}
