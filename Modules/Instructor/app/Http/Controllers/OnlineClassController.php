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

        $courses = $instructor ? $instructor->courses()->with('students')->get() : Course::with('students')->get();
        $zoomConfigured = app(ZoomService::class)->isConfigured();

        $tenantSettings = app('tenant')?->settings ?? [];
        $defaultMeetingLink = $instructor?->default_meeting_link ?? ($tenantSettings['default_meeting_link'] ?? null);

        return view('instructor::online_classes.create', compact('courses', 'zoomConfigured', 'defaultMeetingLink'));
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
            'platform' => 'required|string|in:in_app,zoom,manual',
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

        $validated['platform'] = $validated['platform'] ?? 'in_app';
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

        $courses = $instructor ? $instructor->courses()->with('students')->get() : Course::with('students')->get();

        $selectedStudentIds = $onlineClass->access_mode === 'selected'
            ? $onlineClass->participants()->pluck('student_id')->toArray()
            : [];
        $zoomConfigured = app(ZoomService::class)->isConfigured();

        return view('instructor::online_classes.edit', compact('onlineClass', 'courses', 'selectedStudentIds', 'zoomConfigured'));
    }

    public function update(Request $request, OnlineClass $onlineClass)
    {
        $this->authorize('update', $onlineClass);

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'platform' => 'required|string|in:in_app,zoom,manual',
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

        $validated['platform'] = $validated['platform'] ?? 'in_app';
        $validated['auto_recording'] = $request->boolean('auto_recording');

        try {
            $this->service->updateClass($onlineClass, $validated);

            return redirect()->route('instructor.online_classes.show', $onlineClass)
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
     * Show the live classroom page (host view with In-App Studio or Meeting SDK).
     */
    public function show(OnlineClass $onlineClass)
    {
        $this->authorize('view', $onlineClass);

        $joinContext = null;

        if ($onlineClass->platform === 'zoom' && app(ZoomService::class)->isConfigured() && $onlineClass->meeting_id) {
            $joinContext = app(ZoomService::class)->getJoinContext($onlineClass, auth()->user(), true);
        }

        $participants = $onlineClass->participants()->with('student')->get();
        $tenantSettings = app('tenant')?->settings ?? [];
        $defaultMeetingLink = auth()->user()->instructor?->default_meeting_link ?? ($tenantSettings['default_meeting_link'] ?? null);

        // Load recent interactive items
        $messages = $onlineClass->messages()->with('user')->take(50)->get()->reverse()->values();
        $questions = $onlineClass->questions()->with(['user', 'student'])->get();
        $handRaises = $onlineClass->handRaises()->with(['user', 'student'])->get();

        return view('instructor::online_classes.show', compact('onlineClass', 'joinContext', 'participants', 'defaultMeetingLink', 'messages', 'questions', 'handRaises'));
    }

    // ==========================================
    // Interactive Classroom Endpoints (Chat, Q&A, Hand Raises)
    // ==========================================

    public function getMessages(OnlineClass $onlineClass)
    {
        $this->authorize('view', $onlineClass);

        $messages = $onlineClass->messages()
            ->with('user:id,name')
            ->take(50)
            ->get()
            ->reverse()
            ->values()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'user_id' => $m->user_id,
                    'user_name' => $m->user?->name ?? 'مستخدم',
                    'is_me' => $m->user_id === auth()->id(),
                    'is_instructor' => $m->user_id === $m->onlineClass?->instructor?->user_id,
                    'message' => $m->message,
                    'time' => $m->created_at->format('H:i'),
                ];
            });

        return response()->json(['messages' => $messages]);
    }

    public function sendMessage(Request $request, OnlineClass $onlineClass)
    {
        $this->authorize('view', $onlineClass);

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = $onlineClass->messages()->create([
            'tenant_id' => $onlineClass->tenant_id,
            'user_id' => auth()->id(),
            'message' => trim($validated['message']),
            'type' => 'text',
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'user_id' => $message->user_id,
                'user_name' => auth()->user()->name,
                'is_me' => true,
                'is_instructor' => true,
                'message' => $message->message,
                'time' => $message->created_at->format('H:i'),
            ],
        ]);
    }

    public function getQuestions(OnlineClass $onlineClass)
    {
        $this->authorize('view', $onlineClass);

        $questions = $onlineClass->questions()
            ->with(['user:id,name', 'student:id,name'])
            ->get()
            ->map(function ($q) {
                return [
                    'id' => $q->id,
                    'user_name' => $q->student?->name ?? $q->user?->name ?? 'طالب',
                    'question' => $q->question,
                    'upvotes' => $q->upvotes_count,
                    'is_answered' => (bool) $q->is_answered,
                    'time' => $q->created_at->diffForHumans(),
                ];
            });

        return response()->json(['questions' => $questions]);
    }

    public function askQuestion(Request $request, OnlineClass $onlineClass)
    {
        $this->authorize('view', $onlineClass);

        $validated = $request->validate([
            'question' => 'required|string|max:500',
        ]);

        $user = auth()->user();
        $student = $user->student;

        $question = $onlineClass->questions()->create([
            'tenant_id' => $onlineClass->tenant_id,
            'user_id' => $user->id,
            'student_id' => $student?->id,
            'question' => trim($validated['question']),
            'upvotes_count' => 0,
            'is_answered' => false,
        ]);

        return response()->json([
            'success' => true,
            'question' => [
                'id' => $question->id,
                'user_name' => $student?->name ?? $user->name,
                'question' => $question->question,
                'upvotes' => 0,
                'is_answered' => false,
                'time' => 'الآن',
            ],
        ]);
    }

    public function upvoteQuestion(Request $request, OnlineClass $onlineClass, \App\Models\OnlineClassQuestion $question)
    {
        $this->authorize('view', $onlineClass);

        $question->increment('upvotes_count');

        return response()->json([
            'success' => true,
            'upvotes' => $question->upvotes_count,
        ]);
    }

    public function toggleAnswerQuestion(Request $request, OnlineClass $onlineClass, \App\Models\OnlineClassQuestion $question)
    {
        $this->authorize('update', $onlineClass);

        $question->update([
            'is_answered' => ! $question->is_answered,
            'answered_at' => ! $question->is_answered ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'is_answered' => $question->is_answered,
        ]);
    }

    public function getHandRaises(OnlineClass $onlineClass)
    {
        $this->authorize('view', $onlineClass);

        $hands = $onlineClass->handRaises()
            ->with(['user:id,name', 'student:id,name'])
            ->get()
            ->map(function ($h) {
                return [
                    'id' => $h->id,
                    'student_name' => $h->student?->name ?? $h->user?->name ?? 'طالب',
                    'time' => $h->created_at->diffForHumans(),
                ];
            });

        return response()->json(['hand_raises' => $hands]);
    }

    public function raiseHand(Request $request, OnlineClass $onlineClass)
    {
        $this->authorize('view', $onlineClass);

        $user = auth()->user();
        $student = $user->student;

        $hand = $onlineClass->handRaises()->firstOrCreate([
            'tenant_id' => $onlineClass->tenant_id,
            'online_class_id' => $onlineClass->id,
            'user_id' => $user->id,
            'status' => 'raised',
        ], [
            'student_id' => $student?->id,
            'raised_at' => now(),
        ]);

        return response()->json(['success' => true, 'id' => $hand->id]);
    }

    public function acknowledgeHandRaise(Request $request, OnlineClass $onlineClass, \App\Models\OnlineClassHandRaise $handRaise)
    {
        $this->authorize('update', $onlineClass);

        $handRaise->update(['status' => 'lowered']);

        return response()->json(['success' => true]);
    }
}