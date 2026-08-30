<?php

namespace Modules\Center\Http\Controllers;

use App\Models\Course;
use App\Models\Schedule;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Center\Http\Controllers\CenterBaseController as Controller;
use Modules\Center\Models\Attendance;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(\App\Services\AttendanceService $attendanceService)
    {
        parent::__construct();
        $this->attendanceService = $attendanceService;
    }

    /**
     * Display a listing of today's scheduled sessions for attendance tracking.
     */
    public function index()
    {
        $this->authorize('viewAny', Attendance::class);
        $user = auth()->user();
        $dayOfWeek = now()->dayOfWeek;

        $query = Schedule::with(['course', 'classroom', 'instructor'])
            ->where('day_of_week', $dayOfWeek)
            ->whereNotNull('course_id')
            ->whereHas('course', fn ($q) => $q->whereNull('deleted_at'));

        // Filter by instructor if they are not center_admin
        if ($user->hasRole('instructor') && ! $user->hasRole('center_admin')) {
            $query->where('instructor_id', $user->instructor->id ?? 0);
        }

        $todaySessions = $query->select('schedules.*')
            ->distinct()
            ->orderBy('start_time')
            ->get()
            ->unique(function ($schedule) {
                return $schedule->course_id.'-'.$schedule->start_time.'-'.$schedule->end_time;
            });

        $todaySessions = new \Illuminate\Pagination\LengthAwarePaginator(
            $todaySessions->forPage(request()->get('page', 1), 10),
            $todaySessions->count(),
            10,
            request()->get('page', 1),
            ['path' => request()->url()]
        );

        $attendanceQuery = Attendance::with(['student', 'course', 'schedule'])->latest();

        if ($user->hasRole('instructor') && ! $user->hasRole('center_admin')) {
            $attendanceQuery->whereHas('course', function ($q) use ($user) {
                $q->where('instructor_id', $user->instructor->id ?? 0);
            });
        }

        $recentAttendance = $attendanceQuery->take(10)->get();

        return view('center::attendance.index', compact('todaySessions', 'recentAttendance'));
    }

    /**
     * Show the student list for a specific scheduled session.
     */
    public function show(Schedule $schedule)
    {
        $this->authorize('viewAny', Attendance::class);
        $schedule->load(['course.enrollments.user.student', 'classroom']);

        $attendances = Attendance::where('schedule_id', $schedule->id)
            ->whereDate('session_date', today())
            ->get()
            ->keyBy('student_id');

        return view('center::attendance.show', compact('schedule', 'attendances'));
    }

    /**
     * Manually mark attendance for a student.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Attendance::class);
        $validated = $request->validate([
            'student_id' => 'nullable|exists:students,id',
            'student_code' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'schedule_id' => 'required|exists:schedules,id',
            'session_date' => 'required|date|before_or_equal:today',
            'status' => 'required|in:present,absent,late,excused',
            'late_minutes' => 'nullable|integer|min:0',
        ]);

        $user = auth()->user();
        if ($user->hasRole('instructor') && ! $user->hasRole('center_admin')) {
            $course = Course::findOrFail($validated['course_id']);
            if ($course->instructor_id !== ($user->instructor->id ?? 0)) {
                return $request->expectsJson()
                    ? response()->json(['success' => false, 'message' => 'غير مصرح لك بتسجيل الحضور لهذه المجموعة.'], 403)
                    : back()->with('error', 'غير مصرح لك بتسجيل الحضور لهذه المجموعة.');
            }
        }

        $studentId = $request->student_id;

        // If student_id is not provided, try to find by code
        if (! $studentId && $request->student_code) {
            $student = \App\Models\Student::where('tenant_id', $this->tenant->id)
                ->where('code', $request->student_code)
                ->first();

            if (! $student) {
                // Try finding by ID directly just in case the code is actually an ID
                $student = \App\Models\Student::where('tenant_id', $this->tenant->id)
                    ->where('id', $request->student_code)
                    ->first();
            }

            if (! $student) {
                return $request->expectsJson()
                    ? response()->json(['success' => false, 'message' => 'لم يتم العثور على الطالب بهذا الكود.'], 404)
                    : back()->with('error', __('center::messages.msg_009'));
            }
            $studentId = $student->id;
        }

        if (! $studentId) {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'معرف الطالب مطلوب.'], 422)
                : back()->with('error', __('center::messages.msg_010'));
        }

        $schedule = Schedule::findOrFail($validated['schedule_id']);
        if (now()->isAfter(Carbon::parse($schedule->end_time)) && $validated['status'] !== 'absent') {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'لا يمكن تسجيل الحضور بعد انتهاء وقت الحصة.'], 422)
                : back()->with('error', __('center::messages.msg_011'));
        }

        if ($this->attendanceService->hasAttendedToday($studentId, $request->schedule_id)) {
            if (! $user->hasRole('center_admin')) {
                return $request->expectsJson()
                    ? response()->json(['success' => false, 'message' => 'هذا الطالب مسجل حضوره بالفعل. التعديل مسموح للمدير فقط.'], 422)
                    : back()->with('error', 'هذا الطالب مسجل حضوره بالفعل. التعديل مسموح للمدير فقط.');
            }
        }

        $this->attendanceService->markAttendance(array_merge($validated, [
            'tenant_id' => $this->tenant->id,
            'student_id' => $studentId, // Ensure the resolved student ID is used
        ]));

        return $request->expectsJson()
            ? response()->json(['success' => true, 'message' => 'تم تسجيل الحضور بنجاح!'])
            : back()->with('success', __('center::messages.msg_013'));
    }

    /**
     * Mark all unrecorded students as absent for a session.
     */
    public function bulkAbsent(Schedule $schedule)
    {
        $this->authorize('create', Attendance::class);

        $schedule->load('course.enrollments.user.student');
        $recordedStudentIds = Attendance::where('schedule_id', $schedule->id)
            ->whereDate('session_date', today())
            ->pluck('student_id')
            ->all();
        $recordedStudentIds = array_flip($recordedStudentIds); // O(1) lookups

        // Build all rows first, then insert in ONE query instead of one
        // updateOrCreate per student (2 queries × N students on large classes).
        // 'absent' rows trigger no notifications/gamification in markAttendance,
        // so a plain batch insert is behavior-equivalent for this path.
        $now = now();
        $rows = [];
        foreach ($schedule->course->enrollments as $enrollment) {
            $student = $enrollment->user->student ?? null;
            if ($student && ! isset($recordedStudentIds[$student->id])) {
                $recordedStudentIds[$student->id] = true; // dedupe multiple enrollments
                $rows[] = [
                    'tenant_id' => $this->tenant->id,
                    'student_id' => $student->id,
                    'course_id' => $schedule->course_id,
                    'schedule_id' => $schedule->id,
                    'session_date' => today()->toDateString(),
                    'check_in_time' => $now,
                    'status' => 'absent',
                    'late_minutes' => 0,
                    'late_label' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if ($rows !== []) {
            foreach (array_chunk($rows, 500) as $chunk) {
                Attendance::insert($chunk);
            }
        }
        $markedCount = count($rows);

        return back()->with('success', __('center::messages.bulk_absent_success', ['count' => $markedCount]));
    }

    /**
     * Show QR Code for a specific session.
     */
    public function showQr(Request $request, Schedule $schedule)
    {
        $this->authorize('viewAny', Attendance::class);
        $this->assertTenantSchedule($schedule);

        $schedule->load(['course.enrollments.user.student', 'classroom', 'instructor']);

        $url = $this->attendanceService->generateQrUrl($schedule->id, $this->tenant->domain);

        $today = today();
        $totalEnrolled = $schedule->course ? $schedule->course->enrollments()->count() : 0;
        $attendances = Attendance::where('schedule_id', $schedule->id)
            ->whereDate('session_date', $today)
            ->with('student')
            ->latest('check_in_time')
            ->get();

        $attendedCount = $attendances->whereIn('status', ['present', 'late'])->count();

        return view('center::attendance.qr', compact('schedule', 'url', 'totalEnrolled', 'attendedCount', 'attendances'));
    }

    /**
     * Get live attendance status and refreshed QR URL for polling.
     */
    public function qrLiveStatus(Schedule $schedule)
    {
        $this->authorize('viewAny', Attendance::class);
        $this->assertTenantSchedule($schedule);

        $today = today();
        $attendances = Attendance::where('schedule_id', $schedule->id)
            ->whereDate('session_date', $today)
            ->with('student:id,name,phone')
            ->latest('check_in_time')
            ->take(15)
            ->get(['id', 'student_id', 'status', 'check_in_time', 'late_minutes']);

        $totalEnrolled = $schedule->course ? $schedule->course->enrollments()->count() : 0;
        $attendedCount = Attendance::where('schedule_id', $schedule->id)
            ->whereDate('session_date', $today)
            ->whereIn('status', ['present', 'late'])
            ->count();

        $freshUrl = $this->attendanceService->generateQrUrl($schedule->id, $this->tenant->domain);

        return response()->json([
            'total_enrolled' => $totalEnrolled,
            'attended_count' => $attendedCount,
            'qr_url' => $freshUrl,
            'attendees' => $attendances->map(fn ($a) => [
                'id' => $a->id,
                'name' => $a->student->name ?? 'طالب',
                'status' => $a->status,
                'status_label' => $a->status === 'present' ? 'حاضر' : ($a->status === 'late' ? 'متأخر' : 'غائب'),
                'late_minutes' => $a->late_minutes ?? 0,
                'time' => $a->check_in_time ? $a->check_in_time->format('h:i A') : '',
            ]),
        ]);
    }

    /**
     * Mark attendance via QR code scan (Student side).
     */
    public function markByQr(Request $request, Schedule $schedule)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'انتهت صلاحية رمز QR أو أنه غير صالح. يرجى مسح الرمز مرة أخرى.');
        }

        $this->assertTenantSchedule($schedule);

        if (! auth()->check()) {
            return view('center::attendance.scan-login', [
                'schedule' => $schedule,
                'qrUrl' => $request->fullUrl(),
            ]);
        }

        $student = auth()->user()->student;
        if (! $student) {
            auth()->logout();

            return view('center::attendance.scan-login', [
                'schedule' => $schedule,
                'qrUrl' => $request->fullUrl(),
                'message' => 'هذا الحساب ليس حساب طالب. يرجى تسجيل الدخول بحساب طالب.',
            ]);
        }

        if (! $this->assertStudentTenant($student)) {
            return view('center::attendance.scan-login', [
                'schedule' => $schedule,
                'qrUrl' => $request->fullUrl(),
                'message' => 'هذا الحساب لا ينتمي إلى هذا المركز.',
            ]);
        }

        return $this->processQrAttendance($student, $schedule);
    }

    /**
     * Handle login + attendance from scan-login form.
     */
    public function loginAndMark(Request $request, Schedule $schedule)
    {
        // TEN-4: signed-URL protection & schedule tenant validation
        if (! $request->hasValidSignature()) {
            abort(403, 'انتهت صلاحية رمز QR أو أنه غير صالح. يرجى مسح الرمز مرة أخرى.');
        }

        $this->assertTenantSchedule($schedule);

        // Case 1: Phone / Code quick attendance verification (No password required in classroom)
        if ($request->filled('phone_or_code')) {
            $input = trim($request->phone_or_code);
            $student = Student::where('tenant_id', $this->tenant->id)
                ->where(function ($q) use ($input) {
                    $q->where('phone', $input)
                      ->orWhere('parent_phone', $input)
                      ->orWhere('code', $input)
                      ->orWhere('national_id', $input);
                })
                ->first();

            if (! $student) {
                return back()->withErrors(['phone_or_code' => 'لم يتم العثور على طالب مسجل برقم الهاتف أو الكود المدخل في هذا المركز.'])->withInput();
            }

            if (! $this->assertStudentTenant($student)) {
                return back()->withErrors(['phone_or_code' => 'هذا الحساب لا ينتمي إلى هذا المركز.'])->withInput();
            }

            return $this->processQrAttendance($student, $schedule);
        }

        // Case 2: Email & Password authentication
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'qr_url' => 'nullable|string',
        ]);

        if (! auth()->attempt(['email' => $request->email, 'password' => $request->password, 'tenant_id' => $this->tenant->id])) {
            return back()->withErrors(['email' => 'بيانات الدخول غير صحيحة.'])->withInput();
        }

        $student = auth()->user()->student;
        if (! $student) {
            auth()->logout();

            return back()->with('message', __('center::messages.msg_014'));
        }

        if (! $this->assertStudentTenant($student)) {
            auth()->logout();

            return back()->with('message', 'هذا الحساب لا ينتمي إلى هذا المركز.');
        }

        return $this->processQrAttendance($student, $schedule);
    }

    /**
     * TEN-4: fail closed when no tenant context, or when the schedule was
     * created by a different tenant (guards route-model binding on hosts
     * where no tenant is bound).
     */
    private function assertTenantSchedule(Schedule $schedule): void
    {
        if (! $this->tenant || $schedule->tenant_id !== $this->tenant->id) {
            abort(404, 'Center not found.');
        }
    }

    /**
     * TEN-4: the student row (and its user) must belong to the current tenant.
     */
    private function assertStudentTenant(\App\Models\Student $student): bool
    {
        if (! $this->tenant || $student->tenant_id !== $this->tenant->id) {
            return false;
        }

        return true;
    }

    /**
     * Process QR attendance once we have an authenticated student.
     */
    private function processQrAttendance(Student $student, Schedule $schedule)
    {
        if ($this->attendanceService->hasAttendedToday($student->id, $schedule->id)) {
            return view('center::attendance.success', ['message' => 'تم تسجيل حضورك بالفعل لهذه الحصة اليوم!']);
        }

        if (now()->isAfter(Carbon::parse($schedule->end_time))) {
            return view('center::attendance.success', ['message' => 'عذراً، انتهى وقت تسجيل الحضور لهذه الحصة.']);
        }

        $lateData = $this->attendanceService->determineStatus($schedule);

        $this->attendanceService->markAttendance([
            'tenant_id' => $this->tenant->id,
            'student_id' => $student->id,
            'course_id' => $schedule->course_id,
            'schedule_id' => $schedule->id,
            'session_date' => today(),
            'status' => $lateData['status'],
            'late_minutes' => $lateData['late_minutes'],
            'late_label' => $lateData['late_label'],
        ]);

        $successMsg = 'تم تسجيل حضورك بنجاح! 🎉';
        if ($lateData['status'] === 'late') {
            $successMsg = "تم تسجيل حضورك بنجاح (تأخير: {$lateData['late_minutes']} دقيقة - {$lateData['late_label']})";
        }

        return view('center::attendance.success', ['message' => $successMsg]);
    }

    /**
     * Sync offline attendance records (bulk).
     * Receives an array of attendance records saved in LocalStorage when offline.
     */
    public function offlineSync(Request $request)
    {
        $this->authorize('create', Attendance::class);

        $validated = $request->validate([
            'records' => 'required|array|min:1|max:50',
            'records.*.student_id' => 'nullable|exists:students,id',
            'records.*.student_code' => 'nullable|string',
            'records.*.course_id' => 'required|exists:courses,id',
            'records.*.schedule_id' => 'required|exists:schedules,id',
            'records.*.session_date' => 'required|date',
            'records.*.status' => 'required|in:present,absent,late,excused',
            'records.*.late_minutes' => 'nullable|integer|min:0',
            'records.*.offline_timestamp' => 'nullable|string',
        ]);

        $synced = 0;
        $failed = 0;
        $errors = [];

        // Resolve all student codes in ONE query instead of one query per record.
        $codes = collect($validated['records'])
            ->filter(fn ($r) => empty($r['student_id']) && ! empty($r['student_code']))
            ->pluck('student_code')
            ->unique()
            ->values();
        $codeToId = $codes->isEmpty()
            ? collect()
            : Student::where('tenant_id', $this->tenant->id)
                ->whereIn('code', $codes)
                ->pluck('id', 'code');

        foreach ($validated['records'] as $index => $record) {
            try {
                $studentId = $record['student_id'] ?? null;

                // Resolve student by code if no ID (from the prefetched map)
                if (! $studentId && ! empty($record['student_code'])) {
                    $studentId = $codeToId[$record['student_code']] ?? null;
                }

                if (! $studentId) {
                    $failed++;
                    $errors[] = "Record #{$index}: Student not found.";

                    continue;
                }

                $this->attendanceService->markAttendance(array_merge($record, [
                    'tenant_id' => $this->tenant->id,
                    'student_id' => $studentId,
                ]));

                $synced++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Record #{$index}: ".$e->getMessage();
                \Illuminate\Support\Facades\Log::warning('Offline sync failed for record', [
                    'record' => $record,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'synced' => $synced,
            'failed' => $failed,
            'errors' => $errors,
            'message' => "تمت مزامنة {$synced} سجل حضور بنجاح.".($failed > 0 ? " فشل {$failed} سجل." : ''),
        ]);
    }
}
