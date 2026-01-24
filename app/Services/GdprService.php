<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class GdprService
{
    /**
     * Export all student data to a structured array.
     */
    public function exportStudentData(User $user)
    {
        $student = $user->student;

        // 1. Basic Profile
        $data = [
            'profile' => [
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone,
                'joined_at' => $student->created_at->toIso8601String(),
            ],
            'academic_records' => [],
            'financial_records' => [],
            'activity_logs' => [],
        ];

        // 2. Quiz Attempts
        $data['academic_records']['quizzes'] = $user->quizAttempts()
            ->with('quiz:id,title')
            ->get()
            ->map(function($attempt) {
                return [
                    'quiz_title' => $attempt->quiz->title ?? 'Unknown Quiz',
                    'score' => $attempt->score,
                    'passed' => (bool) $attempt->passed,
                    'date' => $attempt->created_at->toIso8601String(),
                ];
            });

        // 3. Enrollments
        if ($student) {
            $data['academic_records']['courses'] = $student->enrollments()
                ->with('course:id,title')
                ->get()
                ->map(function($enrollment) {
                    return [
                        'course_title' => $enrollment->course->title ?? 'Unknown Course',
                        'progress' => $enrollment->progress_percent . '%',
                        'enrolled_at' => $enrollment->created_at->toIso8601String(),
                    ];
                });
        }

        // 4. Activity Logs
        $data['activity_logs'] = Activity::where('causer_id', $user->id)
            ->where('causer_type', get_class($user))
            ->latest()
            ->get()
            ->map(function($activity) {
                return [
                    'description' => $activity->description,
                    'date' => $activity->created_at->toIso8601String(),
                ];
            });

        return $data;
    }

    /**
     * Permanently delete student account and anonymize logs.
     */
    public function deleteStudentAccount(User $user)
    {
        return DB::transaction(function () use ($user) {
            $student = $user->student;

            // 1. Anonymize Activity Logs (Don't delete, just remove PII)
            Activity::where('causer_id', $user->id)
                ->where('causer_type', get_class($user))
                ->update([
                    'causer_id' => null,
                    'properties' => array_merge(['original_user_role' => $user->role], ['deleted_at' => now()->toIso8601String()]),
                    'description' => 'Activity by deleted user'
                ]);

            // 2. Delete Student Record & Related Data
            // Cascading deletes usually handle this, but let's be explicit for safety
            if ($student) {
                $student->enrollments()->delete();
                $student->delete();
            }

            // 3. Delete related Quiz Attempts
            $user->quizAttempts()->delete();

            // 4. Delete User Account
            $user->delete();

            return true;
        });
    }
}
