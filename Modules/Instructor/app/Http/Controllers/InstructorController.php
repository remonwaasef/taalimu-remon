<?php

namespace Modules\Instructor\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\Enrollment;
use App\Models\Sale;
use App\Models\Payment;
use App\Services\AttendanceService;
use Modules\Center\Models\Attendance;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeStudentMail;
use App\Mail\WelcomeGuardianMail;

class InstructorController extends Controller
{
    protected $instructor;
    protected $tenant;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->instructor = $this->resolveInstructor();
            $this->tenant = app('tenant');

            // Share globally with all views
            view()->share('instructor', $this->instructor);
            view()->share('tenant', $this->tenant);

            return $next($request);
        });
    }

    protected function authorizeCourse($course)
    {
        if ($this->instructor && $course->instructor_id !== $this->instructor->id) {
            abort(403, 'غير مصرح لك بإدارة هذا الكورس');
        }
    }

    protected function authorizeSchedule($schedule)
    {
        if ($this->instructor && $schedule->instructor_id !== $this->instructor->id) {
            abort(403, 'غير مصرح لك بإدارة هذا الموعد');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            
            // Temporary Cache Clearing
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
        } catch (\Exception $e) {
            // Ignore errors if already migrated or other issues for now
        }

        $instructor = $this->instructor;
        
        if (!$instructor) {
            // Fallback for demo or admin
            $courses = Course::take(5)->get();
            $totalStudents = Student::count();
            $totalCourses = Course::count();
            $monthlyRevenue = Sale::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('paid_amount');
        } else {
            $courses = $instructor->courses()->withCount('enrollments')->get();
            $totalStudents = Student::whereHas('enrollments', function($q) use ($instructor) {
                $q->whereIn('course_id', $instructor->courses->pluck('id'));
            })->count();
            $totalCourses = $courses->count();
            
            $monthlyRevenue = Sale::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->whereHas('student.enrollments', function($q) use ($instructor) {
                    $q->whereIn('course_id', $instructor->courses->pluck('id'));
                })->sum('paid_amount');
        }
        
        // Attendance Analytics (Last 7 Days)
        $attendanceData = [];
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days[] = $date->translatedFormat('D');
            
            $query = Attendance::whereDate('session_date', $date->toDateString());
            if ($instructor) {
                $query->whereIn('course_id', $instructor->courses->pluck('id'));
            }
            $attendanceData[] = $query->count();
        }

        return view('instructor::index', compact('courses', 'totalStudents', 'totalCourses', 'monthlyRevenue', 'attendanceData', 'days'));
    }




    /**
     * Show the QR Scanner interface for a specific course/group
     */
    public function scanner(Course $course)
    {
        // Try to find a schedule for today
        $dayOfWeek = now()->dayOfWeek;
        $schedule = Schedule::where('course_id', $course->id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        return view('instructor::scanner', compact('course', 'schedule'));
    }

    /**
     * Process a scanned QR identifier
     */
    public function scan(Request $request, Course $course)
    {
        $request->validate([
            'qr_identifier' => 'required|string',
            'schedule_id' => 'required|exists:schedules,id',
        ]);

        $user = \App\Models\User::where('qr_identifier', $request->qr_identifier)->first();

        if (!$user || !$user->student) {
            return response()->json([
                'success' => false,
                'message' => __('instructor::messages.student_not_found')
            ], 404);
        }

        $student = $user->student;

        // Check enrollment
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'student_name' => $student->name,
                'message' => __('instructor::messages.student_not_enrolled')
            ], 403);
        }

        // Mark Attendance using the existing service
        $attendanceService = app(AttendanceService::class);

        if ($attendanceService->hasAttendedToday($student->id, $request->schedule_id)) {
            return response()->json([
                'success' => true,
                'student_name' => $student->name,
                'already_marked' => true,
                'message' => __('instructor::messages.already_attended')
            ]);
        }

        $attendanceService->markAttendance([
            'tenant_id' => $student->tenant_id,
            'student_id' => $student->id,
            'course_id' => $course->id,
            'schedule_id' => $request->schedule_id,
            'session_date' => today(),
            'status' => 'present',
        ]);

        $msg = __('instructor::messages.attendance_notification', [
            'student' => $student->name,
            'course' => $course->title,
            'center' => $this->tenant->name
        ]);
        $phoneToNotify = $student->parent_phone ?: $student->phone;
        $whatsappUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $phoneToNotify) . "?text=" . urlencode($msg);

        return response()->json([
            'success' => true,
            'student_name' => $student->name,
            'remaining_sessions' => $enrollment->fresh()->remaining_sessions,
            'whatsapp_url' => $whatsappUrl,
            'message' => __('instructor::messages.scanned_success')
        ]);
    }

    /**
     * Display billing information for students
     */
    public function billing()
    {
        $instructor = $this->instructor;
        
        if (!$instructor) {
            $students = Student::with(['user', 'sales', 'enrollments.course'])->take(10)->get();
        } else {
            $students = Student::whereHas('enrollments', function($q) use ($instructor) {
                $q->whereIn('course_id', $instructor->courses->pluck('id'));
            })->with(['user', 'sales', 'enrollments.course'])->get();
        }

        return view('instructor::billing', compact('students'));
    }

    /**
     * Display a list of students enrolled in instructor's courses
     */
    public function students()
    {
        $instructor = $this->instructor;

        if (!$instructor) {
            $students = Student::with(['user', 'enrollments.course', 'sales'])->take(20)->get();
        } else {
            $students = Student::whereHas('enrollments', function($q) use ($instructor) {
                $q->whereIn('course_id', $instructor->courses->pluck('id'));
            })->with(['user', 'enrollments.course' => function($q) use ($instructor) {
                $q->where('instructor_id', $instructor->id);
            }, 'sales' => function($q) use ($instructor) {
                // Optionally filter sales if needed, but usually we want total student balance
            }])->get();
        }

        // Add attendance counts if needed, but for better performance we might calculate it per row or use a subquery
        // For now, let's ensure students have what's needed for the view logic
        
        return view('instructor::students.index', compact('students'));
    }

    /**
     * Export students to CSV
     */
    public function exportStudents()
    {
        $instructor = $this->instructor;
        
        if (!$instructor) {
            $students = Student::with(['enrollments.course'])->get();
        } else {
            $students = Student::whereHas('enrollments', function($q) use ($instructor) {
                $q->whereIn('course_id', $instructor->courses->pluck('id'));
            })->with(['enrollments.course'])->get();
        }

        $filename = "students_export_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            __('instructor::messages.csv_name'),
            __('instructor::messages.csv_phone'),
            __('instructor::messages.csv_parent_phone'),
            __('instructor::messages.csv_groups'),
            __('instructor::messages.csv_registration_date'),
            __('instructor::messages.csv_status')
        ];

        $callback = function() use($students, $columns) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel Arabic support
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($students as $student) {
                $row['Name']    = $student->name;
                $row['Phone']    = $student->phone;
                $row['Parent Phone']  = $student->parent_phone;
                $row['Groups']  = $student->enrollments->pluck('course.title')->implode(', ');
                $row['Registration Date']  = $student->created_at->format('Y-m-d');
                $row['Status']  = $student->status;

                fputcsv($file, [$row['Name'], $row['Phone'], $row['Parent Phone'], $row['Groups'], $row['Registration Date'], $row['Status']]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import students from CSV
     */
    public function importStudents(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
            'course_id' => 'required|exists:courses,id'
        ]);

        $instructor = $this->instructor;
        $course = \App\Models\Course::findOrFail($request->course_id);

        if ($course->instructor_id !== $instructor->id) {
            return back()->with('error', __('instructor::messages.unauthorized'));
        }

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip header
        fgetcsv($handle);

        $imported = 0;
        $errors = 0;

        while (($data = fgetcsv($handle)) !== FALSE) {
            try {
                // simple mapping: 0: name, 1: phone, 2: parent_phone
                $name = $data[0] ?? null;
                $phone = $data[1] ?? null;
                $parent_phone = $data[2] ?? null;

                if (!$name || !$phone) continue;

                $student = Student::firstOrCreate(
                    ['phone' => $phone, 'tenant_id' => $instructor->tenant_id],
                    [
                        'name' => $name,
                        'parent_phone' => $parent_phone,
                    ]
                );

                // Enroll student
                \App\Models\Enrollment::firstOrCreate([
                    'user_id' => $student->user_id ?: $this->getOrCreateUserForStudent($student),
                    'course_id' => $course->id,
                    'tenant_id' => $instructor->tenant_id,
                ]);

                $imported++;
            } catch (\Exception $e) {
                $errors++;
            }
        }
        fclose($handle);

        return back()->with('success', __('instructor::messages.import_success', ['count' => $imported]) . ($errors ? " " . __('instructor::messages.import_errors', ['count' => $errors]) : ""));
    }

    private function getOrCreateUserForStudent($student)
    {
        if ($student->user_id) return $student->user_id;
        
        // Check if user exists by phone
        $user = \App\Models\User::where('phone', $student->phone)->first();
        if (!$user) {
            $user = \App\Models\User::create([
                'name' => $student->name,
                'phone' => $student->phone,
                'email' => $student->phone . '@edu.com',
                'password' => bcrypt('password'), // temporary
                'role' => 'student',
                'tenant_id' => $student->tenant_id,
            ]);
        }
        
        $student->update(['user_id' => $user->id]);
        return $user->id;
    }

    /**
     * Toggle student status (Active/Frozen)
     */
    public function toggleStudentStatus(Student $student)
    {
        $this->authorizeInstructor($student);

        $student->update([
            'status' => $student->status === 'active' ? 'frozen' : 'active'
        ]);

        return back()->with('success', __('instructor::messages.updated'));
    }

    /**
     * Update student private notes
     */
    public function updateStudentNotes(Request $request, Student $student)
    {
        $this->authorizeInstructor($student);

        $student->update([
            'notes' => $request->notes
        ]);

        return back()->with('success', __('instructor::messages.saved'));
    }

    /**
     * Transfer student between groups
     */
    public function transferStudent(Request $request, Student $student)
    {
        $request->validate([
            'from_course_id' => 'required|exists:courses,id',
            'to_course_id' => 'required|exists:courses,id',
        ]);

        $this->authorizeInstructor($student);

        // Update enrollment
        \App\Models\Enrollment::where('user_id', $student->user_id)
            ->where('course_id', $request->from_course_id)
            ->update(['course_id' => $request->to_course_id]);

        return back()->with('success', __('instructor::messages.updated'));
    }

    private function authorizeInstructor($student)
    {
        $instructor = $this->instructor;
        $isRelated = $student->enrollments()->whereIn('course_id', $instructor->courses->pluck('id'))->exists();
        
        if (!$isRelated) {
            abort(403, __('instructor::messages.unauthorized'));
        }
    }

    /**
     * Show the form for manually creating a student
     */
    public function createStudent()
    {
        $instructor = $this->instructor;
        $courses = $instructor ? $instructor->courses : Course::all();

        // Enforce group-first: redirect to create a group if none exist
        if ($courses->isEmpty()) {
            return redirect()->route('instructor.groups.create')
                ->with('info', __('instructor::messages.create_group_first'));
        }
        
        return view('instructor::students.create', compact('courses'));
    }

    /**
     * Store a manually created student and enroll them
     */
    public function storeStudent(Request $request)
    {
        $instructor = $this->instructor;
        if (!$instructor) {
            return back()->with('error', __('instructor::messages.not_instructor_error'));
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|digits:11',
            'parent_phone' => 'required|string|digits:11',
            'parent_email' => 'nullable|email|max:255',
            'email' => 'nullable|email|max:255',
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,id',
        ]);

        try {
            \DB::beginTransaction();

            // 1. Check if user already exists by phone
            $user = \App\Models\User::where('phone', $validated['phone'])->first();

            if (!$user) {
                $email = $validated['email'] ?: ($validated['phone'] . '@' . ($this->tenant->domain ?? 'taalimu') . '.com');
                
                // Safety check for generated email collisions
                if (\App\Models\User::where('email', $email)->exists() && !$validated['email']) {
                    $email = $validated['phone'] . '_' . \Illuminate\Support\Str::random(4) . '@' . ($this->tenant->domain ?? 'taalimu') . '.com';
                }

                $user = \App\Models\User::create([
                    'tenant_id' => $instructor->tenant_id,
                    'name' => $validated['name'],
                    'email' => $email,
                    'phone' => $validated['phone'],
                    'password' => \Illuminate\Support\Facades\Hash::make($validated['phone']),
                    'role' => 'student',
                    'qr_identifier' => \Illuminate\Support\Str::random(12),
                ]);
                $user->assignRole('student');

                Student::create([
                    'tenant_id' => $instructor->tenant_id,
                    'user_id' => $user->id,
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'parent_phone' => $validated['parent_phone'],
                    'parent_email' => $validated['parent_email'],
                    'status' => 'active',
                ]);
            }

            // 2. Enroll in courses
            $newEnrollments = [];
            foreach ($validated['course_ids'] as $course_id) {
                $isEnrolled = Enrollment::where('user_id', $user->id)
                    ->where('course_id', $course_id)
                    ->exists();
    
                if (!$isEnrolled) {
                    Enrollment::create([
                        'tenant_id' => $instructor->tenant_id,
                        'user_id' => $user->id,
                        'course_id' => $course_id,
                        'status' => 'active',
                        'enrolled_at' => now(),
                    ]);
                    $newEnrollments[] = $course_id;
                }
            }

            \DB::commit();

            // Send emails (only if real email is provided)
            try {
                $student = Student::where('user_id', $user->id)->first();
                $hasValidStudentEmail = $validated['email'] && !preg_match('/^std\d+\..+@taalimu\.com$/', $validated['email']);
                $hasValidParentEmail = !empty($validated['parent_email']);

                if ($student && ($hasValidStudentEmail || $hasValidParentEmail)) {
                    $tenant = $this->tenant;
                    $tenantSettings = $tenant->settings['email_templates'] ?? [];
                    $defaultPresetKey = config('email_templates.default_preset', 'formal');
                    $defaultPreset = config("email_templates.presets.{$defaultPresetKey}", []);

                    $variables = [
                        'اسم_الطالب'    => $student->name,
                        'اسم_المركز'    => $tenant->name,
                        'رابط_الدخول'   => url('/login'),
                        'كلمة_المرور'   => $validated['phone'],
                        'رقم_الهاتف'    => $student->phone ?? '',
                        'اسم_ولي_الأمر' => '',
                        'المرحلة'       => '',
                    ];

                    $studentEnabled = (bool) ($tenantSettings['welcome_student_enabled'] ?? true);
                    if ($studentEnabled && $hasValidStudentEmail) {
                        $subject = $tenantSettings['welcome_student_subject'] ?? $defaultPreset['student_subject'] ?? '';
                        $body = $tenantSettings['welcome_student_body'] ?? $defaultPreset['student_body'] ?? '';
                        Mail::to($validated['email'])->queue(new WelcomeStudentMail(
                            $student, $subject, $body, $variables, $tenant->name
                        ));
                    }

                    // Send welcome email to guardian (if enabled and real email provided)
                    $guardianEnabled = (bool) ($tenantSettings['welcome_guardian_enabled'] ?? true);
                    if ($guardianEnabled && $hasValidParentEmail) {
                        $guardianSubject = $tenantSettings['welcome_guardian_subject'] ?? $defaultPreset['guardian_subject'] ?? '';
                        $guardianBody = $tenantSettings['welcome_guardian_body'] ?? $defaultPreset['guardian_body'] ?? '';
                        
                        Mail::to($validated['parent_email'])->queue(new \App\Mail\WelcomeGuardianMail(
                            '', // guardian name if available
                            $student->name,
                            $guardianSubject,
                            $guardianBody,
                            $variables,
                            $tenant->name
                        ));
                    }
                    
                    // Send Group Enrollment Notification (if enabled and there are new enrollments)
                    $groupEnrollmentEnabled = (bool) ($tenantSettings['notif_group_enrollment_enabled'] ?? false);
                    if ($groupEnrollmentEnabled && count($newEnrollments) > 0) {
                        foreach ($newEnrollments as $course_id) {
                            $course = \App\Models\Course::find($course_id);
                            $groupVariables = [
                                'اسم_الطالب' => $student->name,
                                'اسم_المركز' => $tenant->name,
                                'اسم_المجموعة' => $course ? $course->title : '',
                                'سعر_الدورة' => $course ? ($course->price . ' ج.م') : '',
                                'رابط_الدخول' => url('/login'),
                            ];
                            
                            $groupSubject = $tenantSettings['notif_group_enrollment_subject'] ?? 'تم تسجيلك في مجموعة جديدة';
                            $groupBody = $tenantSettings['notif_group_enrollment_body'] ?? '';
                            
                            if ($hasValidStudentEmail) {
                                Mail::to($validated['email'])->queue(new \App\Mail\NotifGroupEnrollmentMail(
                                    $groupSubject, $groupBody, $groupVariables, $tenant->name, $student->name
                                ));
                            }
                            
                            if ($hasValidParentEmail) {
                                Mail::to($validated['parent_email'])->queue(new \App\Mail\NotifGroupEnrollmentMail(
                                    $groupSubject, $groupBody, $groupVariables, $tenant->name, $student->name
                                ));
                            }
                        }
                    }
                }
            } catch (\Exception $mailEx) {
                Log::error('Instructor welcome/enrollment email failed: ' . $mailEx->getMessage());
            }

            return redirect()->route('instructor.students.list')->with('success', __('instructor::messages.student_added', ['name' => $validated['name']]));
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Manual student registration failed: ' . $e->getMessage());
            return back()->withInput()->with('error', __('instructor::messages.error_adding_student', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroyStudent(Student $student)
    {
        $instructor = $this->instructor;
        
        // Ownership check: If instructor exists, verify student is in one of their courses
        if ($instructor) {
            $isAssociated = Enrollment::where('user_id', $student->user_id)
                ->whereIn('course_id', $instructor->courses->pluck('id'))
                ->exists();
            
            if (!$isAssociated) {
                abort(403, __('instructor::messages.unauthorized'));
            }
        }

        try {
            \DB::beginTransaction();
            
            $studentName = $student->name;
            $userId = $student->user_id;

            // Delete Enrollments first
            Enrollment::where('user_id', $userId)->delete();
            
            // Delete Attendance records
            \Modules\Center\Models\Attendance::where('student_id', $student->id)->delete();

            // Delete Sales and Payments if they are linked to this student
            // CAUTION: Some systems prefer keeping financial records. 
            // For now, we delete to keep it simple as requested by "Delete Student".
            Payment::whereHas('sale', function($q) use ($student) {
                $q->where('student_id', $student->id);
            })->delete();
            Sale::where('student_id', $student->id)->delete();

            // Delete Student record
            $student->delete();

            // Delete User record if it's only a student (check roles if needed)
            $user = \App\Models\User::find($userId);
            if ($user && $user->roles()->count() <= 1) { // Only has student role
                $user->delete();
            }

            \DB::commit();
            return redirect()->route('instructor.students.list')->with('success', __('instructor::messages.student_deleted', ['name' => $studentName]));
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Student deletion failed: ' . $e->getMessage());
            return back()->with('error', __('instructor::messages.error_deleting_student', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Display a list of groups (courses) for the instructor
     */
    public function groups()
    {
        $instructor = $this->instructor;

        if (!$instructor) {
            $courses = Course::withCount('enrollments')->with('schedules')->get();
        } else {
            $courses = $instructor->courses()->withCount('enrollments')->with('schedules')->get();
        }

        return view('instructor::groups.index', compact('courses'));
    }

    /**
     * Show the form for creating a new group
     */
    public function createGroup()
    {
        return view('instructor::groups.create');
    }

    /**
     * Store a newly created group in storage
     */
    public function storeGroup(Request $request)
    {
        $instructor = $this->instructor;
        
        if (!$instructor) {
            return back()->with('error', __('instructor::messages.not_instructor_error'));
        }

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'sessions_count' => 'required|integer|min:1',
            ]);

            \Log::info('Attempting to create course for instructor: ' . $instructor->id, [
                'validated' => $validated,
                'tenant_bound' => app()->bound('tenant'),
                'current_tenant_id' => app()->bound('tenant') ? $this->tenant->id : 'none',
                'instructor_tenant_id' => $instructor->tenant_id
            ]);

            $course = Course::create([
                'tenant_id' => $instructor->tenant_id,
                'instructor_id' => $instructor->id,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? '',
                'price' => $validated['price'],
                'sessions_count' => $validated['sessions_count'],
                'status' => 'active',
            ]);

            \Log::info('Course created successfully: ' . $course->id);

            return redirect()->route('instructor.groups.list')->with('success', __('instructor::messages.group_created', ['title' => $course->title]));
        } catch (\Exception $e) {
            \Log::error('Failed to create course: ' . $e->getMessage(), [
                'instructor_id' => $instructor->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', __('instructor::messages.error_saving_group', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Show the form for editing the specified group
     */
    public function editGroup(Course $course)
    {
        $instructor = $this->instructor;
        $this->authorizeCourse($course);

        return view('instructor::groups.edit', compact('course'));
    }

    /**
     * Update the specified group in storage
     */
    public function updateGroup(Request $request, Course $course)
    {
        $instructor = $this->instructor;
        $this->authorizeCourse($course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sessions_count' => 'required|integer|min:1',
        ]);

        $course->update($validated);

        return redirect()->route('instructor.groups.list')->with('success', __('instructor::messages.group_updated', ['title' => $course->title]));
    }

    /**
     * Quickly mark a student as paid for a specific amount
     */
    public function markPaid(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $student = Student::with(['enrollments.course', 'sales'])->findOrFail($request->student_id);

        // Calculate balance
        $totalDue = $student->enrollments->sum(function($enrollment) {
            return $enrollment->course->price ?? 0;
        });
        $totalPaid = $student->sales->sum('paid_amount');
        $balance = $totalDue - $totalPaid;

        if ($request->amount > $balance) {
            return back()->with('error', __('instructor::messages.collection_error', ['amount' => $request->amount, 'balance' => $balance]));
        }

        $sale = Sale::create([
            'tenant_id' => $student->tenant_id,
            'student_id' => $student->id,
            'total_amount' => $request->amount,
            'paid_amount' => $request->amount,
            'status' => 'paid',
            'payment_method' => 'cash',
            'notes' => $request->notes ?? __('instructor::messages.quick_collection_note'),
        ]);

        Payment::create([
            'tenant_id' => $student->tenant_id,
            'sale_id' => $sale->id,
            'amount' => $request->amount,
            'payment_method' => 'cash',
            'received_by' => auth()->id(),
            'paid_at' => now(),
        ]);

        // Send Payment Confirmation Email
        try {
            $tenant = $this->tenant;
            $tenantSettings = $tenant->settings['email_templates'] ?? [];
            
            // Determine real email
            $realEmail = null;
            $studentEmail = $student->email ?? ($student->user ? $student->user->email : null);
            if ($studentEmail && !preg_match('/^std\d+\..+@taalimu\.com$/', $studentEmail)) {
                $realEmail = $studentEmail;
            }

            if (!empty($tenantSettings['notif_payment_confirmed_enabled']) && ($realEmail || $student->parent_email)) {
                $subject = $tenantSettings['notif_payment_confirmed_subject'] ?? 'تأكيد استلام دفعة';
                $body = $tenantSettings['notif_payment_confirmed_body'] ?? '';
                
                $variables = [
                    'اسم_الطالب' => $student->name,
                    'اسم_المركز' => $tenant->name,
                    'المبلغ_المدفوع' => $request->amount . ' ج.م',
                    'تاريخ_الدفع' => now()->format('Y-m-d'),
                    'المتبقي' => max(0, $balance - $request->amount) . ' ج.م',
                    'طريقة_الدفع' => 'نقدي',
                ];

                if ($realEmail) {
                    \Illuminate\Support\Facades\Mail::to($realEmail)->queue(new \App\Mail\NotifPaymentConfirmedMail(
                        $subject, $body, $variables, $tenant->name, $student->name
                    ));
                }
                
                // Also send to parent if email exists
                if ($student->parent_email) {
                    \Illuminate\Support\Facades\Mail::to($student->parent_email)->queue(new \App\Mail\NotifPaymentConfirmedMail(
                        $subject, $body, $variables, $tenant->name, $student->name
                    ));
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Payment confirmation email failed: ' . $e->getMessage());
        }

        return back()->with('success', __('instructor::messages.collection_success', ['amount' => $request->amount, 'student' => $student->name]));
    }

    /**
     * Rotate the registration link for a group
     */
    public function rotateGroupLink(Course $course)
    {
        $instructor = $this->instructor;
        $this->authorizeCourse($course);

        $course->update([
            'registration_token' => \Illuminate\Support\Str::random(16)
        ]);

        return back()->with('success', __('instructor::messages.link_rotated', ['title' => $course->title]));
    }

    /**
     * Duplicate a group
     */
    public function duplicateGroup(Course $course)
    {
        $instructor = $this->instructor;
        $this->authorizeCourse($course);

        $newCourse = $course->replicate();
        $newCourse->title = $course->title . __('instructor::messages.copy_suffix');
        $newCourse->registration_token = \Illuminate\Support\Str::random(16);
        $newCourse->save();

        return redirect()->route('instructor.groups.list')->with('success', __('instructor::messages.group_duplicated', ['title' => $newCourse->title]));
    }

    /**
     * Remove the specified group from storage (Soft Delete)
     */
    public function destroyGroup(Course $course)
    {
        $instructor = $this->instructor;
        $this->authorizeCourse($course);

        $course->delete();

        return redirect()->route('instructor.groups.list')->with('success', __('instructor::messages.group_deleted', ['title' => $course->title]));
    }

    /**
     * Show detailed profile for a student
     */
    public function showStudent(Student $student)
    {
        $instructor = $this->instructor;
        
        // Ensure student is enrolled in at least one of instructor's courses
        if ($instructor) {
            $isEnrolled = Enrollment::where('user_id', $student->user_id)
                ->whereIn('course_id', $instructor->courses->pluck('id'))
                ->exists();
            if (!$isEnrolled) {
                abort(403);
            }
        }

        $student->load(['user', 'enrollments.course', 'sales' => function($q) {
            $q->latest();
        }]);

        // Get attendance for this student in instructor's courses
        $attendanceQuery = Attendance::where('student_id', $student->id)
            ->with(['course', 'schedule'])
            ->latest();

        if ($instructor) {
            $attendanceQuery->whereIn('course_id', $instructor->courses->pluck('id'));
        }

        $attendances = $attendanceQuery->get();

        return view('instructor::students.show', compact('student', 'attendances'));
    }

    /**
     * Send an email to the student
     */
    public function sendEmail(Request $request, Student $student)
    {
        $instructor = $this->instructor;
        
        // Exclude unauthorized
        if ($instructor) {
            $isEnrolled = Enrollment::where('user_id', $student->user_id)
                ->whereIn('course_id', $instructor->courses->pluck('id'))
                ->exists();
            if (!$isEnrolled) {
                abort(403);
            }
        }

        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $email = $student->email ?: ($student->user ? $student->user->email : null);

        if (!$email) {
            return back()->with('error', 'هذا الطالب لا يمتلك بريداً إلكترونياً مسجلاً.');
        }

        try {
            $senderName = $instructor ? $instructor->name : $this->tenant->name;
            \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\CustomStudentMail(
                $student, 
                $request->subject, 
                $request->message,
                $senderName
            ));
            
            return back()->with('success', 'تم إرسال البريد الإلكتروني للطالب بنجاح.');
        } catch (\Exception $e) {
            \Log::error("Failed to send email to student {$student->id}: " . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء الإرسال: ' . $e->getMessage());
        }
    }

    // ─── Schedule Management ───

    public function schedules()
    {
        $instructor = $this->instructor;
        $query = Schedule::with(['course', 'classroom', 'instructor', 'bookings'])->latest();
        
        if ($instructor) {
            $query->where('instructor_id', $instructor->id);
        }

        $schedules = $query->get();
        return view('instructor::schedules.index', compact('schedules'));
    }

    public function createSchedule()
    {
        $instructor = $this->instructor;
        $courses = $instructor ? $instructor->courses()->select('id', 'title', 'instructor_id')->get() : Course::select('id', 'title', 'instructor_id')->get();
        $classrooms = \App\Models\Classroom::select('id', 'name', 'capacity')->get();
        
        return view('instructor::schedules.create', compact('courses', 'classrooms'));
    }

    public function storeSchedule(Request $request)
    {
        $instructor = $this->instructor;
        if (!$instructor) {
            return back()->with('error', __('instructor::messages.not_instructor_error'));
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'location' => 'nullable|string|max:255',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'max_students' => 'nullable|integer|min:1',
        ]);

        $validated['instructor_id'] = $instructor->id;

        // Verify course belongs to instructor
        $course = Course::findOrFail($validated['course_id']);
        if ($course->instructor_id !== $instructor->id) {
            return back()->withInput()->with('error', __('instructor::messages.unauthorized_course'));
        }

        // Conflict Detection
        $conflictQuery = Schedule::where('day_of_week', $validated['day_of_week'])
            ->where('start_time', '<', $validated['end_time'])
            ->where('end_time', '>', $validated['start_time']);

        // Check Instructor Conflict
        $instructorConflict = clone $conflictQuery;
        if ($instructorConflict->where('instructor_id', $instructor->id)->exists()) {
            return back()->withInput()->with('error', __('instructor::messages.instructor_conflict'));
        }

        // Check Classroom Conflict (if classroom is selected)
        if (!empty($validated['classroom_id'])) {
            $classroomConflict = clone $conflictQuery;
            if ($classroomConflict->where('classroom_id', $validated['classroom_id'])->exists()) {
                return back()->withInput()->with('error', __('instructor::messages.hall_conflict'));
            }
        }

        Schedule::create($validated);

        return redirect()->route('instructor.schedules.index')
            ->with('success', __('instructor::messages.schedule_created'));
    }

    public function storeClassroom(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $classroom = \App\Models\Classroom::create([
            'tenant_id' => $this->tenant->id,
            'name' => $request->name,
            'capacity' => $request->capacity,
            'is_active' => true,
        ]);

        if ($request->ajax()) {
            $classrooms = \App\Models\Classroom::select('id', 'name', 'capacity')->get();
            return response()->json([
                'success' => true,
                'classrooms' => $classrooms,
                'new_id' => $classroom->id
            ]);
        }

        return back()->with('success', __('instructor::messages.saved'));
    }

    public function editSchedule(Schedule $schedule)
    {
        $instructor = $this->instructor;
        $this->authorizeSchedule($schedule);

        $courses = $instructor ? $instructor->courses()->select('id', 'title', 'instructor_id')->get() : Course::select('id', 'title', 'instructor_id')->get();
        $classrooms = \App\Models\Classroom::select('id', 'name', 'capacity')->get();

        return view('instructor::schedules.edit', compact('schedule', 'courses', 'classrooms'));
    }

    public function updateSchedule(Request $request, Schedule $schedule)
    {
        $instructor = $this->instructor;
        $this->authorizeSchedule($schedule);

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'location' => 'nullable|string|max:255',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'max_students' => 'nullable|integer|min:1',
        ]);

        $validated['instructor_id'] = $instructor->id;

        // Conflict Detection (excluding the current schedule)
        $conflictQuery = Schedule::where('id', '!=', $schedule->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where('start_time', '<', $validated['end_time'])
            ->where('end_time', '>', $validated['start_time']);

        // Check Instructor Conflict
        $instructorConflict = clone $conflictQuery;
        if ($instructorConflict->where('instructor_id', $instructor->id)->exists()) {
            return back()->withInput()->with('error', __('instructor::messages.instructor_conflict'));
        }

        // Check Classroom Conflict (if classroom is selected)
        if (!empty($validated['classroom_id'])) {
            $classroomConflict = clone $conflictQuery;
            if ($classroomConflict->where('classroom_id', $validated['classroom_id'])->exists()) {
                return back()->withInput()->with('error', __('instructor::messages.hall_conflict'));
            }
        }

        $schedule->update($validated);

        return redirect()->route('instructor.schedules.index')
            ->with('success', __('instructor::messages.schedule_updated'));
    }

    public function destroySchedule(Schedule $schedule)
    {
        $instructor = $this->instructor;
        $this->authorizeSchedule($schedule);

        $schedule->delete();

        return redirect()->route('instructor.schedules.index')
            ->with('success', __('instructor::messages.schedule_deleted'));
    }

    // ─── Attendance ───

    public function attendance()
    {
        $instructor = $this->instructor;
        $dayOfWeek = now()->dayOfWeek;

        $query = Schedule::with(['course', 'classroom', 'instructor'])
            ->where('day_of_week', $dayOfWeek)
            ->whereNotNull('course_id');

        if ($instructor) {
            $query->where('instructor_id', $instructor->id);
        }

        $todaySessions = $query->orderBy('start_time')->get()
            ->unique(fn ($s) => $s->course_id . '-' . $s->start_time . '-' . $s->end_time);

        $todaySessions = new \Illuminate\Pagination\LengthAwarePaginator(
            $todaySessions->forPage(request()->get('page', 1), 10),
            $todaySessions->count(),
            10,
            request()->get('page', 1),
            ['path' => request()->url()]
        );

        $attendanceQuery = \Modules\Center\Models\Attendance::with(['student', 'course', 'schedule'])->latest();

        if ($instructor) {
            $attendanceQuery->whereHas('course', fn($q) => $q->where('instructor_id', $instructor->id));
        }

        $recentAttendance = $attendanceQuery->take(10)->get();

        return view('instructor::attendance.index', compact('todaySessions', 'recentAttendance'));
    }

    public function attendanceShow(Schedule $schedule)
    {
        $instructor = $this->instructor;
        $this->authorizeSchedule($schedule);

        $schedule->load('course.enrollments.user.student', 'classroom');
        
        $attendances = Attendance::where('schedule_id', $schedule->id)
            ->whereDate('session_date', today())
            ->get()
            ->keyBy('student_id');

        return view('instructor::attendance.show', compact('schedule', 'attendances'));
    }

    public function storeAttendance(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer',
            'course_id' => 'required|integer',
            'schedule_id' => 'required|integer',
            'status' => 'required|in:present,late,absent',
            'session_date' => 'required|date',
        ]);

        $instructor = $this->instructor;
        $schedule = Schedule::findOrFail($validated['schedule_id']);
        
        $this->authorizeSchedule($schedule);

        $attendanceService = app(AttendanceService::class);
        $attendanceService->markAttendance($validated);

        return back()->with('success', __('instructor::messages.saved'));
    }

    public function bulkAbsent(Schedule $schedule)
    {
        $instructor = $this->instructor;
        $this->authorizeSchedule($schedule);

        $enrolledIds = $schedule->course->enrollments()
            ->with('user.student')
            ->get()
            ->pluck('user.student.id')
            ->filter();

        $attendedIds = Attendance::where('schedule_id', $schedule->id)
            ->whereDate('session_date', today())
            ->pluck('student_id')
            ->toArray();

        $absentIds = $enrolledIds->diff($attendedIds);

        foreach ($absentIds as $studentId) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'schedule_id' => $schedule->id,
                    'session_date' => today(),
                ],
                [
                    'tenant_id' => $this->tenant->id,
                    'course_id' => $schedule->course_id,
                    'status' => 'absent',
                    'check_in_time' => now(),
                ]
            );
        }

        return back()->with('success', __('instructor::messages.saved'));
    }

    public function studentReports()
    {
        $instructor = $this->instructor;
        $courseIds = $instructor ? $instructor->courses->pluck('id') : Course::pluck('id');

        $students = Student::whereHas('enrollments', function($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds);
        })->with(['enrollments' => function($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds)->with('course');
        }])->get();

        foreach ($students as $student) {
            $totalSessions = $student->enrollments->sum('course.sessions_count');
            $attendedSessions = Attendance::where('student_id', $student->id)
                ->whereIn('course_id', $courseIds)
                ->where('status', 'present')
                ->count();
            
            $student->attendance_percentage = $totalSessions > 0 ? round(($attendedSessions / $totalSessions) * 100) : 0;
            $student->attended_count = $attendedSessions;
            $student->total_sessions = $totalSessions;
        }

        return view('instructor::reports.students', compact('students'));
    }

    public function paymentReports()
    {
        $instructor = $this->instructor;
        $courseIds = $instructor ? $instructor->courses->pluck('id') : Course::pluck('id');

        // Get ALL students enrolled in instructor's courses with their financial data
        $students = Student::whereHas('enrollments', function($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds);
        })->with(['enrollments' => function($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds)->with('course');
        }, 'sales'])->get();

        foreach ($students as $student) {
            $student->total_due = $student->enrollments->sum(function($e) { return $e->course->price ?? 0; });
            $student->total_paid = $student->sales->sum('paid_amount');
            $student->balance = $student->total_due - $student->total_paid;
            $student->financial_status = $student->balance <= 0 ? 'paid' : ($student->total_paid > 0 ? 'partial' : 'unpaid');
        }

        // Summary stats
        $totalDue = $students->sum('total_due');
        $totalPaid = $students->sum('total_paid');
        $totalBalance = $students->sum('balance');

        return view('instructor::reports.payments', compact('students', 'totalDue', 'totalPaid', 'totalBalance'));
    }

    /**
     * Helper to resolve the instructor profile for the current user,
     * creating it if it doesn't exist for authorized users.
     */
    protected function resolveInstructor()
    {
        $user = auth()->user();
        if (!$user) return null;

        $instructor = $user->instructor;

        if (!$instructor) {
            // Check if the user is authorized to have an instructor profile
            // (e.g., they have the 'instructor' or 'center_admin' role)
            if ($user->hasRole(['instructor', 'center_admin'])) {
                $instructor = \App\Models\Instructor::create([
                    'tenant_id' => $user->tenant_id,
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'status' => 'active',
                ]);
                \Log::info("Auto-created instructor profile for user: {$user->id}");
            }
        }

        return $instructor;
    }

    /**
     * Check if a phone number already exists in the system (AJAX)
     */
    public function checkPhone(Request $request)
    {
        $phone = $request->get('phone');
        if (!$phone || strlen($phone) < 11) {
            return response()->json(['status' => 'invalid']);
        }

        $user = \App\Models\User::where('phone', $phone)->first();

        if ($user) {
            return response()->json([
                'status' => 'exists',
                'name' => auth()->check() ? $user->name : null, // Privacy safeguard
                'role' => $user->role
            ]);
        }

        return response()->json(['status' => 'available']);
    }

    /**
     * Display the consolidated settings dashboard
     */
    public function settings()
    {
        // One-time price correction logic - Fixes Monthly/Term price swap
        try {
            $basic = \App\Models\Package::where('slug', 'basic')->first();
            if ($basic && ($basic->price > 1000 || $basic->term_price === null)) { 
                $updates = [
                    'basic' => [
                        'price' => 450, 'term_price' => 1450, 'yearly_price' => 2500,
                        'regional_prices' => [
                            'EG' => ['amount' => 450, 'currency' => 'EGP', 'term_price' => 1450, 'yearly_price' => 2500],
                            'default' => ['amount' => 15, 'currency' => 'USD', 'term_price' => 49, 'yearly_price' => 85]
                        ]
                    ],
                    'pro' => [
                        'price' => 950, 'term_price' => 3450, 'yearly_price' => 6000,
                        'regional_prices' => [
                            'EG' => ['amount' => 950, 'currency' => 'EGP', 'term_price' => 3450, 'yearly_price' => 6000],
                            'default' => ['amount' => 30, 'currency' => 'USD', 'term_price' => 99, 'yearly_price' => 170]
                        ]
                    ],
                    'enterprise' => [
                        'price' => 1950, 'term_price' => 6950, 'yearly_price' => 12000,
                        'regional_prices' => [
                            'EG' => ['amount' => 1950, 'currency' => 'EGP', 'term_price' => 6950, 'yearly_price' => 12000],
                            'default' => ['amount' => 60, 'currency' => 'USD', 'term_price' => 199, 'yearly_price' => 340]
                        ]
                    ],
                ];

                foreach ($updates as $slug => $data) {
                    \App\Models\Package::where('slug', $slug)->update($data);
                }
                \Illuminate\Support\Facades\Cache::forget('subscription_packages_full');
            }
        } catch (\Exception $e) {
            \Log::error('Settings Price Fix Failed: ' . $e->getMessage());
        }

        $tenant = $this->tenant;
        $settings = $tenant->settings['whatsapp'] ?? [];
        $packages = \App\Models\Package::with('features')->where('is_active', true)->orderBy('sort_order')->get();
        return view('instructor::settings', compact('tenant', 'settings', 'packages'));
    }

    /**
     * Update general center information
     */
    public function updateGeneralSettings(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'currency' => 'nullable|string|max:10',
            'logo' => 'nullable|image|max:2048'
        ]);

        $tenant = \App\Models\Tenant::findOrFail($this->tenant->id);
        
        $tenant->name = $request->name;
        $tenant->phone = $request->phone;
        $tenant->address = $request->address;
        $tenant->description = $request->description;

        $settings = $tenant->settings ?? [];
        $settings['currency'] = $request->currency ?? 'EGP';
        $tenant->settings = $settings;

        if ($request->hasFile('logo')) {
            $tenant->logo = $request->file('logo')->store("{$tenant->id}/logos", 'public');
        }

        $tenant->save();

        return redirect()->route('instructor.settings')->with('success', __('instructor::settings.update_success'));
    }

    /**
     * Show WhatsApp (UltraMsg) settings page
     */
    public function whatsappSettings()
    {
        $tenant = $this->tenant;
        $settings = $tenant->settings['whatsapp'] ?? [
            'enabled' => false,
            'instance_id' => '',
            'token' => ''
        ];
        
        return view('instructor::whatsapp', compact('settings'));
    }

    /**
     * Update WhatsApp settings
     */
    public function updateWhatsAppSettings(Request $request)
    {
        $request->validate([
            'phone_number_id' => 'required|string',
            'access_token' => 'required|string',
            'waba_id' => 'nullable|string',
            'api_version' => 'nullable|string',
            'country_code' => 'required|string',
            'attendance_template' => 'nullable|string',
            'payment_template' => 'nullable|string',
            'debt_template' => 'nullable|string'
        ]);

        $tenant = \App\Models\Tenant::findOrFail($this->tenant->id);
        $settings = $tenant->settings ?? [];
        
        $settings['whatsapp'] = [
            'enabled' => $request->has('enabled'),
            'phone_number_id' => $request->phone_number_id,
            'access_token' => $request->access_token,
            'waba_id' => $request->waba_id,
            'api_version' => $request->api_version ?: 'v21.0',
            'country_code' => $request->country_code,
            'attendance_template' => $request->attendance_template,
            'payment_template' => $request->payment_template,
            'debt_template' => $request->debt_template
        ];

        $tenant->settings = $settings;
        $tenant->save();

        return back()->with('success', __('instructor::messages.saved'));
    }


    /**
     * Set the application locale for the session
     */
    public function setLocale($locale)
    {
        if (in_array($locale, ['ar', 'en', 'fr'])) {
            session(['locale' => $locale]);
            
            if (auth()->check()) {
                auth()->user()->update(['locale' => $locale]);
            }
        }
        
        return back();
    }

    /**
     * Update payment reminder settings for the tenant.
     */
    public function updateReminderSettings(Request $request)
    {
        $request->validate([
            'default_due_day' => 'required|integer|min:1|max:28',
            'default_monthly_fee' => 'nullable|numeric|min:0',
            'email_reminders' => 'array',
            'whatsapp_reminders' => 'array',
            'whatsapp_before_due' => 'boolean',
            'email_template' => 'nullable|string|max:2000',
            'whatsapp_template' => 'nullable|string|max:2000',
        ]);

        $tenant = \App\Models\Tenant::findOrFail($this->tenant->id);
        $settings = $tenant->settings ?? [];

        $settings['payment_reminders'] = [
            'default_due_day' => (int) $request->default_due_day,
            'default_monthly_fee' => $request->default_monthly_fee ? (float) $request->default_monthly_fee : null,
            'email_reminders' => collect($request->email_reminders)->map(function ($item) {
                return [
                    'days_before' => (int) $item['days_before'],
                    'enabled' => (bool) ($item['enabled'] ?? false),
                ];
            })->toArray(),
            'whatsapp_reminders' => collect($request->whatsapp_reminders)->map(function ($item) {
                return [
                    'days_after' => (int) $item['days_after'],
                    'enabled' => (bool) ($item['enabled'] ?? false),
                ];
            })->toArray(),
            'whatsapp_before_due' => (bool) $request->whatsapp_before_due,
            'email_template' => $request->email_template,
            'whatsapp_template' => $request->whatsapp_template,
        ];

        $tenant->settings = $settings;
        $tenant->save();

        return back()->with('success', __('instructor::reminders.saved'));
    }

    /**
     * Update student-specific payment settings (monthly fee + due day).
     */
    public function updateStudentPayment(Request $request, \App\Models\Student $student)
    {
        $request->validate([
            'monthly_fee' => 'nullable|numeric|min:0',
            'payment_due_day' => 'nullable|integer|min:1|max:28',
            'parent_email' => 'nullable|email|max:255',
        ]);

        $student->update([
            'monthly_fee' => $request->monthly_fee ?: null,
            'payment_due_day' => $request->payment_due_day ?: null,
            'parent_email' => $request->parent_email ?: null,
        ]);

        return back()->with('success', __('instructor::reminders.saved'));
    }

    /**
     * Update email template settings for welcome emails.
     * Saves to tenant settings JSON under 'email_templates' key.
     */
    public function updateEmailTemplateSettings(Request $request)
    {
        $request->validate([
            'welcome_student_enabled'  => 'required|boolean',
            'welcome_guardian_enabled' => 'required|boolean',
            'welcome_student_subject'  => 'nullable|string|max:500',
            'welcome_student_body'     => 'nullable|string|max:5000',
            'welcome_guardian_subject' => 'nullable|string|max:500',
            'welcome_guardian_body'    => 'nullable|string|max:5000',
            // Event-based notifications
            'notif_payment_reminder_enabled'   => 'required|boolean',
            'notif_payment_reminder_subject'   => 'nullable|string|max:500',
            'notif_payment_reminder_body'      => 'nullable|string|max:5000',
            'notif_group_enrollment_enabled'   => 'required|boolean',
            'notif_group_enrollment_subject'   => 'nullable|string|max:500',
            'notif_group_enrollment_body'      => 'nullable|string|max:5000',
            'notif_payment_confirmed_enabled'  => 'required|boolean',
            'notif_payment_confirmed_subject'  => 'nullable|string|max:500',
            'notif_payment_confirmed_body'     => 'nullable|string|max:5000',
        ]);

        $tenant = \App\Models\Tenant::findOrFail($this->tenant->id);
        $settings = $tenant->settings ?? [];

        $settings['email_templates'] = [
            // Welcome emails
            'welcome_student_enabled'  => (bool) $request->welcome_student_enabled,
            'welcome_guardian_enabled' => (bool) $request->welcome_guardian_enabled,
            'welcome_student_subject'  => $request->welcome_student_subject,
            'welcome_student_body'     => $request->welcome_student_body,
            'welcome_guardian_subject' => $request->welcome_guardian_subject,
            'welcome_guardian_body'    => $request->welcome_guardian_body,
            // Payment reminder
            'notif_payment_reminder_enabled' => (bool) $request->notif_payment_reminder_enabled,
            'notif_payment_reminder_subject' => $request->notif_payment_reminder_subject,
            'notif_payment_reminder_body'    => $request->notif_payment_reminder_body,
            // Group enrollment
            'notif_group_enrollment_enabled' => (bool) $request->notif_group_enrollment_enabled,
            'notif_group_enrollment_subject' => $request->notif_group_enrollment_subject,
            'notif_group_enrollment_body'    => $request->notif_group_enrollment_body,
            // Payment confirmation
            'notif_payment_confirmed_enabled' => (bool) $request->notif_payment_confirmed_enabled,
            'notif_payment_confirmed_subject' => $request->notif_payment_confirmed_subject,
            'notif_payment_confirmed_body'    => $request->notif_payment_confirmed_body,
        ];

        $tenant->settings = $settings;
        $tenant->save();

        return back()->with('success', 'تم حفظ إعدادات البريد الإلكتروني بنجاح');
    }

    public function resetEmailTemplateSettings(Request $request)
    {
        $tenant = \App\Models\Tenant::findOrFail($this->tenant->id);
        $settings = $tenant->settings ?? [];

        if (isset($settings['email_templates'])) {
            unset($settings['email_templates']);
        }

        $tenant->settings = $settings;
        $tenant->save();

        return back()->with('success', 'تم إعادة ضبط نصوص البريد الإلكتروني للوضع الافتراضي بنجاح');
    }
}

