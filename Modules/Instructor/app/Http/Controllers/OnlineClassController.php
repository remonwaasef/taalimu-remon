<?php

namespace Modules\Instructor\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\OnlineClass;
use Illuminate\Http\Request;

class OnlineClassController extends Controller
{
    /**
     * Display a listing of the instructor's online classes.
     */
    public function index()
    {
        $instructor = auth()->user()->instructor;

        $query = OnlineClass::with(['course']);

        if ($instructor) {
            $query->where('instructor_id', $instructor->id);
        }

        $onlineClasses = $query->latest('start_time')->limit(200)->get();

        return view('instructor::online_classes.index', compact('onlineClasses'));
    }

    /**
     * Show the form for creating a new online class.
     */
    public function create()
    {
        $instructor = auth()->user()->instructor;

        if ($instructor) {
            $courses = $instructor->courses()->limit(500)->get();
        } else {
            $courses = Course::select('id', 'title')->limit(500)->get();
        }

        return view('instructor::online_classes.create', compact('courses'));
    }

    /**
     * Store a newly created online class in storage.
     */
    public function store(Request $request)
    {
        $instructor = auth()->user()->instructor;

        if (! $instructor) {
            return back()->with('error', 'Unauthorized. Must be an instructor.');
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'platform' => 'required|string|max:50',
            'meeting_link' => 'required|url',
            'meeting_id' => 'nullable|string|max:255',
            'meeting_password' => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'duration_minutes' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
        ]);

        $validated['instructor_id'] = $instructor->id;
        $validated['tenant_id'] = $instructor->tenant_id;

        OnlineClass::create($validated);

        return redirect()->route('instructor.online_classes.index')
            ->with('success', __('instructor::dashboard.online_class_created', ['default' => 'تم إنشاء الدرس الأونلاين بنجاح.']));
    }

    /**
     * Show the form for editing the specified online class.
     */
    public function edit(OnlineClass $onlineClass)
    {
        $instructor = auth()->user()->instructor;

        if ($instructor && $onlineClass->instructor_id !== $instructor->id) {
            abort(403, 'Unauthorized');
        }

        if ($instructor) {
            $courses = $instructor->courses;
        } else {
            $courses = Course::select('id', 'title')->get();
        }

        return view('instructor::online_classes.edit', compact('onlineClass', 'courses'));
    }

    /**
     * Update the specified online class in storage.
     */
    public function update(Request $request, OnlineClass $onlineClass)
    {
        $instructor = auth()->user()->instructor;

        if ($instructor && $onlineClass->instructor_id !== $instructor->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'platform' => 'required|string|max:50',
            'meeting_link' => 'required|url',
            'meeting_id' => 'nullable|string|max:255',
            'meeting_password' => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'duration_minutes' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
        ]);

        $onlineClass->update($validated);

        return redirect()->route('instructor.online_classes.index')
            ->with('success', __('instructor::dashboard.online_class_updated', ['default' => 'تم تحديث الدرس الأونلاين بنجاح.']));
    }

    /**
     * Remove the specified online class from storage.
     */
    public function destroy(OnlineClass $onlineClass)
    {
        $instructor = auth()->user()->instructor;

        if ($instructor && $onlineClass->instructor_id !== $instructor->id) {
            abort(403, 'Unauthorized');
        }

        $onlineClass->delete();

        return redirect()->route('instructor.online_classes.index')
            ->with('success', __('instructor::dashboard.online_class_deleted', ['default' => 'تم حذف الدرس الأونلاين بنجاح.']));
    }
}
