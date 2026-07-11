<?php

namespace Modules\Instructor\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Modules\Center\Models\Attendance;
use Modules\Instructor\Http\Controllers\Traits\ResolvesInstructor;

class AttendanceController extends Controller
{
    use ResolvesInstructor;

    public function index()
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
            ->unique(fn ($s) => $s->course_id.'-'.$s->start_time.'-'.$s->end_time);

        $todaySessions = new \Illuminate\Pagination\LengthAwarePaginator(
            $todaySessions->forPage(request()->get('page', 1), 10),
            $todaySessions->count(),
            10,
            request()->get('page', 1),
            ['path' => request()->url()]
        );

        $attendanceQuery = Attendance::with(['student', 'course', 'schedule'])->latest();

        if ($instructor) {
            $attendanceQuery->whereHas('course', fn ($q) => $q->where('instructor_id', $instructor->id));
        }

        $recentAttendance = $attendanceQuery->take(10)->get();

        return view('instructor::attendance.index', compact('todaySessions', 'recentAttendance'));
    }

    public function show(Schedule $schedule)
    {
        $this->authorizeSchedule($schedule);

        $schedule->load('course.enrollments.user.student', 'classroom');

        $attendances = Attendance::where('schedule_id', $schedule->id)
            ->whereDate('session_date', today())
            ->get()
            ->keyBy('student_id');

        return view('instructor::attendance.show', compact('schedule', 'attendances'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer',
            'course_id' => 'required|integer',
            'schedule_id' => 'required|integer',
            'status' => 'required|in:present,late,absent',
            'session_date' => 'required|date',
        ]);

        $schedule = Schedule::findOrFail($validated['schedule_id']);
        $this->authorizeSchedule($schedule);

        $attendanceService = app(AttendanceService::class);
        $attendanceService->markAttendance($validated);

        return back()->with('success', __('instructor::messages.saved'));
    }

    public function bulkAbsent(Schedule $schedule)
    {
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
}
