<?php

namespace App\Services;

use Modules\Center\Models\Attendance;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\Schedule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\AttendanceNotificationMail;
use App\Traits\HasLocaleResolution;

class AttendanceService
{
    use HasLocaleResolution;
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
                'tenant_id' => $data['tenant_id'] ?? \Modules\Tenancy\Services\TenantResolver::get()->id,
                'course_id' => $data['course_id'],
                'check_in_time' => now(),
                'status' => $status,
                'late_minutes' => $lateMinutes,
                'late_label' => $lateLabel,
            ]
        );

        // Send WhatsApp Notification if student arrived (even if previously marked as absent)
        $isArriving = in_array($status, ['present', 'late']);
        $wasAlreadyPresent = !$attendance->wasRecentlyCreated && in_array($attendance->getOriginal('status'), ['present', 'late']);

        if ($isArriving && !$wasAlreadyPresent) {
            $student = Student::with('user')->find($data['student_id']);
            $schedule = Schedule::with('course')->find($data['schedule_id']);
            $tenant = \Modules\Tenancy\Services\TenantResolver::get();
            
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

                $attendanceAlertEnabled = !isset($tenant->settings['academic']['attendance_alert']) || $tenant->settings['academic']['attendance_alert'];

                if ($attendanceAlertEnabled) {
                    // Dispatch Sync for immediate reliability (avoids queue worker dependency)
                    \App\Jobs\SendWhatsAppNotification::dispatchSync($tenant, $student, $schedule->course);
                    
                    // Send Email Notification if enabled
                    $this->sendEmailNotification($tenant, $student, $schedule->course, $status);
                }

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
        $lateLevels = \Modules\Tenancy\Services\TenantResolver::get()->settings['academic']['late_levels'] ?? config('academic.late_rules.defaults', []);
        
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
            $tenantDomain = \Modules\Tenancy\Services\TenantResolver::get()->domain;
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
     * Send attendance email notification.
     */
    public function sendEmailNotification($tenant, $student, $course, $status)
    {
        try {
            $tenantSettings = $tenant->settings['email_templates'] ?? [];
            $notifEnabled = !isset($tenantSettings['notif_attendance_enabled']) || $tenantSettings['notif_attendance_enabled'];

            if (!$notifEnabled) return;

            // Determine real email
            $realEmail = null;
            $studentEmail = $student->email ?? ($student->user ? $student->user->email : null);
            if ($studentEmail && !preg_match('/^std\d+\..+@taalimu\.com$/', $studentEmail)) {
                $realEmail = $studentEmail;
            }
            $hasParentEmail = !empty($student->parent_email);

            if ($realEmail || $hasParentEmail) {
                $locale = $this->getTargetLocale($tenant, $student);
                
                $subjectKey = "notif_attendance_subject_{$locale}";
                $bodyKey = "notif_attendance_body_{$locale}";

                $defaultSubjects = [
                    __('services.string_50'),
                    'en' => 'Attendance Notification - {center_name}',
                    'fr' => 'Notification de présence - {center_name}',
                ];

                $defaultBodies = [
                    'ar' => __('services.string_51'),
                    'en' => "Hello {student_name},\n\nWe would like to inform you that your attendance for {course_name} has been recorded.\nStatus: {status}\n\nBest regards,\n{center_name}",
                    'fr' => "Bonjour {student_name},\n\nNous vous informons que votre présence pour {course_name} a été enregistrée.\nStatut: {status}\n\nCordialement,\n{center_name}",
                ];
                
                $subject = $tenantSettings[$subjectKey] ?? $tenantSettings['notif_attendance_subject'] ?? ($defaultSubjects[$locale] ?? $defaultSubjects['en']);
                $body = $tenantSettings[$bodyKey] ?? $tenantSettings['notif_attendance_body'] ?? ($defaultBodies[$locale] ?? $defaultBodies['en']);
                
                $variables = [
                    'student_name' => $student->name,
                    'course_name' => $course->title,
                    'center_name' => $tenant->name,
                    'status' => __('center::students.' . $status, [], $locale),
                    'date' => now()->format('Y-m-d'),
                ];

                if ($realEmail) {
                    Mail::to($realEmail)->queue(new AttendanceNotificationMail(
                        $subject, $body, $variables, $tenant->name, $student->name
                    ));
                }
                
                if ($hasParentEmail) {
                    Mail::to($student->parent_email)->queue(new AttendanceNotificationMail(
                        $subject, $body, $variables, $tenant->name, $student->name
                    ));
                }
            }
        } catch (\Exception $e) {
            Log::error('AttendanceService email notification failed: ' . $e->getMessage());
        }
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


