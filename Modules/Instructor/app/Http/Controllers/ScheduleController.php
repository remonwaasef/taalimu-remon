<?php

namespace Modules\Instructor\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Modules\Instructor\Http\Controllers\Traits\ResolvesInstructor;

class ScheduleController extends Controller
{
    use ResolvesInstructor;

    public function index()
    {
        $instructor = $this->instructor;
        $query = Schedule::with(['course', 'classroom', 'instructor', 'bookings'])->latest();

        if ($instructor) {
            $query->where('instructor_id', $instructor->id);
        }

        $schedules = $query->get();

        return view('instructor::schedules.index', compact('schedules'));
    }

    public function create()
    {
        $instructor = $this->instructor;
        $courses = $instructor ? $instructor->courses()->select('id', 'title', 'instructor_id')->get() : Course::select('id', 'title', 'instructor_id')->get();
        $classrooms = \App\Models\Classroom::select('id', 'name', 'capacity')->get();

        return view('instructor::schedules.create', compact('courses', 'classrooms'));
    }

    public function store(Request $request)
    {
        $instructor = $this->instructor;
        if (! $instructor) {
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

        $course = Course::findOrFail($validated['course_id']);
        if ($course->instructor_id !== $instructor->id) {
            return back()->withInput()->with('error', __('instructor::messages.unauthorized_course'));
        }

        // Conflict Detection
        $conflictQuery = Schedule::where('day_of_week', $validated['day_of_week'])
            ->where('start_time', '<', $validated['end_time'])
            ->where('end_time', '>', $validated['start_time']);

        $instructorConflict = clone $conflictQuery;
        if ($instructorConflict->where('instructor_id', $instructor->id)->exists()) {
            return back()->withInput()->with('error', __('instructor::messages.instructor_conflict'));
        }

        if (! empty($validated['classroom_id'])) {
            $classroomConflict = clone $conflictQuery;
            if ($classroomConflict->where('classroom_id', $validated['classroom_id'])->exists()) {
                return back()->withInput()->with('error', __('instructor::messages.hall_conflict'));
            }
        }

        Schedule::create($validated);

        return redirect()->route('instructor.schedules.index')
            ->with('success', __('instructor::messages.schedule_created'));
    }

    public function edit(Schedule $schedule)
    {
        $this->authorizeSchedule($schedule);
        $instructor = $this->instructor;
        $courses = $instructor ? $instructor->courses()->select('id', 'title', 'instructor_id')->get() : Course::select('id', 'title', 'instructor_id')->get();
        $classrooms = \App\Models\Classroom::select('id', 'name', 'capacity')->get();

        return view('instructor::schedules.edit', compact('schedule', 'courses', 'classrooms'));
    }

    public function update(Request $request, Schedule $schedule)
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

        // Conflict Detection (excluding current)
        $conflictQuery = Schedule::where('id', '!=', $schedule->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where('start_time', '<', $validated['end_time'])
            ->where('end_time', '>', $validated['start_time']);

        $instructorConflict = clone $conflictQuery;
        if ($instructorConflict->where('instructor_id', $instructor->id)->exists()) {
            return back()->withInput()->with('error', __('instructor::messages.instructor_conflict'));
        }

        if (! empty($validated['classroom_id'])) {
            $classroomConflict = clone $conflictQuery;
            if ($classroomConflict->where('classroom_id', $validated['classroom_id'])->exists()) {
                return back()->withInput()->with('error', __('instructor::messages.hall_conflict'));
            }
        }

        $schedule->update($validated);

        return redirect()->route('instructor.schedules.index')
            ->with('success', __('instructor::messages.schedule_updated'));
    }

    public function destroy(Schedule $schedule)
    {
        $this->authorizeSchedule($schedule);
        $schedule->delete();

        return redirect()->route('instructor.schedules.index')
            ->with('success', __('instructor::messages.schedule_deleted'));
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
                'new_id' => $classroom->id,
            ]);
        }

        return back()->with('success', __('instructor::messages.saved'));
    }
}
