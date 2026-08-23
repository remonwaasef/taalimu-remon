<?php

namespace Modules\Campus\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClassRecording;
use App\Models\OnlineClass;
use App\Services\PlaybackTokenService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StudentClassController extends Controller implements HasMiddleware
{
    public function __construct(protected PlaybackTokenService $tokens)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $user = auth()->user();
                if ($user && ! $user->student) {
                    if ($user->hasRole('instructor')) {
                        return redirect()->route('instructor.dashboard')->with('error', 'هذه الصفحة مخصصة للطلاب فقط.');
                    }
                    return redirect()->route('center.dashboard')->with('error', 'هذه الصفحة مخصصة للطلاب فقط.');
                }
                return $next($request);
            }),
        ];
    }

    /**
     * Dashboard: Live Now + Upcoming + Recorded Lessons grid
     */
    public function index()
    {
        $user = auth()->user();
        $student = $user->student;
        $tenant = app('tenant');

        // Live now / joinable
        $liveClasses = OnlineClass::with(['course', 'instructor'])
            ->where('tenant_id', $tenant->id)
            ->where(function ($q) {
                $q->where('status', OnlineClass::STATUS_LIVE)
                    ->orWhere(function ($q2) {
                        $q2->where('status', OnlineClass::STATUS_SCHEDULED)
                            ->whereRaw('start_time <= ? AND start_time + INTERVAL duration_minutes MINUTE >= ?', [now(), now()]);
                    });
            })
            ->whereHas('allowedUserIds', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->get();

        // Upcoming scheduled
        $upcoming = OnlineClass::with(['course', 'instructor'])
            ->where('tenant_id', $tenant->id)
            ->where('status', OnlineClass::STATUS_SCHEDULED)
            ->where('start_time', '>', now())
            ->whereHas('allowedUserIds', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        // Recorded lessons with progress
        $recordings = ClassRecording::with(['onlineClass.course'])
            ->where('tenant_id', $tenant->id)
            ->where('status', ClassRecording::STATUS_READY)
            ->whereHas('onlineClass', function ($q) use ($user) {
                $q->whereHas('allowedUserIds', function ($q2) use ($user) {
                    $q2->where('user_id', $user->id);
                });
            })
            ->latest('available_at')
            ->get()
            ->map(function ($rec) use ($user) {
                $progress = $rec->progressFor($user->id);
                $rec->user_progress = $progress;

                return $rec;
            });

        return view('campus::classes', compact('student', 'liveClasses', 'upcoming', 'recordings'));
    }

    /**
     * Join a live class (student classroom with Meeting SDK).
     */
    public function join(OnlineClass $class)
    {
        $this->authorize('join', $class);

        if (! app(\App\Services\ZoomService::class)->isConfigured() || ! $class->meeting_id) {
            // Fallback: if manual link, redirect to external
            if ($class->meeting_link) {
                return redirect()->away($class->meeting_link);
            }
            abort(404);
        }

        $joinContext = app(\App\Services\ZoomService::class)->getJoinContext($class, auth()->user(), false);

        return view('campus::live-classroom', compact('class', 'joinContext'));
    }

    /**
     * Watch a recording (secure player page with watermark).
     */
    public function watch(ClassRecording $recording)
    {
        $this->authorize('view', $recording);

        $progress = $recording->progressFor(auth()->id());

        return view('campus::watch', compact('recording', 'progress'));
    }

    /**
     * Issue a short-lived playback token (AJAX).
     */
    public function token(ClassRecording $recording)
    {
        $this->authorize('view', $recording);

        try {
            $token = $this->tokens->issue($recording, auth()->user());

            return response()->json([
                'token' => $token,
                'ttl' => PlaybackTokenService::TTL_SECONDS,
            ]);
        } catch (\App\Exceptions\BusinessException $e) {
            return response()->json(['error' => $e->getMessage()], 429);
        }
    }

    /**
     * Save playback progress (throttled).
     */
    public function progress(Request $request, ClassRecording $recording)
    {
        $this->authorize('view', $recording);

        $request->validate([
            'position_seconds' => 'required|integer|min:0',
            'watched_delta' => 'nullable|integer|min:0',
        ]);

        // Server-side throttle: one save per 10s per user/recording
        $throttleKey = "progress_save:{$recording->id}:".auth()->id();

        if (\Illuminate\Support\Facades\Cache::has($throttleKey)) {
            return response()->json(['message' => 'throttled']);
        }

        \Illuminate\Support\Facades\Cache::put($throttleKey, true, now()->addSeconds(10));

        $progress = \App\Models\VideoProgress::saveProgress(
            $recording,
            auth()->id(),
            $request->integer('position_seconds'),
            $request->integer('watched_delta')
        );

        return response()->json([
            'last_position' => $progress->last_position_seconds,
            'completion' => $progress->completion_percentage,
        ]);
    }
}