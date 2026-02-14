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
        $schedules = Schedule::with(['course', 'classroom', 'instructor', 'bookings'])->latest()->paginate(10);
        return view('center::schedules.index', compact('schedules'));
    }

    /**
     * Get schedules for a specific course (Ajax).
     */
    public function getCourseSchedules(Course $course)
    {
        $this->authorize('view', $course);
        $schedules = Schedule::where('course_id', $course->id)
            ->with(['classroom', 'instructor'])
            ->get();
        
        return response()->json($schedules);
    }

    /**
     * Get scheduling metadata (Ajax).
     */
    public function getMetadata()
    {
        $this->authorize('viewAny', Schedule::class);
        return response()->json($this->getFormData());
    }

    /**
     * Get form data for create and edit views.
     */
    protected function getFormData(): array
    {
        return [
            'courses' => Course::select('id', 'title', 'instructor_id')->get(),
            'classrooms' => Classroom::select('id', 'name', 'capacity')->get(),
            'instructors' => Instructor::select('id', 'name', 'email')->get(),
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
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Schedule::class);
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'instructor_id' => 'nullable|exists:instructors,id',
            'days' => 'required|array|min:1',
            'days.*' => 'integer|between:0,6',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'max_students' => 'nullable|integer|min:1',
        ]);

        $days = $request->days;
        $commonData = $request->only(['course_id', 'classroom_id', 'instructor_id', 'start_time', 'end_time', 'max_students']);
        
        $conflicts = [];
        foreach ($days as $day) {
            $data = array_merge($commonData, ['day_of_week' => $day]);
            $error = $this->getConflictError($data);
            if ($error) {
                $daysNames = [0 => 'الأحد', 1 => 'الإثنين', 2 => 'الثلاثاء', 3 => 'الأربعاء', 4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت'];
                $conflicts[] = "يوم {$daysNames[$day]}: {$error}";
            }
        }

        if (count($conflicts) > 0) {
            return back()->withInput()->withErrors(['conflict' => $conflicts]);
        }

        foreach ($days as $day) {
            Schedule::create(array_merge($commonData, ['day_of_week' => $day]));
        }

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'تم إضافة المواعيد بنجاح']);
        }

        return redirect()->route('center.schedules.index')
            ->with('success', 'تم إضافة المواعيد بنجاح');
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
            'course_id' => 'required|exists:courses,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'instructor_id' => 'nullable|exists:instructors,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'max_students' => 'nullable|integer|min:1',
        ]);

        $conflictError = $this->getConflictError($validated, $schedule->id);
        if ($conflictError) {
            return back()->withInput()->withErrors(['conflict' => "خطأ: {$conflictError}"]);
        }

        $schedule->update($validated);

        return redirect()->route('center.schedules.index')
            ->with('success', 'تم تحديث الموعد بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule): RedirectResponse
    {
        $this->authorize('delete', $schedule);
        $schedule->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('center.schedules.index')
            ->with('success', 'تم حذف الموعد بنجاح');
    }

    protected function getConflictError($data, $excludeId = null)
    {
        if (!isset($data['day_of_week']) || empty($data['start_time']) || empty($data['end_time'])) {
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
        if (!empty($data['classroom_id'])) {
            $classroomConflict = (clone $query)->where('classroom_id', $data['classroom_id'])->with('course')->first();
            if ($classroomConflict) {
                return "هذه القاعة محجوزة لدورة: " . $classroomConflict->course->title;
            }
        }

        // Instructor conflict
        if (!empty($data['instructor_id'])) {
            $instructorConflict = (clone $query)->where('instructor_id', $data['instructor_id'])->with('course')->first();
            if ($instructorConflict) {
                return "هذا المعلم لديه حصة أخرى في نفس الوقت لدورة: " . $instructorConflict->course->title;
            }
        }

        return null;
    }
}
