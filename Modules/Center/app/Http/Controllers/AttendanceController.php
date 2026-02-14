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
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'schedule_id' => 'required|exists:schedules,id',
            'status' => 'required|in:present,late,absent',
            'session_date' => 'required|date|before_or_equal:today',
        ]);
        
        $schedule = Schedule::findOrFail($validated['schedule_id']);
        if (now()->isAfter(Carbon::parse($schedule->end_time)) && $validated['status'] !== 'absent') {
            return back()->with('error', 'لا يمكن تسجيل الحضور بعد انتهاء وقت الحصة (يمكنك فقط تسجيل الغياب)');
        }

        $this->attendanceService->markAttendance(array_merge($validated, [
            'tenant_id' => app('tenant')->id
        ]));

        return back()->with('success', 'تم تحديث الحالة بنجاح');
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
        $url = $this->attendanceService->generateQrUrl($schedule->id, $request->route('tenant'));

        return view('center::attendance.qr', compact('schedule', 'url'));
    }

    /**
     * Mark attendance via QR code scan (Student side).
     */
    public function markByQr(Request $request, Schedule $schedule)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'QR Code Expired or Invalid.');
        }

        $student = auth()->user()->student;
        if (!$student) {
            return redirect()->route('center.login')->with('error', 'Must be logged in as a student.');
        }

        if ($this->attendanceService->hasAttendedToday($student->id, $schedule->id)) {
            return view('center::attendance.success', ['message' => 'تم تسجيل حضورك بالفعل لهذه الحصة اليوم!']);
        }

        if (now()->isAfter(Carbon::parse($schedule->end_time))) {
            return view('center::attendance.success', ['message' => 'عذراً، انتهى وقت تسجيل الحضور لهذه الحصة.']);
        }

        $this->attendanceService->markAttendance([
            'tenant_id' => $request->route('tenant'),
            'student_id' => $student->id,
            'course_id' => $schedule->course_id,
            'schedule_id' => $schedule->id,
            'session_date' => today(),
            'status' => 'present'
        ]);

        return view('center::attendance.success', ['message' => 'تم تسجيل حضورك بنجاح!']);
    }
}
