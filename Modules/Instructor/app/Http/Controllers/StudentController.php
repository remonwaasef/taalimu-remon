<?php

namespace Modules\Instructor\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Sale;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeStudentMail;
use App\Mail\WelcomeGuardianMail;
use Modules\Instructor\Http\Requests\StoreStudentRequest;
use Modules\Instructor\Http\Controllers\Traits\ResolvesInstructor;

class StudentController extends Controller
{
    use ResolvesInstructor;

    /**
     * Display a list of students enrolled in instructor's courses
     */
    public function index()
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
                // Optionally filter sales if needed
            }])->get();
        }

        return view('instructor::students.index', compact('students'));
    }

    /**
     * Show the form for manually creating a student
     */
    public function create()
    {
        $instructor = $this->instructor;
        $courses = $instructor ? $instructor->courses : Course::select('id', 'title', 'price')->get();

        if ($courses->isEmpty()) {
            return redirect()->route('instructor.groups.create')
                ->with('info', __('instructor::messages.create_group_first'));
        }
        
        return view('instructor::students.create', compact('courses'));
    }

    /**
     * Store a manually created student and enroll them
     */
    public function store(StoreStudentRequest $request)
    {
        $instructor = $this->instructor;
        if (!$instructor) {
            return back()->with('error', __('instructor::messages.not_instructor_error'));
        }

        $validated = $request->validated();

        try {
            \DB::beginTransaction();

            if ($request->filled('student_id')) {
                $student = Student::findOrFail($request->student_id);
                $this->authorizeInstructor($student);
                $user = $student->user;
            } else {
                $user = \App\Models\User::where('phone', $validated['phone'])->first();

                if (!$user) {
                    $email = $validated['email'] ?: ($validated['phone'] . '@' . ($this->tenant->domain ?? 'taalimu') . '.com');
                    
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

                    $student = Student::forceCreate([
                        'tenant_id' => app('tenant')->id,
                        'user_id' => $user->id,
                        'name' => $validated['name'],
                        'phone' => $validated['phone'],
                        'parent_phone' => $validated['parent_phone'],
                        'parent_email' => $validated['parent_email'],
                        'status' => 'active',
                    ]);
                } else {
                    $student = $user->student;
                }
            }

            // Enroll in courses via FinanceService
            if (!empty($validated['course_ids'])) {
                $items = [];
                $courses = Course::whereIn('id', $validated['course_ids'])->get();
                foreach ($courses as $course) {
                    $items[] = ['id' => $course->id, 'price' => $course->price];
                }

                if (!empty($items)) {
                    app(\App\Services\FinanceService::class)->createSale([
                        'student_id' => $student->id,
                        'items' => $items,
                        'payment_method' => 'cash',
                        'paid_amount' => 0,
                    ]);
                }
                $newEnrollments = $validated['course_ids'];
            }

            \DB::commit();

            // Send emails
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

                    $guardianEnabled = (bool) ($tenantSettings['welcome_guardian_enabled'] ?? true);
                    if ($guardianEnabled && $hasValidParentEmail) {
                        $guardianSubject = $tenantSettings['welcome_guardian_subject'] ?? $defaultPreset['guardian_subject'] ?? '';
                        $guardianBody = $tenantSettings['welcome_guardian_body'] ?? $defaultPreset['guardian_body'] ?? '';
                        
                        Mail::to($validated['parent_email'])->queue(new WelcomeGuardianMail(
                            '',
                            $student->name,
                            $guardianSubject,
                            $guardianBody,
                            $variables,
                            $tenant->name
                        ));
                    }
                    
                    $groupEnrollmentEnabled = (bool) ($tenantSettings['notif_group_enrollment_enabled'] ?? false);
                    if ($groupEnrollmentEnabled && count($newEnrollments) > 0) {
                        foreach ($newEnrollments as $course_id) {
                            $course = Course::find($course_id);
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
            Log::error('Manual student registration failed: ' . $e->getMessage());
            return back()->withInput()->with('error', __('instructor::messages.error_adding_student', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Show detailed profile for a student
     */
    public function show(Student $student)
    {
        $instructor = $this->instructor;
        
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

        $attendanceQuery = \Modules\Center\Models\Attendance::where('student_id', $student->id)
            ->with(['course', 'schedule'])
            ->latest();

        if ($instructor) {
            $attendanceQuery->whereIn('course_id', $instructor->courses->pluck('id'));
        }

        $attendances = $attendanceQuery->get();

        return view('instructor::students.show', compact('student', 'attendances'));
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        $instructor = $this->instructor;
        
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

            Enrollment::where('user_id', $userId)->delete();
            \Modules\Center\Models\Attendance::where('student_id', $student->id)->delete();
            Payment::whereHas('sale', function($q) use ($student) {
                $q->where('student_id', $student->id);
            })->delete();
            Sale::where('student_id', $student->id)->delete();
            $student->delete();

            $user = \App\Models\User::find($userId);
            if ($user && $user->roles()->count() <= 1) {
                $user->delete();
            }

            \DB::commit();
            return redirect()->route('instructor.students.list')->with('success', __('instructor::messages.student_deleted', ['name' => $studentName]));
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Student deletion failed: ' . $e->getMessage());
            return back()->with('error', __('instructor::messages.error_deleting_student', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Toggle student status (Active/Frozen)
     */
    public function toggleStatus(Student $student)
    {
        $this->authorizeInstructor($student);
        $student->update(['status' => $student->status === 'active' ? 'frozen' : 'active']);
        return back()->with('success', __('instructor::messages.updated'));
    }

    /**
     * Update student private notes
     */
    public function updateNotes(Request $request, Student $student)
    {
        $this->authorizeInstructor($student);
        $student->update(['notes' => $request->notes]);
        return back()->with('success', __('instructor::messages.saved'));
    }

    /**
     * Transfer student between groups
     */
    public function transfer(Request $request, Student $student)
    {
        $request->validate([
            'from_course_id' => 'required|exists:courses,id',
            'to_course_id' => 'required|exists:courses,id',
        ]);

        $this->authorizeInstructor($student);
        Enrollment::where('user_id', $student->user_id)
            ->where('course_id', $request->from_course_id)
            ->update(['course_id' => $request->to_course_id]);

        return back()->with('success', __('instructor::messages.updated'));
    }

    /**
     * Export students to CSV
     */
    public function export()
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
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($students as $student) {
                fputcsv($file, [
                    $student->name,
                    $student->phone,
                    $student->parent_phone,
                    $student->enrollments->pluck('course.title')->implode(', '),
                    $student->created_at->format('Y-m-d'),
                    $student->status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import students from CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
            'course_id' => 'required|exists:courses,id'
        ]);

        $instructor = $this->instructor;
        $course = Course::findOrFail($request->course_id);

        if ($course->instructor_id !== $instructor->id) {
            return back()->with('error', __('instructor::messages.unauthorized'));
        }

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        fgetcsv($handle);

        $imported = 0;
        $errors = 0;

        while (($data = fgetcsv($handle)) !== FALSE) {
            try {
                $name = $data[0] ?? null;
                $phone = $data[1] ?? null;
                $parent_phone = $data[2] ?? null;

                if (!$name || !$phone) continue;

                $student = Student::firstOrCreate(
                    ['phone' => $phone, 'tenant_id' => $instructor->tenant_id],
                    ['name' => $name, 'parent_phone' => $parent_phone]
                );

                Enrollment::firstOrCreate([
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

    /**
     * Send an email to the student
     */
    public function sendEmail(Request $request, Student $student)
    {
        $instructor = $this->instructor;
        
        if ($instructor) {
            $isEnrolled = Enrollment::where('user_id', $student->user_id)
                ->whereIn('course_id', $instructor->courses->pluck('id'))
                ->exists();
            if (!$isEnrolled) abort(403);
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
            Mail::to($email)->queue(new \App\Mail\CustomStudentMail(
                $student, $request->subject, $request->message, $senderName
            ));
            return back()->with('success', 'تم إرسال البريد الإلكتروني للطالب بنجاح.');
        } catch (\Exception $e) {
            Log::error("Failed to send email to student {$student->id}: " . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء الإرسال: ' . $e->getMessage());
        }
    }

    /**
     * Check if a phone number already exists (AJAX)
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
                'name' => auth()->check() ? $user->name : null,
                'role' => $user->role
            ]);
        }

        return response()->json(['status' => 'available']);
    }

    /**
     * Update student-specific payment settings
     */
    public function updatePayment(Request $request, Student $student)
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

    private function getOrCreateUserForStudent($student)
    {
        if ($student->user_id) return $student->user_id;
        
        $user = \App\Models\User::where('phone', $student->phone)->first();
        if (!$user) {
            $user = \App\Models\User::create([
                'name' => $student->name,
                'phone' => $student->phone,
                'email' => $student->phone . '@edu.com',
                'password' => bcrypt(\Illuminate\Support\Str::random(12)),
                'role' => 'student',
                'tenant_id' => $student->tenant_id,
            ]);
        }
        
        $student->update(['user_id' => $user->id]);
        return $user->id;
    }
}
