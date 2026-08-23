<?php

namespace Modules\Instructor\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\OnlineClass;
use App\Models\OnlineClassParticipant;
use App\Services\OnlineClassService;
use App\Services\ZoomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OnlineClassController extends Controller
{
    public function __construct(protected OnlineClassService $service)
    {
    }

    public function index()
    {
        $instructor = auth()->user()->instructor;

        $query = OnlineClass::with(['course', 'readyRecording'])
            ->latest('start_time');

        if ($instructor) {
            $query->where('instructor_id', $instructor->id);
        }

        $onlineClasses = $query->paginate(15);

        return view('instructor::online_classes.index', compact('onlineClasses'));
    }

    public function create()
    {
        $instructor = auth()->user()->instructor;

        $courses = $instructor ? $instructor->courses : Course::select('id', 'title')->get();

        return view('instructor::online_classes.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $instructor = auth()->user()->instructor;

        if (! $instructor) {
            return back()->with('error', 'Unauthorized. Must be an instructor.');
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'platform' => 'required|string|in:zoom,manual',
            'meeting_link' => 'nullable|url|required_if:platform,manual',
            'meeting_id' => 'nullable|string|max:255',
            'meeting_password' => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'duration_minutes' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'access_mode' => 'required|in:course,selected',
            'auto_recording' => 'boolean',
            'selected_student_ids' => 'array',
            'selected_student_ids.*' => 'exists:students,id',
        ]);

        $validated['platform'] = $validated['platform'] ?? 'zoom';
        $validated['auto_recording'] = $request->boolean('auto_recording');

        try {
            $class = $this->service->createClass($validated, $instructor->id, $instructor->tenant_id);

            return redirect()->route('instructor.online_classes.index')
                ->with('success', __('instructor::dashboard.online_class_created', ['default' => 'تم إنشاء الحصة بنجاح.']));
        } catch (\Throwable $e) {
            Log::error('Online class create failed', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'تعذر إنشاء الحصة. يرجى المحاولة لاحقاً.');
        }
    }

    public function edit(OnlineClass $onlineClass)
    {
        $this->authorize('update', $onlineClass);

        $instructor = auth()->user()->instructor;

        $courses = $instructor ? $instructor->courses : Course::select('id', 'title')->get();

        $selectedStudentIds = $onlineClass->access_mode === 'selected'
            ? $onlineClass->participants()->pluck('student_id')->toArray()
            : [];

        return view('instructor::online_classes.edit', compact('onlineClass', 'courses', 'selectedStudentIds'));
    }

    public function update(Request $request, OnlineClass $onlineClass)
    {
        $this->authorize('update', $onlineClass);

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'platform' => 'required|string|in:zoom,manual',
            'meeting_link' => 'nullable|url|required_if:platform,manual',
            'meeting_id' => 'nullable|string|max:255',
            'meeting_password' => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'duration_minutes' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'access_mode' => 'required|in:course,selected',
            'auto_recording' => 'boolean',
            'selected_student_ids' => 'array',
            'selected_student_ids.*' => 'exists:students,id',
        ]);

        $validated['platform'] = $validated['platform'] ?? 'zoom';
        $validated['auto_recording'] = $request->boolean('auto_recording');

        try {
            $this->service->updateClass($onlineClass, $validated);

            return redirect()->route('instructor.online_classes.index')
                ->with('success', __('instructor::dashboard.online_class_updated', ['default' => 'تم تحديث الحصة بنجاح.']));
        } catch (\Throwable $e) {
            Log::error('Online class update failed', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'تعذر تحديث الحصة.');
        }
    }

    public function destroy(OnlineClass $onlineClass)
    {
        $this->authorize('delete', $onlineClass);

        $this->service->cancelClass($onlineClass);

        return redirect()->route('instructor.online_classes.index')
            ->with('success', __('instructor::dashboard.online_class_deleted', ['default' => 'تم حذف الحصة بنجاح.']));
    }

    /**
     * Show the live classroom page (host view with Meeting SDK).
     */
    public function show(OnlineClass $onlineClass)
    {
        $this->authorize('view', $onlineClass);

        $joinContext = null;

        if (app(ZoomService::class)->isConfigured() && $onlineClass->meeting_id) {
            $joinContext = app(ZoomService::class)->getJoinContext($onlineClass, auth()->user(), true);
        }

        $participants = $onlineClass->participants()->with('student')->get();

        return view('instructor::online_classes.show', compact('onlineClass', 'joinContext', 'participants'));
    }
}