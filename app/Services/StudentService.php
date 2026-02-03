<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use App\Models\Guardian;
use App\DTOs\StudentData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentService
{
    protected $adminNotificationService;

    public function __construct(AdminNotificationService $adminNotificationService)
    {
        $this->adminNotificationService = $adminNotificationService;
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
        return DB::transaction(function () use ($data, $creator, $notify) {
            // ... (keep existing logic unchanged until notification) ...
            // 1. Generate a secure random password
            $generatedPassword = Str::random(12);

            // 2. Handle Profile Photo Path
            $profilePhotoPath = $data->profile_photo;

            // 3. Resolve or Generate Email and Code
            $email = $data->email ?? $this->generateUniqueEmail();
            $code = $data->code ?? $this->generateUniqueCode();

            // 4. Create User account
            $user = User::create([
                'name' => $data->name,
                'email' => $email,
                'password' => Hash::make($generatedPassword),
                'role' => 'student',
                'tenant_id' => app('tenant')->id,
                'must_change_password' => true,
            ]);

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

            // Notify Admins
            if ($notify) {
                $this->notifyAdminsAboutRegistration($student, $creator);
            }

            return $result;
        });
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
