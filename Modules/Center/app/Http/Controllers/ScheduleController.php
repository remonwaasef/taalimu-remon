<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Schedule::class);
        $user = auth()->user();

        $query = Schedule::with(['course', 'classroom', 'instructor', 'bookings'])->latest();

        if ($user->hasRole('instructor') && ! $user->hasRole('center_admin')) {
            $query->where('instructor_id', $user->instructor->id ?? 0);
        }

        $schedules = $query->paginate(10);

        return view('center::schedules.index', compact('schedules'));
    }

    protected function getFormData(): array
    {
        $user = auth()->user();
        $coursesQuery = Course::select('id', 'title', 'instructor_id');
        $instructorsQuery = Instructor::select('id', 'name', 'email');

        if ($user->hasRole('instructor') && ! $user->hasRole('center_admin')) {
            $instructorId = $user->instructor->id ?? 0;
            $coursesQuery->where('instructor_id', $instructorId);
            $instructorsQuery->where('id', $instructorId);
        }

        return [
            'courses' => $coursesQuery->limit(500)->get(),
            'classrooms' => Classroom::select('id', 'name', 'capacity')->limit(500)->get(),
            'instructors' => $instructorsQuery->limit(500)->get(),
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Schedule::class);

        return view('center::schedules.create', $this->getFormData());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Schedule::class);
        $validated = $request->validate([
            'course_id' => 'nullable|exists:courses,id',

            'classroom_id' => 'nullable|exists:classrooms,id',

            'instructor_id' => 'nullable|exists:instructors,id',
            'day_of_week' => 'nullable|integer|between:0,6',

            'start_time' => 'nullable',

            'end_time' => 'nullable|after:start_time',

            'max_students' => 'nullable|integer|min:1',
        ]);

        // Conflict Detection
        $conflictError = $this->getConflictError($validated);
        if ($conflictError) {
            return back()->withInput()->withErrors(['conflict' => $conflictError]);
        }

        Schedule::create($validated);

        return redirect()->route('center.schedules.index')
            ->with('success', __('center::messages.msg_075'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        $this->authorize('update', $schedule);

        return view('center::schedules.edit', array_merge(
            ['schedule' => $schedule],
            $this->getFormData()
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $this->authorize('update', $schedule);
        $validated = $request->validate([
            'course_id' => 'nullable|exists:courses,id',

            'classroom_id' => 'nullable|exists:classrooms,id',

            'instructor_id' => 'nullable|exists:instructors,id',
            'day_of_week' => 'nullable|integer|between:0,6',

            'start_time' => 'nullable',

            'end_time' => 'nullable|after:start_time',

            'max_students' => 'nullable|integer|min:1',
        ]);

        // Conflict Detection (excluding current schedule)
        $conflictError = $this->getConflictError($validated, $schedule->id);
        if ($conflictError) {
            return back()->withInput()->withErrors(['conflict' => $conflictError]);
        }

        $schedule->update($validated);

        return redirect()->route('center.schedules.index')
            ->with('success', __('center::messages.msg_076'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule): RedirectResponse
    {
        $this->authorize('delete', $schedule);
        $schedule->delete();

        return redirect()->route('center.schedules.index')
            ->with('success', __('center::messages.msg_077'));
    }

    protected function getConflictError($data, $excludeId = null)
    {
        if (empty($data['day_of_week']) || empty($data['start_time']) || empty($data['end_time'])) {
            return null;
        }

        $query = Schedule::where('day_of_week', $data['day_of_week'])
            ->where(function ($q) use ($data) {
                $q->where(function ($sub) use ($data) {
                    $sub->where('start_time', '<', $data['end_time'])
                        ->where('end_time', '>', $data['start_time']);
                });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        // Classroom conflict
        if (! empty($data['classroom_id'])) {
            $classroomConflict = (clone $query)->where('classroom_id', $data['classroom_id'])->with('course')->first();
            if ($classroomConflict) {
                return __('center::schedules.classroom_conflict', ['course' => $classroomConflict->course->title]);
            }
        }

        // Instructor conflict
        if (! empty($data['instructor_id'])) {
            $instructorConflict = (clone $query)->where('instructor_id', $data['instructor_id'])->with('course')->first();
            if ($instructorConflict) {
                return __('center::schedules.instructor_conflict', ['course' => $instructorConflict->course->title]);
            }
        }

        return null;
    }
}
