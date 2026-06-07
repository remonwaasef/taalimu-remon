<?php

namespace App\Services\Student;

use App\Models\Student;
use App\Models\User;
use App\Mail\WelcomeStudentMail;
use App\Mail\WelcomeGuardianMail;
use App\Mail\NotifGroupEnrollmentMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Services\AdminNotificationService;
use App\Traits\HasLocaleResolution;

class StudentNotificationService
{
    use HasLocaleResolution;

    protected $adminNotificationService;

    public function __construct(AdminNotificationService $adminNotificationService)
    {
        $this->adminNotificationService = $adminNotificationService;
    }

    public function notifyAdminsAboutRegistration(Student $student, User $creator)
    {
        $this->adminNotificationService->notifyAdmins(
            'student_registered',
            'تم تسجيل طالب جديد: ' . $student->name,
            route('center.students.show', ['tenant' => \Modules\Tenancy\Services\TenantResolver::get()->domain, 'student' => $student->id]),
            'fas fa-user-plus',
            $creator->name
        );
    }

    public function notifyAdminsAboutUpdate(Student $student, User $modifier)
    {
        $this->adminNotificationService->notifyAdmins(
            'student_updated',
            'تم تعديل بيانات الطالب: ' . $student->name,
            route('center.students.show', ['tenant' => \Modules\Tenancy\Services\TenantResolver::get()->domain, 'student' => $student->id]),
            'fas fa-user-edit',
            $modifier->name
        );
    }

    public function notifyAdminsAboutDeletion(string $studentName, User $deleter)
    {
        $this->adminNotificationService->notifyAdmins(
            'student_deleted',
            'تم حذف الطالب: ' . $studentName,
            route('center.students.index', ['tenant' => \Modules\Tenancy\Services\TenantResolver::get()->domain]),
            'fas fa-user-times',
            $deleter->name
        );
    }

    public function sendWelcomeEmails(Student $student, ?string $generatedPassword): void
    {
        try {
            $tenant = \Modules\Tenancy\Services\TenantResolver::get();
            $settings = $this->getEmailTemplateSettings($tenant);

            $variables = $this->buildTemplateVariables($student, $tenant, $generatedPassword);
            $locale = $this->getTargetLocale($tenant, $student);

            if ($settings['welcome_student_enabled'] && $this->isRealEmail($student->email)) {
                $subject = $settings["welcome_student_subject_{$locale}"] ?? $settings['welcome_student_subject'];
                $body = $settings["welcome_student_body_{$locale}"] ?? $settings['welcome_student_body'];

                Mail::to($student->email)->queue(new WelcomeStudentMail(
                    $student, $subject, $body, $variables, $tenant->name
                ));
            }

            if ($settings['welcome_guardian_enabled']) {
                $guardianEmail = $student->parent_email ?: $student->guardian?->email;
                $guardianName = $student->parent_name ?? $student->guardian?->name ?? '';

                if ($guardianEmail && $this->isRealEmail($guardianEmail)) {
                    $gSubject = $settings["welcome_guardian_subject_{$locale}"] ?? $settings['welcome_guardian_subject'];
                    $gBody = $settings["welcome_guardian_body_{$locale}"] ?? $settings['welcome_guardian_body'];

                    Mail::to($guardianEmail)->queue(new WelcomeGuardianMail(
                        $guardianName, $student->name, $gSubject, $gBody, $variables, $tenant->name
                    ));
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to queue welcome emails for student {$student->id}: " . $e->getMessage());
        }
    }

    public function sendGroupEnrollmentEmails(Student $student, array $courseIds): void
    {
        try {
            $tenant = \Modules\Tenancy\Services\TenantResolver::get();
            $tenantSettings = $tenant->settings['email_templates'] ?? [];
            $groupEnrollmentEnabled = !isset($tenantSettings['notif_group_enrollment_enabled']) || $tenantSettings['notif_group_enrollment_enabled'];
            
            if (!$groupEnrollmentEnabled) return;

            $hasValidStudentEmail = $this->isRealEmail($student->email);
            $guardianEmail = $student->parent_email ?: $student->guardian?->email;
            $hasValidParentEmail = $this->isRealEmail($guardianEmail);

            if (!$hasValidStudentEmail && !$hasValidParentEmail) return;

            $locale = $this->getTargetLocale($tenant, $student);
            $groupSubjectKey = "notif_group_enrollment_subject_{$locale}";
            $groupBodyKey = "notif_group_enrollment_body_{$locale}";

            $defaultGroupSubjects = [
                'ar' => 'تم تسجيلك في مجموعة جديدة',
                'en' => 'You have been enrolled in a new group',
                'fr' => 'Vous avez été inscrit dans un nouveau groupe',
            ];
            $defaultGroupBodies = [
                'ar' => "مرحباً {student_name},\n\nتم تسجيلك بنجاح في {group_name}.\nنتمنى لك التوفيق!\n\n{center_name}",
                'en' => "Hello {student_name},\n\nYou have been successfully enrolled in {group_name}.\nWe wish you the best of luck!\n\n{center_name}",
                'fr' => "Bonjour {student_name},\n\nVous avez été inscrit avec succès dans {group_name}.\nNous vous souhaitons bonne chance !\n\n{center_name}",
            ];

            $groupSubject = $tenantSettings[$groupSubjectKey] ?? $tenantSettings['notif_group_enrollment_subject'] ?? ($defaultGroupSubjects[$locale] ?? $defaultGroupSubjects['en']);
            $groupBody = $tenantSettings[$groupBodyKey] ?? $tenantSettings['notif_group_enrollment_body'] ?? ($defaultGroupBodies[$locale] ?? $defaultGroupBodies['en']);

            $currencySymbol = function_exists('get_currency_symbol') ? get_currency_symbol() : ($tenant->settings['financial']['currency'] ?? 'EGP');

            foreach ($courseIds as $courseId) {
                $course = \App\Models\Course::find($courseId);
                if (!$course) continue;

                $groupVariables = [
                    'student_name' => $student->name,
                    'center_name' => $tenant->name,
                    'group_name' => $course->title,
                    'course_price' => $course->price . ' ' . $currencySymbol,
                    'login_link' => url('/login'),
                ];

                if ($hasValidStudentEmail) {
                    Mail::to($student->email)->queue(new NotifGroupEnrollmentMail(
                        $groupSubject, $groupBody, $groupVariables, $tenant->name, $student->name
                    ));
                }

                if ($hasValidParentEmail) {
                    Mail::to($guardianEmail)->queue(new NotifGroupEnrollmentMail(
                        $groupSubject, $groupBody, $groupVariables, $tenant->name, $student->name
                    ));
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to queue group enrollment emails for student {$student->id}: " . $e->getMessage());
        }
    }

    public function sendBulkWelcomeEmails(array $emails, string $passwordHash): void
    {
        try {
            $tenant = \Modules\Tenancy\Services\TenantResolver::get();
            $settings = $this->getEmailTemplateSettings($tenant);

            if (!$settings['welcome_student_enabled'] && !$settings['welcome_guardian_enabled']) {
                return;
            }

            $students = Student::where('tenant_id', $tenant->id)
                ->whereIn('email', $emails)
                ->with('guardian')
                ->get()
                ->filter(fn($s) => $this->isRealEmail($s->email));

            foreach ($students as $student) {
                $variables = $this->buildTemplateVariables($student, $tenant, null);
                $locale = $this->getTargetLocale($tenant, $student);

                if ($settings['welcome_student_enabled']) {
                    $subject = $settings["welcome_student_subject_{$locale}"] ?? $settings['welcome_student_subject'];
                    $body = $settings["welcome_student_body_{$locale}"] ?? $settings['welcome_student_body'];
                    
                    $passwordHints = [
                        'ar' => '(يرجى استخدام "نسيت كلمة المرور" لإعادة تعيين كلمة مرور جديدة)',
                        'en' => '(Please use "Forgot Password" to set a new password)',
                        'fr' => '(Veuillez utiliser « Mot de passe oublié » pour définir un nouveau mot de passe)',
                    ];
                    $passwordHint = $passwordHints[$locale] ?? $passwordHints['en'];

                    Mail::to($student->email)->queue(new WelcomeStudentMail(
                        $student, $subject, str_replace('{password}', $passwordHint, $body), $variables, $tenant->name
                    ));
                }

                if ($settings['welcome_guardian_enabled']) {
                    $guardianEmail = $student->parent_email ?: $student->guardian?->email;
                    $guardianName = $student->parent_name ?? $student->guardian?->name ?? '';

                    if ($guardianEmail && $this->isRealEmail($guardianEmail)) {
                        $gSubject = $settings["welcome_guardian_subject_{$locale}"] ?? $settings['welcome_guardian_subject'];
                        $gBody = $settings["welcome_guardian_body_{$locale}"] ?? $settings['welcome_guardian_body'];

                        $guardianPasswordHints = [
                            'ar' => '(يرجى التواصل مع المركز للحصول على بيانات الدخول)',
                            'en' => '(Please contact the center to get the login credentials)',
                            'fr' => '(Veuillez contacter le centre pour obtenir les identifiants de connexion)',
                        ];
                        $guardianPasswordHint = $guardianPasswordHints[$locale] ?? $guardianPasswordHints['en'];

                        Mail::to($guardianEmail)->queue(new WelcomeGuardianMail(
                            $guardianName, $student->name, $gSubject, str_replace('{password}', $guardianPasswordHint, $gBody), $variables, $tenant->name
                        ));
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to queue bulk welcome emails: " . $e->getMessage());
        }
    }

    public function getEmailTemplateSettings($tenant): array
    {
        $tenantSettings = $tenant->settings['email_templates'] ?? [];
        $defaultPresetKey = config('email_templates.default_preset', 'formal');
        $defaultPreset = config("email_templates.presets.{$defaultPresetKey}", []);

        return [
            'welcome_student_enabled'  => (bool) ($tenantSettings['welcome_student_enabled'] ?? true),
            'welcome_guardian_enabled' => (bool) ($tenantSettings['welcome_guardian_enabled'] ?? true),
            'welcome_student_subject'  => $tenantSettings['welcome_student_subject'] ?? $defaultPreset['student_subject'] ?? '',
            'welcome_student_body'     => $tenantSettings['welcome_student_body'] ?? $defaultPreset['student_body'] ?? '',
            'welcome_guardian_subject' => $tenantSettings['welcome_guardian_subject'] ?? $defaultPreset['guardian_subject'] ?? '',
            'welcome_guardian_body'    => $tenantSettings['welcome_guardian_body'] ?? $defaultPreset['guardian_body'] ?? '',
            'welcome_student_subject_ar' => $tenantSettings['welcome_student_subject_ar'] ?? null,
            'welcome_student_subject_en' => $tenantSettings['welcome_student_subject_en'] ?? $defaultPreset['student_subject_en'] ?? null,
            'welcome_student_subject_fr' => $tenantSettings['welcome_student_subject_fr'] ?? $defaultPreset['student_subject_fr'] ?? null,
            'welcome_student_body_ar' => $tenantSettings['welcome_student_body_ar'] ?? null,
            'welcome_student_body_en' => $tenantSettings['welcome_student_body_en'] ?? $defaultPreset['student_body_en'] ?? null,
            'welcome_student_body_fr' => $tenantSettings['welcome_student_body_fr'] ?? $defaultPreset['student_body_fr'] ?? null,
            'welcome_guardian_subject_ar' => $tenantSettings['welcome_guardian_subject_ar'] ?? null,
            'welcome_guardian_subject_en' => $tenantSettings['welcome_guardian_subject_en'] ?? $defaultPreset['guardian_subject_en'] ?? null,
            'welcome_guardian_subject_fr' => $tenantSettings['welcome_guardian_subject_fr'] ?? $defaultPreset['guardian_subject_fr'] ?? null,
            'welcome_guardian_body_ar' => $tenantSettings['welcome_guardian_body_ar'] ?? null,
            'welcome_guardian_body_en' => $tenantSettings['welcome_guardian_body_en'] ?? $defaultPreset['guardian_body_en'] ?? null,
            'welcome_guardian_body_fr' => $tenantSettings['welcome_guardian_body_fr'] ?? $defaultPreset['guardian_body_fr'] ?? null,
        ];
    }

    public function isRealEmail(?string $email): bool
    {
        if (!$email) return false;
        return !preg_match('/^std\d+\..+@taalimu\.com$/', $email);
    }

    public function buildTemplateVariables(Student $student, $tenant, ?string $password): array
    {
        return [
            'student_name'    => $student->name,
            'center_name'     => $tenant->name,
            'login_link'      => url('/login'),
            'password'        => $password ?? '',
            'phone'           => $student->phone ?? '',
            'parent_name'     => $student->guardian?->name ?? $student->parent_name ?? '',
            'stage'           => $student->grade_level_name ?? '',
        ];
    }
}


