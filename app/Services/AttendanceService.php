<?php

namespace App\Services;

use Modules\Center\Models\Attendance;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\Schedule;

class AttendanceService
{
    protected $whatsappService;
    protected $gamificationService;

    public function __construct(WhatsAppService $whatsappService, GamificationService $gamificationService)
    {
        $this->whatsappService = $whatsappService;
        $this->gamificationService = $gamificationService;
    }

    /**
     * Mark attendance for a student.
     */
    public function markAttendance(array $data)
    {
        $attendance = Attendance::updateOrCreate(
            [
                'student_id' => $data['student_id'],
                'schedule_id' => $data['schedule_id'],
                'session_date' => $data['session_date'],
            ],
            [
                'tenant_id' => $data['tenant_id'],
                'course_id' => $data['course_id'],
                'check_in_time' => now(),
                'status' => $data['status'],
            ]
        );

        // Send WhatsApp Notification if student arrived
        if ($attendance->wasRecentlyCreated && $data['status'] === 'present') {
            $student = Student::with('user')->find($data['student_id']);
            $schedule = Schedule::with('course')->find($data['schedule_id']);
            $tenant = app('tenant');
            
            if ($student && $schedule) {
                // Deduct session from balance
                $enrollment = \App\Models\Enrollment::where('user_id', $student->user_id)
                    ->where('course_id', $data['course_id'])
                    ->first();
                
                if ($enrollment) {
                    if ($enrollment->remaining_sessions > 0) {
                        $enrollment->decrement('remaining_sessions');
                    }
                    
                    // Update Progress based on sessions
                    $totalSessions = $schedule->course->sessions_count ?? 0;
                    if ($totalSessions > 0) {
                        $consumed = $totalSessions - $enrollment->remaining_sessions;
                        $progress = min(100, round(($consumed / $totalSessions) * 100));
                        $enrollment->update(['progress' => $progress]);
                    }
                }

                // Dispatch Jobs for better performance
                \App\Jobs\SendWhatsAppNotification::dispatch($tenant, $student, $schedule->course)
                    ->onQueue('notifications');
                
                // Award points for attendance
                if ($student->user) {
                    \App\Jobs\AwardGamificationPoints::dispatch(
                        $student->user, 
                        10, 
                        "Attended session: " . $schedule->course->title, 
                        $attendance
                    )->onQueue('gamification');
                }
            }
        }

        return $attendance;
    }

    /**
     * Generate a signed QR URL for attendance.
     */
    public function generateQrUrl(int $scheduleId, string $tenantDomain)
    {
        return URL::temporarySignedRoute(
            'center.attendance.markByQr',
            now()->addSeconds(60),
            [
                'tenant' => $tenantDomain,
                'schedule' => $scheduleId,
            ]
        );
    }

    /**
     * Check if student already attended today.
     */
    public function hasAttendedToday(int $studentId, int $scheduleId)
    {
        return Attendance::where('student_id', $studentId)
            ->where('schedule_id', $scheduleId)
            ->whereDate('session_date', today())
            ->exists();
    }
}
