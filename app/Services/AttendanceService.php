<?php

namespace App\Services;

use App\Mail\AttendanceNotificationMail;
use App\Models\Enrollment;
use App\Models\Schedule;
use App\Models\Student;
use App\Traits\HasLocaleResolution;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Modules\Center\Models\Attendance;

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
     * Uses database transaction with row locking to prevent race conditions
     * on session deduction and duplicate attendance creation.
     */
    public function markAttendance(array $data)
    {
        $status = $data['status'] ?? 'present';
        $lateMinutes = $data['late_minutes'] ?? 0;
        $lateLabel = $data['late_label'] ?? null;

        // If status is present but no late data provided, check if it should be late
        if ($status === 'present' && ! isset($data['late_minutes']) && isset($data['schedule_id'])) {
            $schedule = Schedule::find($data['schedule_id']);
            if ($schedule) {
                $lateData = $this->determineStatus($schedule);
                $status = $lateData['status'];
                $lateMinutes = $lateData['late_minutes'];
                $lateLabel = $lateData['late_label'];
            }
        }

        // If manual late entry from modal (has minutes but no label), resolve label automatically
        if ($status === 'late' && $lateMinutes > 0 && ! $lateLabel) {
            $lateLevels = $this->getLateLevels();
            foreach ($lateLevels as $level) {
                if ($lateMinutes >= $level['minutes']) {
                    $lateLabel = $level['label'];
                    break;
                }
            }
        }

        return DB::transaction(function () use ($data, $status, $lateMinutes, $lateLabel) {
            $tenantId = $data['tenant_id'] ?? \Modules\Tenancy\Services\TenantResolver::get()->id;
            $sessionDate = $data['session_date'] ?? today();

            // Lock the attendance row for this student/schedule/date to prevent concurrent creation
            // The unique constraint on (tenant_id, student_id, schedule_id, session_date) is the
            // authoritative guard, but we also lock here for the session deduction logic.
            $attendance = Attendance::lockForUpdate()->where([
                'student_id' => $data['student_id'],
                'schedule_id' => $data['schedule_id'],
                'session_date' => $sessionDate,
            ])->first();

            $isNewAttendance = false;

            if (! $attendance) {
                $attendance = Attendance::create([
                    'tenant_id' => $tenantId,
                    'student_id' => $data['student_id'],
                    'course_id' => $data['course_id'],
                    'schedule_id' => $data['schedule_id'],
                    'session_date' => $sessionDate,
                    'check_in_time' => now(),
                    'status' => $status,
                    'late_minutes' => $lateMinutes,
                    'late_label' => $lateLabel,
                ]);
                $isNewAttendance = true;
            } else {
                // Update existing attendance if status changed to present/late from absent/excused
                $wasAbsent = in_array($attendance->status, ['absent', 'excused']);
                $isArriving = in_array($status, ['present', 'late']);

                if ($wasAbsent && $isArriving) {
                    $attendance->update([
                        'check_in_time' => now(),
                        'status' => $status,
                        'late_minutes' => $lateMinutes,
                        'late_label' => $lateLabel,
                    ]);
                    $isNewAttendance = true; // Treat as new for deduction purposes
                }
            }

            // Send WhatsApp Notification if student arrived (even if previously marked as absent)
            $isArriving = in_array($status, ['present', 'late']);
            $wasAlreadyPresent = ! $isNewAttendance && in_array($attendance->getOriginal('status') ?? $attendance->status, ['present', 'late']);

            if ($isArriving && ! $wasAlreadyPresent) {
                $student = Student::with('user')->find($data['student_id']);
                $schedule = Schedule::with('course')->find($data['schedule_id']);
                $tenant = \Modules\Tenancy\Services\TenantResolver::get();

                if ($student && $schedule) {
                    // Deduct session from balance atomically with row lock
                    $enrollment = Enrollment::where('user_id', $student->user_id)
                        ->where('course_id', $data['course_id'])
                        ->lockForUpdate()
                        ->first();

                    if ($enrollment) {
                        $totalSessions = $schedule->course->sessions_count ?? 0;
                        $progress = 0;

                        if ($totalSessions > 0) {
                            $remaining = max(0, $enrollment->remaining_sessions - 1);
                            $consumed = $totalSessions - $remaining;
                            $progress = min(100, round(($consumed / $totalSessions) * 100));
                        }

                        if ($enrollment->remaining_sessions > 0) {
                            $enrollment->decrement('remaining_sessions', 1, ['progress' => $progress]);
                        } else {
                            $enrollment->update(['progress' => $progress]);
                        }
                    }

                    $attendanceAlertEnabled = ! isset($tenant->settings['academic']['attendance_alert']) || $tenant->settings['academic']['attendance_alert'];

                    if ($attendanceAlertEnabled) {
                        // Dispatch on queue for better performance
                        \App\Jobs\SendWhatsAppNotification::dispatch($tenant, $student, $schedule->course)->onQueue('whatsapp');

                        // Send Email Notification if enabled
                        $this->sendEmailNotification($tenant, $student, $schedule->course, $status);
                    }

                    // Award points for attendance (maybe reduction for late?)
                    if ($student->user) {
                        $points = $status === 'late' ? 5 : 10;
                        \App\Jobs\AwardGamificationPoints::dispatch(
                            $student->user,
                            $points,
                            'Attended session: '.$schedule->course->title.($status === 'late' ? ' (Late)' : ''),
                            $attendance
                        )->onQueue('gamification');
                    }
                }
            }

            return $attendance;
        });
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

        usort($lateLevels, function ($a, $b) {
            return $b['minutes'] <=> $a['minutes'];
        });

        return $lateLevels;
    }

    /**
     * Generate a signed QR URL for attendance.
     */
    public function generateQrUrl(int $scheduleId, ?string $tenantDomain = null)
    {
        if (! $tenantDomain && app()->bound('tenant')) {
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
            $notifEnabled = ! isset($tenantSettings['notif_attendance_enabled']) || $tenantSettings['notif_attendance_enabled'];

            if (! $notifEnabled) {
                return;
            }

            // Determine real email
            $realEmail = null;
            $studentEmail = $student->email ?? ($student->user ? $student->user->email : null);
            if ($studentEmail && ! preg_match('/^std\d+\..+@taalimu\.com$/', $studentEmail)) {
                $realEmail = $studentEmail;
            }
            // Fetch guardian emails dynamically from the guardians relation
            $guardianEmails = [];
            if ($student->relationLoaded('guardians') || $student->guardians()->exists()) {
                $guardianEmails = $student->guardians->pluck('email')->filter()->toArray();
            }

            if ($realEmail || !empty($guardianEmails)) {
                $locale = $this->getTargetLocale($tenant, $student);

                $subjectKey = "notif_attendance_subject_{$locale}";
                $bodyKey = "notif_attendance_body_{$locale}";

                $defaultSubjects = [
                    'ar' => 'إشعار الحضور - {center_name}',
                    'en' => 'Attendance Notification - {center_name}',
                    'fr' => 'Notification de présence - {center_name}',
                ];

                $defaultBodies = [
                    'ar' => "مرحباً {student_name},\n\nنود إعلامك بأنه تم تسجيل حضورك في {course_name}.\nالحالة: {status}\n\nمع تحياتنا,\n{center_name}",
                    'en' => "Hello {student_name},\n\nWe would like to inform you that your attendance for {course_name} has been recorded.\nStatus: {status}\n\nBest regards,\n{center_name}",
                    'fr' => "Bonjour {student_name},\n\nNous vous informons que votre présence pour {course_name} a été enregistrée.\nStatut: {status}\n\nCordialement,\n{center_name}",
                ];

                $subject = $tenantSettings[$subjectKey] ?? $tenantSettings['notif_attendance_subject'] ?? ($defaultSubjects[$locale] ?? $defaultSubjects['en']);
                $body = $tenantSettings[$bodyKey] ?? $tenantSettings['notif_attendance_body'] ?? ($defaultBodies[$locale] ?? $defaultBodies['en']);

                $variables = [
                    'student_name' => $student->name,
                    'course_name' => $course->title,
                    'center_name' => $tenant->name,
                    'status' => __('center::students.'.$status, [], $locale),
                    'date' => now()->format('Y-m-d'),
                ];

                if ($realEmail) {
                    Mail::to($realEmail)->queue(new AttendanceNotificationMail(
                        $subject, $body, $variables, $tenant->name, $student->name
                    ));
                }

                foreach ($guardianEmails as $parentEmail) {
                    Mail::to($parentEmail)->queue(new AttendanceNotificationMail(
                        $subject, $body, $variables, $tenant->name, $student->name
                    ));
                }
            }
        } catch (\Exception $e) {
            Log::error('AttendanceService email notification failed: '.$e->getMessage());
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
