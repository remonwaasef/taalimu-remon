<?php

namespace App\Services\Student;

use App\Models\User;
use App\Models\Student;
use App\Models\Guardian;
use App\DTOs\StudentData;
use Illuminate\Support\Facades\DB;

class StudentProfileService
{
    protected $notificationService;

    public function __construct(StudentNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function updateStudent(Student $student, StudentData $data, User $modifier)
    {
        return DB::transaction(function () use ($student, $data, $modifier) {
            $user = $student->user;
            $user->update([
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone,
            ]);

            $guardianId = $student->guardian_id;
            if ($data->parent_phone) {
                $guardian = Guardian::updateOrCreate(
                    ['tenant_id' => \Modules\Tenancy\app\Services\TenantResolver::get()->id, 'phone' => $data->parent_phone],
                    [
                        'name' => $data->parent_name ?? 'N/A',
                        'job' => $data->parent_job,
                        'address' => $data->address,
                    ]
                );
                $guardianId = $guardian->id;
            }

            $student->update([
                'grade_id' => $data->grade_id,
                'guardian_id' => $guardianId,
                'grade_level' => $data->grade_level,
                'code' => $data->code ?? $student->code,
                'national_id' => $data->national_id ?? $student->national_id,
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone,
                'parent_phone' => $data->parent_phone,
                'parent_email' => $data->parent_email,
                'birth_date' => $data->birth_date,
                'gender' => $data->gender,
                'address' => $data->address,
                'parent_name' => $data->parent_name,
                'parent_job' => $data->parent_job,
                'parent_relation' => $data->parent_relation,
                'emergency_phone' => $data->emergency_phone,
                'school_name' => $data->school_name,
                'section_type' => $data->section_type,
                'profile_photo' => $data->profile_photo ?? $student->profile_photo,
            ]);

            $this->notificationService->notifyAdminsAboutUpdate($student, $modifier);

            return $student;
        });
    }

    public function getExportData()
    {
        return Student::where('tenant_id', \Modules\Tenancy\app\Services\TenantResolver::get()->id)
            ->with(['grade.stage'])
            ->lazy(1000)
            ->map(function ($student) {
                return [
                    $student->id,
                    $student->name,
                    $student->email,
                    " " . $student->phone,
                    $student->grade_level_name,
                    $student->school_name ?? '---',
                    $student->section_type ?? '---',
                    $student->status,
                ];
            });
    }

    public function getProfileData(Student $student)
    {
        $tenantId = \Modules\Tenancy\app\Services\TenantResolver::get()->id;
        $userId = $student->user_id;

        $attendanceLogs = \Modules\Center\Models\Attendance::where('student_id', $student->id)
            ->with(['course', 'schedule'])
            ->latest('session_date')
            ->limit(100) // Fixed N+1 Memory Issue
            ->get();
            
        $attendanceStats = \Modules\Center\Models\Attendance::where('student_id', $student->id)
            ->selectRaw("
                COUNT(CASE WHEN status IN ('present', 'late') THEN 1 END) as present_count,
                COUNT(CASE WHEN status = 'absent' THEN 1 END) as absent_count,
                COUNT(*) as total
            ")->first();

        $presentCount = $attendanceStats->present_count ?? 0;
        $absentCount = $attendanceStats->absent_count ?? 0;
        $totalAttendanceRecords = $attendanceStats->total ?? 0;
        
        $totalCourseSessions = (int) $student->enrollments()
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->sum('courses.sessions_count'); // Fixed N+1

        $attendancePercentage = $totalAttendanceRecords > 0 ? round(($presentCount / $totalAttendanceRecords) * 100) : 0;

        $quizAttempts = \App\Models\QuizAttempt::where('user_id', $userId)
            ->with('quiz')
            ->latest()
            ->limit(50)
            ->get();
            
        $quizStats = \App\Models\QuizAttempt::where('user_id', $userId)
            ->selectRaw("AVG(score) as avg_score, MAX(score) as max_score, COUNT(*) as total")
            ->first();

        $avgQuizScore = $quizStats->total > 0 ? round($quizStats->avg_score) : 0;
        $highestScore = $quizStats->total > 0 ? $quizStats->max_score : 0;

        $pointsStats = \App\Models\PointLog::where('user_id', $userId)
            ->selectRaw("
                SUM(points) as balance,
                SUM(CASE WHEN points > 0 THEN points ELSE 0 END) as earned,
                SUM(CASE WHEN points < 0 THEN ABS(points) ELSE 0 END) as spent
            ")->first();

        $pointBalance = $pointsStats->balance ?? 0;
        $pointsEarned = $pointsStats->earned ?? 0;
        $pointsSpent = $pointsStats->spent ?? 0;

        $assignments = \App\Models\AssignmentSubmission::where('user_id', $userId)
            ->with('assignment.course')
            ->latest()
            ->limit(20)
            ->get();

        $data = [
            'recent_activity' => $student->activities()->with('causer')->latest()->take(10)->get(),
            'enrollments' => $student->enrollments()->with('course')->get(),
            'sales' => $student->sales()->latest()->get(),
            'bookings' => $student->bookings()->with(['schedule.course', 'schedule.classroom'])->get(),
            'availableSchedules' => \App\Models\Schedule::where('tenant_id', $tenantId)
                ->with(['course', 'classroom', 'instructor'])
                ->get(),
            
            'stats' => [
                'attendance_pct' => $attendancePercentage,
                'attendance_count' => $presentCount,
                'absent_count' => $absentCount,
                'total_sessions' => $totalCourseSessions ?: $totalAttendanceRecords,
                'avg_quiz_score' => $avgQuizScore,
                'highest_score' => $highestScore,
                'quiz_count' => $quizStats->total ?? 0,
                'points' => $pointBalance,
                'points_earned' => $pointsEarned,
                'points_spent' => $pointsSpent,
                'remaining_sessions_count' => (int) $student->enrollments()->sum('remaining_sessions'),
            ],
            
            'attendance_logs' => $attendanceLogs,
            'quiz_attempts' => $quizAttempts,
            'assignments' => $assignments,
            'point_logs' => \App\Models\PointLog::where('user_id', $userId)->latest()->limit(50)->get(),
            'payments' => \App\Models\Payment::whereHas('sale', function($q) use ($student) {
                $q->where('student_id', $student->id);
            })->with('receiver')->latest()->limit(20)->get(),
        ];

        if ($student->guardian_id) {
            $data['siblings'] = Student::where('guardian_id', $student->guardian_id)
                ->where('id', '!=', $student->id)
                ->with('grade')
                ->get();
        } else {
            $data['siblings'] = collect();
        }

        return $data;
    }

    public function deleteStudent(Student $student, User $deleter)
    {
        return DB::transaction(function () use ($student, $deleter) {
            $studentName = $student->name;
            if ($student->user) {
                $student->user->delete();
            }
            $result = $student->delete();
            $this->notificationService->notifyAdminsAboutDeletion($studentName, $deleter);
            return $result;
        });
    }
}
