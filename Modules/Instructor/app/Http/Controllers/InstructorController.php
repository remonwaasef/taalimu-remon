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

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        } catch (\Exception $e) {
            // Ignore errors if already migrated or other issues for now
        }

        $instructor = $this->resolveInstructor();
        
        if (!$instructor) {
            // Fallback for demo or admin
            $courses = Course::take(5)->get();
            $studentsCount = Student::count();
            $monthlyRevenue = Sale::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('paid_amount');
        } else {
            $courses = $instructor->courses()->withCount('enrollments')->get();
            $studentsCount = Student::whereHas('enrollments', function($q) use ($instructor) {
                $q->whereIn('course_id', $instructor->courses->pluck('id'));
            })->count();
            
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

        return view('instructor::index', compact('courses', 'studentsCount', 'monthlyRevenue', 'attendanceData', 'days'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('instructor::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('instructor::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('instructor::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
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
                'message' => 'كود الطالب غير صحيح أو غير مسجل النظام.'
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
                'message' => 'الطالب غير مسجل في هذه المجموعة!'
            ], 403);
        }

        // Mark Attendance using the existing service
        $attendanceService = app(AttendanceService::class);

        if ($attendanceService->hasAttendedToday($student->id, $request->schedule_id)) {
            return response()->json([
                'success' => true,
                'student_name' => $student->name,
                'already_marked' => true,
                'message' => 'تم تسجيل حضور الطالب مسبقاً.'
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

        $msg = "تحرك من المركز: الطالب {$student->name} حضر الآن حصة '{$course->title}' في مركز " . app('tenant')->name . ".";
        $phoneToNotify = $student->parent_phone ?: $student->phone;
        $whatsappUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $phoneToNotify) . "?text=" . urlencode($msg);

        return response()->json([
            'success' => true,
            'student_name' => $student->name,
            'remaining_sessions' => $enrollment->fresh()->remaining_sessions,
            'whatsapp_url' => $whatsappUrl,
            'message' => 'تم تسجيل الحضور بنجاح!'
        ]);
    }

    /**
     * Display billing information for students
     */
    public function billing()
    {
        $instructor = auth()->user()->instructor;
        
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
        $instructor = auth()->user()->instructor;

        if (!$instructor) {
            $students = Student::with(['user', 'enrollments.course'])->take(20)->get();
        } else {
            $students = Student::whereHas('enrollments', function($q) use ($instructor) {
                $q->whereIn('course_id', $instructor->courses->pluck('id'));
            })->with(['user', 'enrollments.course' => function($q) use ($instructor) {
                $q->where('instructor_id', $instructor->id);
            }])->get();
        }

        return view('instructor::students.index', compact('students'));
    }

    /**
     * Show the form for manually creating a student
     */
    public function createStudent()
    {
        $instructor = $this->resolveInstructor();
        $courses = $instructor ? $instructor->courses : Course::all();
        
        return view('instructor::students.create', compact('courses'));
    }

    /**
     * Store a manually created student and enroll them
     */
    public function storeStudent(Request $request)
    {
        $instructor = $this->resolveInstructor();
        if (!$instructor) {
            return back()->with('error', 'يجب أن تكون مسجلاً كمعلم لإضافة طالب.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'parent_phone' => 'required|string|max:20',
            'course_id' => 'required|exists:courses,id',
        ]);

        try {
            \DB::beginTransaction();

            // 1. Check if user already exists by phone
            $user = \App\Models\User::where('phone', $validated['phone'])->first();

            if (!$user) {
                $user = \App\Models\User::create([
                    'tenant_id' => $instructor->tenant_id,
                    'name' => $validated['name'],
                    'email' => $validated['phone'] . '@' . (app('tenant')->domain ?? 'taalimu') . '.com',
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
                    'status' => 'active',
                ]);
            }

            // 2. Enroll in course
            $isEnrolled = Enrollment::where('user_id', $user->id)
                ->where('course_id', $validated['course_id'])
                ->exists();

            if (!$isEnrolled) {
                Enrollment::create([
                    'tenant_id' => $instructor->tenant_id,
                    'user_id' => $user->id,
                    'course_id' => $validated['course_id'],
                    'status' => 'active',
                    'enrolled_at' => now(),
                ]);
            }

            \DB::commit();
            return redirect()->route('instructor.students.list')->with('success', "تم إضافة الطالب '{$validated['name']}' وتسجيلة في المجموعة بنجاح.");
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Manual student registration failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'حدث خطأ أثناء إضافة الطالب: ' . $e->getMessage());
        }
    }

    /**
     * Display a list of groups (courses) for the instructor
     */
    public function groups()
    {
        $instructor = $this->resolveInstructor();

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
        $instructor = $this->resolveInstructor();
        
        if (!$instructor) {
            return back()->with('error', 'يجب أن تكون مسجلاً كمعلم لإنشاء مجموعة.');
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
                'current_tenant_id' => app()->bound('tenant') ? app('tenant')->id : 'none',
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

            return redirect()->route('instructor.groups.list')->with('success', "تم إنشاء المجموعة '{$course->title}' بنجاح.");
        } catch (\Exception $e) {
            \Log::error('Failed to create course: ' . $e->getMessage(), [
                'instructor_id' => $instructor->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'حدث خطأ أثناء محاولة حفظ المجموعة: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified group
     */
    public function editGroup(Course $course)
    {
        $instructor = $this->resolveInstructor();
        if ($instructor && $course->instructor_id !== $instructor->id) {
            abort(403);
        }

        return view('instructor::groups.edit', compact('course'));
    }

    /**
     * Update the specified group in storage
     */
    public function updateGroup(Request $request, Course $course)
    {
        $instructor = $this->resolveInstructor();
        if ($instructor && $course->instructor_id !== $instructor->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sessions_count' => 'required|integer|min:1',
        ]);

        $course->update($validated);

        return redirect()->route('instructor.groups.list')->with('success', "تم تحديث بيانات المجموعة '{$course->title}' بنجاح.");
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
            return back()->with('error', "خطأ: المبلغ المدخل ({$request->amount}) أكبر من المتبقي على الطالب ({$balance})");
        }

        $sale = Sale::create([
            'tenant_id' => $student->tenant_id,
            'student_id' => $student->id,
            'total_amount' => $request->amount,
            'paid_amount' => $request->amount,
            'status' => 'paid',
            'payment_method' => 'cash',
            'notes' => $request->notes ?? 'تحصيل سريع من واجهة المدرس',
        ]);

        Payment::create([
            'tenant_id' => $student->tenant_id,
            'sale_id' => $sale->id,
            'amount' => $request->amount,
            'payment_method' => 'cash',
            'received_by' => auth()->id(),
            'paid_at' => now(),
        ]);

        return back()->with('success', "تم تسجيل استلام {$request->amount} ج.م من الطالب {$student->name}");
    }

    /**
     * Rotate the registration link for a group
     */
    public function rotateGroupLink(Course $course)
    {
        $instructor = $this->resolveInstructor();
        if ($instructor && $course->instructor_id !== $instructor->id) {
            abort(403);
        }

        $course->update([
            'registration_token' => \Illuminate\Support\Str::random(16)
        ]);

        return back()->with('success', "تم توليد رابط جديد للمجموعة '{$course->title}' بنجاح.");
    }

    /**
     * Duplicate a group
     */
    public function duplicateGroup(Course $course)
    {
        $instructor = $this->resolveInstructor();
        if ($instructor && $course->instructor_id !== $instructor->id) {
            abort(403);
        }

        $newCourse = $course->replicate();
        $newCourse->title = $course->title . ' - نسخة';
        $newCourse->registration_token = \Illuminate\Support\Str::random(16);
        $newCourse->save();

        return redirect()->route('instructor.groups.list')->with('success', "تم تكرار المجموعة بنجاح باسم '{$newCourse->title}'.");
    }

    /**
     * Remove the specified group from storage (Soft Delete)
     */
    public function destroyGroup(Course $course)
    {
        $instructor = $this->resolveInstructor();
        if ($instructor && $course->instructor_id !== $instructor->id) {
            abort(403);
        }

        $course->delete();

        return redirect()->route('instructor.groups.list')->with('success', "تم حذف المجموعة '{$course->title}' بنجاح.");
    }

    /**
     * Show detailed profile for a student
     */
    public function showStudent(Student $student)
    {
        $instructor = $this->resolveInstructor();
        
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

    // ─── Schedule Management ───

    public function schedules()
    {
        $instructor = $this->resolveInstructor();
        $query = Schedule::with(['course', 'classroom', 'instructor', 'bookings'])->latest();
        
        if ($instructor) {
            $query->where('instructor_id', $instructor->id);
        }

        $schedules = $query->get();
        return view('instructor::schedules.index', compact('schedules'));
    }

    public function createSchedule()
    {
        $instructor = $this->resolveInstructor();
        $courses = $instructor ? $instructor->courses()->select('id', 'title', 'instructor_id')->get() : Course::select('id', 'title', 'instructor_id')->get();
        $classrooms = \App\Models\Classroom::select('id', 'name', 'capacity')->get();
        
        return view('instructor::schedules.create', compact('courses', 'classrooms'));
    }

    public function storeSchedule(Request $request)
    {
        $instructor = $this->resolveInstructor();
        if (!$instructor) {
            return back()->with('error', 'يجب أن تكون مسجلاً كمعلم.');
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'max_students' => 'nullable|integer|min:1',
        ]);

        $validated['instructor_id'] = $instructor->id;

        // Verify course belongs to instructor
        $course = Course::findOrFail($validated['course_id']);
        if ($course->instructor_id !== $instructor->id) {
            return back()->withInput()->with('error', 'لا يمكنك إنشاء حصة لمجموعة لا تخصك.');
        }

        // Conflict Detection
        $conflictQuery = Schedule::where('day_of_week', $validated['day_of_week'])
            ->where('start_time', '<', $validated['end_time'])
            ->where('end_time', '>', $validated['start_time']);

        // Check Instructor Conflict
        $instructorConflict = clone $conflictQuery;
        if ($instructorConflict->where('instructor_id', $instructor->id)->exists()) {
            return back()->withInput()->with('error', 'يوجد تعارض في المواعيد! لديك حصة أخرى مسجلة في نفس هذا الوقت.');
        }

        // Check Classroom Conflict (if classroom is selected)
        if (!empty($validated['classroom_id'])) {
            $classroomConflict = clone $conflictQuery;
            if ($classroomConflict->where('classroom_id', $validated['classroom_id'])->exists()) {
                return back()->withInput()->with('error', 'يوجد تعارض في المواعيد! القاعة المختارة محجوزة بالفعل لمجموعة أخرى في نفس الوقت.');
            }
        }

        Schedule::create($validated);

        return redirect()->route('instructor.schedules.index')
            ->with('success', 'تم إنشاء موعد الحصة بنجاح.');
    }

    public function editSchedule(Schedule $schedule)
    {
        $instructor = $this->resolveInstructor();
        if ($instructor && $schedule->instructor_id !== $instructor->id) {
            abort(403);
        }

        $courses = $instructor ? $instructor->courses()->select('id', 'title', 'instructor_id')->get() : Course::select('id', 'title', 'instructor_id')->get();
        $classrooms = \App\Models\Classroom::select('id', 'name', 'capacity')->get();

        return view('instructor::schedules.edit', compact('schedule', 'courses', 'classrooms'));
    }

    public function updateSchedule(Request $request, Schedule $schedule)
    {
        $instructor = $this->resolveInstructor();
        if ($instructor && $schedule->instructor_id !== $instructor->id) {
            abort(403);
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
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
            return back()->withInput()->with('error', 'يوجد تعارض في المواعيد! لديك حصة أخرى مسجلة في نفس هذا الوقت.');
        }

        // Check Classroom Conflict (if classroom is selected)
        if (!empty($validated['classroom_id'])) {
            $classroomConflict = clone $conflictQuery;
            if ($classroomConflict->where('classroom_id', $validated['classroom_id'])->exists()) {
                return back()->withInput()->with('error', 'يوجد تعارض في المواعيد! القاعة المختارة محجوزة بالفعل لمجموعة أخرى في نفس الوقت.');
            }
        }

        $schedule->update($validated);

        return redirect()->route('instructor.schedules.index')
            ->with('success', 'تم تعديل موعد الحصة بنجاح.');
    }

    public function destroySchedule(Schedule $schedule)
    {
        $instructor = $this->resolveInstructor();
        if ($instructor && $schedule->instructor_id !== $instructor->id) {
            abort(403);
        }

        $schedule->delete();

        return redirect()->route('instructor.schedules.index')
            ->with('success', 'تم حذف موعد الحصة بنجاح.');
    }

    // ─── Attendance ───

    public function attendance()
    {
        $instructor = $this->resolveInstructor();
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
        $instructor = $this->resolveInstructor();
        if ($instructor && $schedule->instructor_id !== $instructor->id) {
            abort(403);
        }

        $schedule->load('course.enrollments.user.student', 'classroom');
        
        $attendances = Attendance::where('schedule_id', $schedule->id)
            ->whereDate('session_date', today())
            ->get()
            ->keyBy('student_id');

        return view('instructor::attendance.show', compact('schedule', 'attendances'));
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
}

