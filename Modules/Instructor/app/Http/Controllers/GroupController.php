<?php

namespace Modules\Instructor\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Modules\Instructor\Http\Requests\StoreGroupRequest;
use Modules\Instructor\Http\Controllers\Traits\ResolvesInstructor;

class GroupController extends Controller
{
    use ResolvesInstructor;

    /**
     * Display a list of groups (courses) for the instructor
     */
    public function index()
    {
        $instructor = $this->instructor;

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
    public function create()
    {
        return view('instructor::groups.create');
    }

    /**
     * Store a newly created group in storage
     */
    public function store(StoreGroupRequest $request)
    {
        $instructor = $this->instructor;
        
        if (!$instructor) {
            return back()->with('error', __('instructor::messages.not_instructor_error'));
        }

        try {
            $validated = $request->validated();

            \Log::info('Attempting to create course for instructor: ' . $instructor->id, [
                'validated' => $validated,
                'tenant_bound' => app()->bound('tenant'),
                'current_tenant_id' => app()->bound('tenant') ? $this->tenant->id : 'none',
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

            return redirect()->route('instructor.groups.list')->with('success', __('instructor::messages.group_created', ['title' => $course->title]));
        } catch (\Exception $e) {
            \Log::error('Failed to create course: ' . $e->getMessage(), [
                'instructor_id' => $instructor->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', __('instructor::messages.error_saving_group', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Show the form for editing the specified group
     */
    public function edit(Course $course)
    {
        $this->authorizeCourse($course);
        return view('instructor::groups.edit', compact('course'));
    }

    /**
     * Update the specified group in storage
     */
    public function update(StoreGroupRequest $request, Course $course)
    {
        $this->authorizeCourse($course);
        $validated = $request->validated();
        $course->update($validated);
        return redirect()->route('instructor.groups.list')->with('success', __('instructor::messages.group_updated', ['title' => $course->title]));
    }

    /**
     * Rotate the registration link for a group
     */
    public function rotateLink(Course $course)
    {
        $this->authorizeCourse($course);
        $course->update(['registration_token' => \Illuminate\Support\Str::random(16)]);
        return back()->with('success', __('instructor::messages.link_rotated', ['title' => $course->title]));
    }

    /**
     * Duplicate a group
     */
    public function duplicate(Course $course)
    {
        $this->authorizeCourse($course);

        $newCourse = $course->replicate();
        $newCourse->title = $course->title . __('instructor::messages.copy_suffix');
        $newCourse->registration_token = \Illuminate\Support\Str::random(16);
        $newCourse->save();

        return redirect()->route('instructor.groups.list')->with('success', __('instructor::messages.group_duplicated', ['title' => $newCourse->title]));
    }

    /**
     * Remove the specified group from storage
     */
    public function destroy(Course $course)
    {
        $this->authorizeCourse($course);
        $course->delete();
        return redirect()->route('instructor.groups.list')->with('success', __('instructor::messages.group_deleted', ['title' => $course->title]));
    }
}
