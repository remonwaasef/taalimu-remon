<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use App\Models\Guardian;
use App\DTOs\StudentData;
use App\Mail\WelcomeStudentMail;
use App\Mail\WelcomeGuardianMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\FinanceService;

use App\Traits\HasLocaleResolution;

class StudentService
{
    use HasLocaleResolution;
    protected $adminNotificationService;
    protected $financeService;

    public function __construct(AdminNotificationService $adminNotificationService, FinanceService $financeService)
    {
        $this->adminNotificationService = $adminNotificationService;
        $this->financeService = $financeService;
    }

    /**
     * Register a new student.
     *
     * @param StudentData $data
     * @param User $creator
     * @param bool $notify
     * @return array
     */
    public function registerStudent(StudentData $data, User $creator, bool $notify = true)
    {
        $result = DB::transaction(function () use ($data, $creator, $notify) {
            // ... (keep existing logic unchanged until notification) ...
            // 1. Generate a secure random password
            $generatedPassword = Str::random(12);

            // 2. Handle Profile Photo Path
            $profilePhotoPath = $data->profile_photo;

            // 3. Resolve or Generate Email and Code
            $email = $data->email ?? $this->generateUniqueEmail();
            $code = $data->code ?? $this->generateUniqueCode();

            // 4. Handle User account (Check if email or phone already exists IN THIS TENANT)
            $existingUser = User::where('email', $email)->first();
            
            if (!$existingUser && !empty($data->phone)) {
                $existingUser = User::where('phone', $data->phone)->first();
            }

            if ($existingUser) {
                
                // If user exists in SAME tenant, check if they are already a student
                $student = Student::where('user_id', $existingUser->id)->first();
                if ($student) {
                    // Update existing student instead of creating a new one
                    $student->update([
                        'grade_id' => $data->grade_id,
                        'name' => $data->name,
                        'phone' => $data->phone,
                    ]);
                    
                    return [
                        'user' => $existingUser,
                        'student' => $student,
                        'generated_password' => null, // Password not changed
                    ];
                }
                
                $user = $existingUser;
            } else {
                // Create new User account
                $user = User::create([
                    'name' => $data->name,
                    'email' => $email,
                    'phone' => $data->phone,
                    'password' => Hash::make($generatedPassword),
                    'role' => 'student',
                    'tenant_id' => app('tenant')->id,
                    'must_change_password' => true,
                ]);
            }

            // 4.5 Handle Guardian
            $guardianId = null;
            if ($data->parent_phone) {
                $guardian = Guardian::updateOrCreate(
                    ['tenant_id' => app('tenant')->id, 'phone' => $data->parent_phone],
                    [
                        'name' => $data->parent_name ?? 'N/A',
                        'job' => $data->parent_job,
                        'address' => $data->address,
                    ]
                );
                $guardianId = $guardian->id;
            }

            // 5. Create Student profile
            $student = Student::create([
                'tenant_id' => app('tenant')->id,
                'user_id' => $user->id,
                'grade_id' => $data->grade_id,
                'guardian_id' => $guardianId,
                'grade_level' => $data->grade_level,
                'code' => $code,
                'national_id' => $data->national_id,
                'name' => $data->name,
                'email' => $email,
                'phone' => $data->phone,
                'parent_phone' => $data->parent_phone,
                'parent_email' => $data->parent_email,
                'status' => 'active',
                'birth_date' => $data->birth_date,
                'gender' => $data->gender,
                'address' => $data->address,
                'parent_name' => $data->parent_name,
                'parent_job' => $data->parent_job,
                'parent_relation' => $data->parent_relation,
                'emergency_phone' => $data->emergency_phone,
                'school_name' => $data->school_name,
                'section_type' => $data->section_type,
                'profile_photo' => $profilePhotoPath,
                'joined_at' => now(),
            ]);

            $result = [
                'user' => $user,
                'student' => $student,
                'generated_password' => $generatedPassword,
            ];

            // Enroll in selected courses via FinanceService to ensure sales & commissions are recorded
            if (!empty($data->course_ids)) {
                $items = [];
                $courses = \App\Models\Course::whereIn('id', $data->course_ids)->get();
                foreach ($courses as $course) {
                    $items[] = ['id' => $course->id, 'price' => $course->price];
                }

                if (!empty($items)) {
                    $this->financeService->createSale([
                        'student_id' => $student->id,
                        'items' => $items,
                        'payment_method' => 'cash',
                        'paid_amount' => 0, // Unpaid by default during registration
                    ]);
                }
            }

            // Notify Admins
            if ($notify) {
                $this->notifyAdminsAboutRegistration($student, $creator);
            }

            return $result;
        });

        // Send welcome emails AFTER transaction commits (outside DB::transaction)
        if (isset($result['student'])) {
            $this->sendWelcomeEmails($result['student'], $result['generated_password']);
            
            if (!empty($data->course_ids)) {
                $this->sendGroupEnrollmentEmails($result['student'], $data->course_ids);
            }
        }

        return $result;
    }

    /**
     * Notify admins about a new student registration.
     */
    protected function notifyAdminsAboutRegistration(Student $student, User $creator)
    {
        $this->adminNotificationService->notifyAdmins(
            'student_registered',
            "تم تسجيل طالب جديد: {$student->name}",
            route('center.students.show', ['tenant' => app('tenant')->domain, 'student' => $student->id]),
            'fas fa-user-plus',
            $creator->name
        );
    }

    /**
     * Update an existing student.
     *
     * @param Student $student
     * @param StudentData $data
     * @return Student
     */
    public function updateStudent(Student $student, StudentData $data, User $modifier)
    {
        return DB::transaction(function () use ($student, $data, $modifier) {
            // 1. Update User account
            $user = $student->user;
            $user->update([
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone, // Synchronize phone on update
            ]);

            // 1.5 Handle Guardian
            $guardianId = $student->guardian_id;
            if ($data->parent_phone) {
                $guardian = Guardian::updateOrCreate(
                    ['tenant_id' => app('tenant')->id, 'phone' => $data->parent_phone],
                    [
                        'name' => $data->parent_name ?? 'N/A',
                        'job' => $data->parent_job,
                        'address' => $data->address,
                    ]
                );
                $guardianId = $guardian->id;
            }

            // 2. Update Student Profile
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

            // Notify Admins
            $this->notifyAdminsAboutUpdate($student, $modifier);

            return $student;
        });
    }

    /**
     * Notify admins about student update.
     */
    protected function notifyAdminsAboutUpdate(Student $student, User $modifier)
    {
        $this->adminNotificationService->notifyAdmins(
            'student_updated',
            "تم تحديث بيانات الطالب: {$student->name}",
            route('center.students.show', ['tenant' => app('tenant')->domain, 'student' => $student->id]),
            'fas fa-user-edit',
            $modifier->name
        );
    }

    /**
     * Get data for export using cursor for memory efficiency.
     * 
     * @return \Illuminate\Support\LazyCollection
     */
    public function getExportData()
    {
        return Student::where('tenant_id', app('tenant')->id)
            ->with(['grade.stage']) // Optimize: nested eager loading
            ->lazy(1000)
            ->map(function ($student) {
                return [
                    $student->id,
                    $student->name,
                    $student->email,
                    $student->phone,
                    $student->grade_level_name, // Accessor
                    $student->school_name ?? '---',
                    $student->section_type ?? '---',
                    $student->status,
                ];
            });
    }

    public function getProfileData(Student $student)
    {
        $tenantId = app('tenant')->id;
        $userId = $student->user_id;

        // 1. Attendance & Session Stats
        $attendanceLogs = \Modules\Center\Models\Attendance::where('student_id', $student->id)
            ->with(['course', 'schedule'])
            ->latest('session_date')
            ->get();
            
        $presentCount = $attendanceLogs->whereIn('status', ['present', 'late'])->count();
        $absentCount = $attendanceLogs->where('status', 'absent')->count();
        $totalAttendanceRecords = $attendanceLogs->count();
        
        // Accurate Total Sessions from Enrolled Courses
        $totalCourseSessions = (int) $student->enrollments()
            ->with('course')
            ->get()
            ->sum(function($enrollment) {
                return $enrollment->course->sessions_count ?? 0;
            });

        $attendancePercentage = $totalAttendanceRecords > 0 ? round(($presentCount / $totalAttendanceRecords) * 100) : 0;

        // 2. Quiz Stats
        $quizAttempts = \App\Models\QuizAttempt::where('user_id', $userId)
            ->with('quiz')
            ->latest()
            ->get();
            
        $avgQuizScore = $quizAttempts->count() > 0 ? round($quizAttempts->avg('score')) : 0;
        $highestScore = $quizAttempts->count() > 0 ? $quizAttempts->max('score') : 0;

        // 3. Behavioral Points
        $pointsLogs = \App\Models\PointLog::where('user_id', $userId)->get();
        $pointBalance = $pointsLogs->sum('points');
        $pointsEarned = $pointsLogs->where('points', '>', 0)->sum('points');
        $pointsSpent = abs($pointsLogs->where('points', '<', 0)->sum('points'));

        // 4. Assignment Submissions
        $assignments = \App\Models\AssignmentSubmission::where('user_id', $userId)
            ->with('assignment.course')
            ->latest()
            ->get();

        $data = [
            'recent_activity' => $student->activities()->with('causer')->latest()->take(10)->get(),
            'enrollments' => $student->enrollments()->with('course')->get(),
            'sales' => $student->sales()->latest()->get(),
            'bookings' => $student->bookings()->with(['schedule.course', 'schedule.classroom'])->get(),
            'availableSchedules' => \App\Models\Schedule::where('tenant_id', $tenantId)
                ->with(['course', 'classroom', 'instructor'])
                ->get(),
            
            // Elite Stats
            'stats' => [
                'attendance_pct' => $attendancePercentage,
                'attendance_count' => $presentCount,
                'absent_count' => $absentCount,
                'total_sessions' => $totalCourseSessions ?: $totalAttendanceRecords, // Use course sum OR record count
                'avg_quiz_score' => $avgQuizScore,
                'highest_score' => $highestScore,
                'quiz_count' => $quizAttempts->count(),
                'points' => $pointBalance,
                'points_earned' => $pointsEarned,
                'points_spent' => $pointsSpent,
                'remaining_sessions_count' => (int) $student->enrollments()->sum('remaining_sessions'),
            ],
            
            // Logs
            'attendance_logs' => $attendanceLogs,
            'quiz_attempts' => $quizAttempts,
            'assignments' => $assignments,
            'point_logs' => \App\Models\PointLog::where('user_id', $userId)->latest()->get(),
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
            $studentName = $student->name; // Capture name before deletion

            // 1. Delete User account
            if ($student->user) {
                $student->user->delete();
            }

            // 2. Delete Student Profile
            $result = $student->delete();

            // Notify Admins
            $this->notifyAdminsAboutDeletion($studentName, $deleter);

            return $result;
        });
    }

    /**
     * Notify admins about student deletion.
     */
    protected function notifyAdminsAboutDeletion(string $studentName, User $deleter)
    {
        $this->adminNotificationService->notifyAdmins(
            'student_deleted',
            "تم حذف الطالب: {$studentName}",
            route('center.students.index', ['tenant' => app('tenant')->domain]),
            'fas fa-user-times',
            $deleter->name
        );
    }

    /**
     * Import students from CSV data efficiently.
     *
     * @param array $csvData
     * @return array
     */
    public function importStudents(array $csvData)
    {
        $tenantId = app('tenant')->id;
        $successCount = 0;
        $errors = [];
        $creator = auth()->user();
        
        // 1. Pre-fetch Data for Validation (Memory Optimization)
        $inputEmails = collect($csvData)->pluck(1)->filter()->unique()->toArray();
        $inputGradeNames = collect($csvData)->pluck(3)->filter()->unique()->toArray();
        
        // Fetch existing emails in one query
        $existingEmails = User::whereIn('email', $inputEmails)->pluck('email')->flip(); 
        
        // Fetch Grades in one query
        $grades = \App\Models\Grade::where('tenant_id', $tenantId)
            ->where(function($q) use ($inputGradeNames) {
                $q->whereIn('name', $inputGradeNames)
                  ->orWhereIn('id', $inputGradeNames);
            })->get();
            
        // Map Grades by Name and ID for O(1) lookup
        $gradesMap = $grades->pluck('id', 'name')->union($grades->pluck('id', 'id'));

        // Prepare chunks for bulk insertion
        $usersToInsert = [];
        $studentsToInsert = [];
        $now = now();
        $passwordHash = Hash::make(Str::random(12)); // Common hash for initial import, users change it later

        foreach ($csvData as $index => $row) {
            $rowIndex = $index + 1;
            
            // Expected format: name, email, phone, grade_level
            $name = $row[0] ?? null;
            $email = $row[1] ?? null;
            $phone = $row[2] ?? null;
            $gradeLevel = $row[3] ?? null;

            // Basic Validation
            if (!$name || !$email || !$phone) {
                $errors[] = "Row {$rowIndex}: Missing required fields.";
                continue;
            }

            // Security: Prevent CSV Injection
            foreach ([$name, $email, $phone, $gradeLevel] as $field) {
                if ($field && in_array(substr((string)$field, 0, 1), ['=', '+', '-', '@'])) {
                    $errors[] = "Row {$rowIndex}: Unsafe characters detected.";
                    continue 2;
                }
            }

            // Check Duplicate Email (Memory Check)
            if ($existingEmails->has($email)) {
                $errors[] = "Row {$rowIndex}: Email {$email} already exists.";
                continue;
            }
            
            // Resolve Grade
            $gradeId = $gradesMap->get($gradeLevel);

            // Prepare User Data
            $usersToInsert[] = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone, // Synchronize phone during import
                'password' => $passwordHash,
                'role' => 'student',
                'tenant_id' => $tenantId,
                'must_change_password' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Prepare Student Data (We need the User ID, so we'll handle this in chunks)
            $studentsToInsert[] = [
                'grade_id' => $gradeId,
                'grade_level' => $gradeLevel, // Store raw string as fallback or display
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'tenant_id' => $tenantId,
                'status' => 'active',
                'joined_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
                // Add default empty fields to avoid SQL errors if strict compliance is on
                'parent_phone' => null, 'address' => null, 'birth_date' => null, 
                'gender' => null, 'parent_name' => null, 'parent_job' => null, 
                'parent_relation' => null, 'emergency_phone' => null, 'school_name' => null, 
                'section_type' => null, 'profile_photo' => null,
            ];
            
            $successCount++;
        }

        if (!empty($usersToInsert)) {
            DB::transaction(function () use ($usersToInsert, $studentsToInsert) {
                // Bulk Insert Users
                User::insert($usersToInsert);
                
                // Get IDs of inserted users (assuming emails are unique and we just inserted them)
                // In high concurrency, this might be risky, but within a transaction for import it's acceptable
                $insertedUsers = User::whereIn('email', collect($usersToInsert)->pluck('email'))
                    ->pluck('id', 'email');

                // Map User IDs to Students
                foreach ($studentsToInsert as &$student) {
                    if (isset($insertedUsers[$student['email']])) {
                        $student['user_id'] = $insertedUsers[$student['email']];
                    }
                }
                
                // Bulk Insert Students
                // Chunking again to be safe with placeholer limits
                foreach (array_chunk($studentsToInsert, 500) as $chunk) {
                    Student::insert($chunk);
                }
            });
            
            // Note: Batch notification is better than individual notifications for imports
             $this->adminNotificationService->notifyAdmins(
                'bulk_import',
                "تم استيراد {$successCount} طالب بنجاح",
                route('center.students.index', ['tenant' => app('tenant')->domain]),
                'fas fa-file-import',
                $creator->name
            );

            // Send welcome emails for imported students (queued)
            $this->sendBulkWelcomeEmails(
                collect($studentsToInsert)->pluck('email')->toArray(),
                $passwordHash
            );
        }

        return [
            'success_count' => $successCount,
            'errors' => $errors,
        ];
    }


    /**
     * Reset student password to a new random one.
     *
     * @param User $user
     * @return string
     */
    public function resetPassword(User $user)
    {
        $newPassword = Str::random(12);
        
        $user->update([
            'password' => Hash::make($newPassword),
            'must_change_password' => true,
        ]);

        return $newPassword;
    }

    /**
     * Send welcome emails to the student and their guardian after registration.
     * Only sends to real email addresses (not auto-generated ones).
     */
    protected function sendWelcomeEmails(Student $student, ?string $generatedPassword): void
    {
        try {
            $tenant = app('tenant');
            $settings = $this->getEmailTemplateSettings($tenant);

            $variables = $this->buildTemplateVariables($student, $tenant, $generatedPassword);
            $locale = $this->getTargetLocale($tenant, $student);

            // Send to Student
            if ($settings['welcome_student_enabled'] && $this->isRealEmail($student->email)) {
                $subject = $settings["welcome_student_subject_{$locale}"] ?? $settings['welcome_student_subject'];
                $body = $settings["welcome_student_body_{$locale}"] ?? $settings['welcome_student_body'];

                Mail::to($student->email)->queue(new WelcomeStudentMail(
                    $student,
                    $subject,
                    $body,
                    $variables,
                    $tenant->name
                ));
            }

            // Send to Guardian
            if ($settings['welcome_guardian_enabled']) {
                // Priority: direct parent_email field > guardian relationship email
                $guardianEmail = $student->parent_email ?: $student->guardian?->email;
                $guardianName = $student->parent_name ?? $student->guardian?->name ?? '';

                if ($guardianEmail && $this->isRealEmail($guardianEmail)) {
                    $gSubject = $settings["welcome_guardian_subject_{$locale}"] ?? $settings['welcome_guardian_subject'];
                    $gBody = $settings["welcome_guardian_body_{$locale}"] ?? $settings['welcome_guardian_body'];

                    Mail::to($guardianEmail)->queue(new WelcomeGuardianMail(
                        $guardianName,
                        $student->name,
                        $gSubject,
                        $gBody,
                        $variables,
                        $tenant->name
                    ));
                }
            }
        } catch (\Exception $e) {
            // Never block registration because of email failures
            Log::error("Failed to queue welcome emails for student {$student->id}: " . $e->getMessage());
        }
    }

    /**
     * Send group enrollment emails to the student and their guardian.
     */
    protected function sendGroupEnrollmentEmails(Student $student, array $courseIds): void
    {
        try {
            $tenant = app('tenant');
            $tenantSettings = $tenant->settings['email_templates'] ?? [];
            
            // If explicitely disabled (0 or false), disable. Otherwise enable.
            $groupEnrollmentEnabled = !isset($tenantSettings['notif_group_enrollment_enabled']) || $tenantSettings['notif_group_enrollment_enabled'];
            
            if (!$groupEnrollmentEnabled) {
                return;
            }

            $hasValidStudentEmail = $this->isRealEmail($student->email);
            $guardianEmail = $student->parent_email ?: $student->guardian?->email;
            $hasValidParentEmail = $this->isRealEmail($guardianEmail);

            if (!$hasValidStudentEmail && !$hasValidParentEmail) {
                return;
            }

            $locale = $this->getTargetLocale($tenant, $student);

            $groupSubjectKey = "notif_group_enrollment_subject_{$locale}";
            $groupBodyKey = "notif_group_enrollment_body_{$locale}";

            $defaultGroupSubjects = [
                'ar' => 'تم تسجيلك في مجموعة جديدة',
                'en' => 'You have been enrolled in a new group',
                'fr' => 'Vous avez été inscrit dans un nouveau groupe',
            ];
            $defaultGroupBodies = [
                'ar' => "مرحباً {student_name}،\n\nلقد تم تسجيلك بنجاح في {group_name}.\nنتمنى لك التوفيق!\n\n{center_name}",
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
                    Mail::to($student->email)->queue(new \App\Mail\NotifGroupEnrollmentMail(
                        $groupSubject, $groupBody, $groupVariables, $tenant->name, $student->name
                    ));
                }

                if ($hasValidParentEmail) {
                    Mail::to($guardianEmail)->queue(new \App\Mail\NotifGroupEnrollmentMail(
                        $groupSubject, $groupBody, $groupVariables, $tenant->name, $student->name
                    ));
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to queue group enrollment emails for student {$student->id}: " . $e->getMessage());
        }
    }

    /**
     * Send welcome emails for bulk-imported students (queued).
     * Fetches students by their emails and dispatches welcome mails.
     *
     * @param array  $emails       List of student emails that were imported
     * @param string $passwordHash The shared password hash (not the raw password)
     */
    protected function sendBulkWelcomeEmails(array $emails, string $passwordHash): void
    {
        try {
            $tenant = app('tenant');
            $settings = $this->getEmailTemplateSettings($tenant);

            if (!$settings['welcome_student_enabled'] && !$settings['welcome_guardian_enabled']) {
                return; // Both disabled, skip entirely
            }

            // Fetch only students with real emails
            $students = Student::where('tenant_id', $tenant->id)
                ->whereIn('email', $emails)
                ->with('guardian')
                ->get()
                ->filter(fn($s) => $this->isRealEmail($s->email));

            foreach ($students as $student) {
                // For bulk imports we don't have the raw password (only the hash),
                // so we indicate the student should use "forgot password"
                $variables = $this->buildTemplateVariables($student, $tenant, null);
                $locale = $this->getTargetLocale($tenant, $student);

                if ($settings['welcome_student_enabled']) {
                    $subject = $settings["welcome_student_subject_{$locale}"] ?? $settings['welcome_student_subject'];
                    $body = $settings["welcome_student_body_{$locale}"] ?? $settings['welcome_student_body'];
                    
                    $passwordHints = [
                        'ar' => '(يرجى استخدام "نسيت كلمة المرور" لتعيين كلمة مرور جديدة)',
                        'en' => '(Please use "Forgot Password" to set a new password)',
                        'fr' => '(Veuillez utiliser « Mot de passe oublié » pour définir un nouveau mot de passe)',
                    ];
                    $passwordHint = $passwordHints[$locale] ?? $passwordHints['en'];

                    Mail::to($student->email)->queue(new WelcomeStudentMail(
                        $student,
                        $subject,
                        str_replace('{password}', $passwordHint, $body),
                        $variables,
                        $tenant->name
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
                            $guardianName,
                            $student->name,
                            $gSubject,
                            str_replace('{password}', $guardianPasswordHint, $gBody),
                            $variables,
                            $tenant->name
                        ));
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to queue bulk welcome emails: " . $e->getMessage());
        }
    }

    /**
     * Get email template settings for the tenant, with fallback to config defaults.
     */
    protected function getEmailTemplateSettings($tenant): array
    {
        $tenantSettings = $tenant->settings['email_templates'] ?? [];

        // Get default preset from config
        $defaultPresetKey = config('email_templates.default_preset', 'formal');
        $defaultPreset = config("email_templates.presets.{$defaultPresetKey}", []);

        return [
            'welcome_student_enabled'  => (bool) ($tenantSettings['welcome_student_enabled'] ?? true),
            'welcome_guardian_enabled' => (bool) ($tenantSettings['welcome_guardian_enabled'] ?? true),
            
            'welcome_student_subject'  => $tenantSettings['welcome_student_subject'] ?? $defaultPreset['student_subject'] ?? '',
            'welcome_student_body'     => $tenantSettings['welcome_student_body'] ?? $defaultPreset['student_body'] ?? '',
            'welcome_guardian_subject' => $tenantSettings['welcome_guardian_subject'] ?? $defaultPreset['guardian_subject'] ?? '',
            'welcome_guardian_body'    => $tenantSettings['welcome_guardian_body'] ?? $defaultPreset['guardian_body'] ?? '',

            // Multi-lingual student subjects (tenant overrides → config defaults)
            'welcome_student_subject_ar' => $tenantSettings['welcome_student_subject_ar'] ?? null,
            'welcome_student_subject_en' => $tenantSettings['welcome_student_subject_en'] ?? $defaultPreset['student_subject_en'] ?? null,
            'welcome_student_subject_fr' => $tenantSettings['welcome_student_subject_fr'] ?? $defaultPreset['student_subject_fr'] ?? null,
            
            // Multi-lingual student bodies (tenant overrides → config defaults)
            'welcome_student_body_ar' => $tenantSettings['welcome_student_body_ar'] ?? null,
            'welcome_student_body_en' => $tenantSettings['welcome_student_body_en'] ?? $defaultPreset['student_body_en'] ?? null,
            'welcome_student_body_fr' => $tenantSettings['welcome_student_body_fr'] ?? $defaultPreset['student_body_fr'] ?? null,

            // Guardian Multi-lingual subjects (tenant overrides → config defaults)
            'welcome_guardian_subject_ar' => $tenantSettings['welcome_guardian_subject_ar'] ?? null,
            'welcome_guardian_subject_en' => $tenantSettings['welcome_guardian_subject_en'] ?? $defaultPreset['guardian_subject_en'] ?? null,
            'welcome_guardian_subject_fr' => $tenantSettings['welcome_guardian_subject_fr'] ?? $defaultPreset['guardian_subject_fr'] ?? null,

            // Guardian Multi-lingual bodies (tenant overrides → config defaults)
            'welcome_guardian_body_ar' => $tenantSettings['welcome_guardian_body_ar'] ?? null,
            'welcome_guardian_body_en' => $tenantSettings['welcome_guardian_body_en'] ?? $defaultPreset['guardian_body_en'] ?? null,
            'welcome_guardian_body_fr' => $tenantSettings['welcome_guardian_body_fr'] ?? $defaultPreset['guardian_body_fr'] ?? null,
        ];
    }

    /**
     * Check if an email is a real one (not auto-generated by the system).
     */
    protected function isRealEmail(?string $email): bool
    {
        if (!$email) {
            return false;
        }
        // Auto-generated emails follow pattern: std{N}.{domain}@taalimu.com
        return !preg_match('/^std\d+\..+@taalimu\.com$/', $email);
    }

    /**
     * Build the variables array for template placeholder replacement.
     */
    protected function buildTemplateVariables(Student $student, $tenant, ?string $password): array
    {
        return [
            'student_name'    => $student->name,
            'center_name'    => $tenant->name,
            'login_link'   => url('/login'),
            'password'   => $password ?? '',
            'phone'    => $student->phone ?? '',
            'parent_name' => $student->guardian?->name ?? $student->parent_name ?? '',
            'stage'       => $student->grade_level_name ?? '',
        ];
    }

    /**
     * Generate a unique student code.
     *
     * @return string
     */
    protected function generateUniqueCode()
    {
        $tenantId = app('tenant')->id;
        $prefix = 'S-' . ($tenantId % 1000);
        
        $lastStudent = Student::where('tenant_id', $tenantId)
            ->where('code', 'like', $prefix . '%')
            ->latest('id')
            ->first();

        $counter = 1001;

        if ($lastStudent && preg_match('/-(\d+)$/', $lastStudent->code, $matches)) {
            $counter = intval($matches[1]) + 1;
        }

        $code = "{$prefix}-{$counter}";

        // Fast collision check
        while (Student::where('tenant_id', $tenantId)->where('code', $code)->exists()) {
            $counter++;
            $code = "{$prefix}-{$counter}";
        }

        return $code;
    }

    /**
     * Generate a unique auto-incremented email for students.
     *
     * @return string
     */
    protected function generateUniqueEmail()
    {
        $tenant = app('tenant');
        $subdomain = $tenant->domain;
        
        // Find existing users with the NEW format in this tenant
        $lastStudent = User::where('tenant_id', $tenant->id)
            ->where('role', 'student')
            ->where('email', 'like', "std%.{$subdomain}@taalimu.com")
            ->latest('id')
            ->first();

        $counter = 1;

        if ($lastStudent && preg_match('/std(\d+)\./', $lastStudent->email, $matches)) {
            $counter = intval($matches[1]) + 1;
        } else {
            // Fallback: check count of students in this tenant if no matching pattern found
            $counter = User::where('tenant_id', $tenant->id)->where('role', 'student')->count() + 1;
        }

        $email = "std{$counter}.{$subdomain}@taalimu.com";

        // Global safety check for collisions across all tenants
        while (User::withoutGlobalScopes()->where('email', $email)->exists()) {
            $counter++;
            $email = "std{$counter}.{$subdomain}@taalimu.com";
        }

        return $email;
    }
}
