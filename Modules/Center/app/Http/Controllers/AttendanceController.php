<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Enrollment;
use Modules\Center\Models\Attendance;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(\App\Services\AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Display a listing of today's scheduled sessions for attendance tracking.
     */
    public function index()
    {
        $this->authorize('viewAny', Attendance::class);
        $dayOfWeek = now()->dayOfWeek;

        // Get unique schedules per course (avoid duplicates for same course, day, and time)
        $todaySessions = Schedule::with(['course', 'classroom', 'instructor'])
            ->where('day_of_week', $dayOfWeek)
            ->whereNotNull('course_id')
            ->select('schedules.*')
            ->distinct()
            ->orderBy('start_time')
            ->get()
            // Group by course_id and time slot to avoid duplicates
            ->unique(function ($schedule) {
                return $schedule->course_id . '-' . $schedule->start_time . '-' . $schedule->end_time;
            });

        // Convert to paginator manually for view compatibility
        $todaySessions = new \Illuminate\Pagination\LengthAwarePaginator(
            $todaySessions->forPage(request()->get('page', 1), 10),
            $todaySessions->count(),
            10,
            request()->get('page', 1),
            ['path' => request()->url()]
        );

        $recentAttendance = Attendance::with(['student', 'course', 'schedule'])
            ->latest()
            ->take(10)
            ->get();

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
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Attendance::class);
        $validated = $request->validate([
            'student_id' => 'nullable|exists:students,id',
            'student_code' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'schedule_id' => 'required|exists:schedules,id',
            'session_date' => 'required|date|before_or_equal:today',
            'status' => 'required|in:present,absent,late,excused'
        ]);

        $studentId = $request->student_id;

        // If student_id is not provided, try to find by code
        if (!$studentId && $request->student_code) {
            $student = \App\Models\Student::where('tenant_id', app('tenant')->id)
                ->where('code', $request->student_code)
                ->first();
            
            if (!$student) {
                // Try finding by ID directly just in case the code is actually an ID
                $student = \App\Models\Student::where('tenant_id', app('tenant')->id)
                    ->where('id', $request->student_code)
                    ->first();
            }

            if (!$student) {
                return $request->expectsJson() 
                    ? response()->json(['success' => false, 'message' => 'لم يتم العثور على الطالب بهذا الكود.'], 404)
                    : back()->with('error', 'لم يتم العثور على الطالب بهذا الكود.');
            }
            $studentId = $student->id;
        }

        if (!$studentId) {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'معرف الطالب مطلوب.'], 422)
                : back()->with('error', 'معرف الطالب مطلوب.');
        }

        $schedule = Schedule::findOrFail($validated['schedule_id']);
        if (now()->isAfter(Carbon::parse($schedule->end_time)) && $validated['status'] !== 'absent') {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'لا يمكن تسجيل الحضور بعد انتهاء وقت الحصة.'], 422)
                : back()->with('error', 'لا يمكن تسجيل الحصة بعد انتهاء وقت الحصة (يمكنك فقط تسجيل الغياب)');
        }

        if ($this->attendanceService->hasAttendedToday($studentId, $request->schedule_id)) {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'هذا الطالب مسجل حضوره بالفعل.'], 422)
                : back()->with('error', 'هذا الطالب مسجل حضوره بالفعل اليوم');
        }

        $this->attendanceService->markAttendance(array_merge($validated, [
            'tenant_id' => app('tenant')->id,
            'student_id' => $studentId, // Ensure the resolved student ID is used
        ]));

        return $request->expectsJson()
            ? response()->json(['success' => true, 'message' => 'تم تسجيل الحضور بنجاح!'])
            : back()->with('success', 'تم تحديث الحالة بنجاح');
    }

    /**
     * Mark all unrecorded students as absent for a session.
     */
    public function bulkAbsent(Schedule $schedule): RedirectResponse
    {
        $this->authorize('create', Attendance::class);
        
        $schedule->load('course.enrollments.user.student');
        $recordedStudentIds = Attendance::where('schedule_id', $schedule->id)
            ->whereDate('session_date', today())
            ->pluck('student_id')
            ->toArray();

        $markedCount = 0;
        foreach ($schedule->course->enrollments as $enrollment) {
            $student = $enrollment->user->student ?? null;
            if ($student && !in_array($student->id, $recordedStudentIds)) {
                $this->attendanceService->markAttendance([
                    'tenant_id' => app('tenant')->id,
                    'student_id' => $student->id,
                    'course_id' => $schedule->course_id,
                    'schedule_id' => $schedule->id,
                    'session_date' => today(),
                    'status' => 'absent'
                ]);
                $markedCount++;
            }
        }

        return back()->with('success', "تم تسجيل غياب $markedCount طلاب بنجاح");
    }

    /**
     * Show QR Code for a specific session.
     */
    public function showQr(Request $request, Schedule $schedule)
    {
        $this->authorize('viewAny', Attendance::class);
        $url = $this->attendanceService->generateQrUrl($schedule->id, app('tenant')->domain);

        return view('center::attendance.qr', compact('schedule', 'url'));
    }

    /**
     * Mark attendance via QR code scan (Student side).
     */
    public function markByQr(Request $request, Schedule $schedule)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'انتهت صلاحية رمز QR أو أنه غير صالح. يرجى مسح الرمز مرة أخرى.');
        }

        if (!auth()->check()) {
            return view('center::attendance.scan-login', [
                'schedule' => $schedule,
                'qrUrl' => $request->fullUrl(),
            ]);
        }

        $student = auth()->user()->student;
        if (!$student) {
            auth()->logout();
            return view('center::attendance.scan-login', [
                'schedule' => $schedule,
                'qrUrl' => $request->fullUrl(),
                'message' => 'هذا الحساب ليس حساب طالب. يرجى تسجيل الدخول بحساب طالب.',
            ]);
        }

        return $this->processQrAttendance($student, $schedule);
    }

    /**
     * Handle login + attendance from scan-login form.
     */
    public function loginAndMark(Request $request, Schedule $schedule)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'qr_url' => 'nullable|string',
        ]);

        if (!auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return back()->withErrors(['email' => 'بيانات الدخول غير صحيحة.'])->withInput();
        }

        $student = auth()->user()->student;
        if (!$student) {
            auth()->logout();
            return back()->with('message', 'هذا الحساب ليس حساب طالب. يرجى تسجيل الدخول بحساب طالب.');
        }

        return $this->processQrAttendance($student, $schedule);
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
            'tenant_id' => app('tenant')->id,
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
}
