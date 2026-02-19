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
        $status = $data['status'] ?? 'present';
        $lateMinutes = $data['late_minutes'] ?? 0;
        $lateLabel = $data['late_label'] ?? null;

        // If status is present but no late data provided, check if it should be late
        if ($status === 'present' && !isset($data['late_minutes']) && isset($data['schedule_id'])) {
            $schedule = Schedule::find($data['schedule_id']);
            if ($schedule) {
                $lateData = $this->determineStatus($schedule);
                $status = $lateData['status'];
                $lateMinutes = $lateData['late_minutes'];
                $lateLabel = $lateData['late_label'];
            }
        }

        $attendance = Attendance::updateOrCreate(
            [
                'student_id' => $data['student_id'],
                'schedule_id' => $data['schedule_id'],
                'session_date' => $data['session_date'] ?? today(),
            ],
            [
                'tenant_id' => $data['tenant_id'] ?? app('tenant')->id,
                'course_id' => $data['course_id'],
                'check_in_time' => now(),
                'status' => $status,
                'late_minutes' => $lateMinutes,
                'late_label' => $lateLabel,
            ]
        );

        // Send WhatsApp Notification if student arrived
        if ($attendance->wasRecentlyCreated && in_array($status, ['present', 'late'])) {
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
                
                // Award points for attendance (maybe reduction for late?)
                if ($student->user) {
                    $points = $status === 'late' ? 5 : 10;
                    \App\Jobs\AwardGamificationPoints::dispatch(
                        $student->user, 
                        $points, 
                        "Attended session: " . $schedule->course->title . ($status === 'late' ? " (Late)" : ""), 
                        $attendance
                    )->onQueue('gamification');
                }
            }
        }

        return $attendance;
    }

    /**
     * Determine attendance status based on schedule and current time.
     */
    public function determineStatus(Schedule $schedule): array
    {
        $status = 'present';
        $lateMinutes = 0;
        $lateLabel = null;

        $startTime = Carbon::parse($schedule->start_time);
        $now = now();

        if ($now->isAfter($startTime)) {
            $lateMinutes = $now->diffInMinutes($startTime);
            $lateLevels = $this->getLateLevels();

            foreach ($lateLevels as $level) {
                if ($lateMinutes >= $level['minutes']) {
                    $status = 'late';
                    $lateLabel = $level['label'];
                    break;
                }
            }
        }

        return [
            'status' => $status,
            'late_minutes' => $lateMinutes,
            'late_label' => $lateLabel,
        ];
    }

    /**
     * Get sorted late levels from tenant settings.
     */
    public function getLateLevels(): array
    {
        $lateLevels = app('tenant')->settings['academic']['late_levels'] ?? [];
        
        usort($lateLevels, function($a, $b) {
            return $b['minutes'] <=> $a['minutes'];
        });

        return $lateLevels;
    }

    /**
     * Generate a signed QR URL for attendance.

    /**
     * Generate a signed QR URL for attendance.
     */
    public function generateQrUrl(int $scheduleId, ?string $tenantDomain = null)
    {
        if (!$tenantDomain && app()->bound('tenant')) {
            $tenantDomain = app('tenant')->domain;
        }

        return URL::temporarySignedRoute(
            'center.attendance.markByQr',
            now()->addMinutes(5),
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
