<?php

namespace Modules\Instructor\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClassRecording;
use App\Models\OnlineClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassRecordingController extends Controller
{
    public function index()
    {
        $instructor = auth()->user()->instructor;

        $recordings = ClassRecording::with(['onlineClass.course'])
            ->whereHas('onlineClass', function ($q) use ($instructor) {
                $q->where('instructor_id', $instructor->id);
            })
            ->latest('available_at')
            ->paginate(15);

        return view('instructor::recordings.index', compact('recordings'));
    }

    public function show(ClassRecording $recording)
    {
        $this->authorize('viewAnalytics', $recording);

        $analytics = $this->getAnalytics($recording);

        return view('instructor::recordings.show', compact('recording', 'analytics'));
    }

    protected function getAnalytics(ClassRecording $recording): array
    {
        $class = $recording->onlineClass;

        if (! $class) {
            return [
                'total_views' => 0,
                'unique_viewers' => 0,
                'avg_watch_seconds' => 0,
                'completion_rate' => 0,
                'non_viewers' => 0,
            ];
        }

        $allowedUsers = $class->allowedUserIds();

        $logs = \App\Models\VideoAccessLog::where('recording_id', $recording->id)
            ->where('action', 'token_issued')
            ->whereIn('user_id', $allowedUsers)
            ->get();

        $uniqueViewers = $logs->unique('user_id')->count();

        $progress = \App\Models\VideoProgress::where('recording_id', $recording->id)
            ->whereIn('user_id', $allowedUsers)
            ->get();

        $avgWatch = $progress->isEmpty() ? 0 : $progress->avg('watched_seconds');
        $completed = $progress->where('completed_at', '!=', null)->count();
        $completionRate = $uniqueViewers > 0 ? round(($completed / $uniqueViewers) * 100, 1) : 0;

        $nonViewers = $allowedUsers->count() - $uniqueViewers;

        return [
            'total_views' => $logs->count(),
            'unique_viewers' => $uniqueViewers,
            'avg_watch_seconds' => round($avgWatch),
            'completion_rate' => $completionRate,
            'non_viewers' => max(0, $nonViewers),
        ];
    }
}