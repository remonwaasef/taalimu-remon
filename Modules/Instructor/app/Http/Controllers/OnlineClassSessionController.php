<?php

namespace Modules\Instructor\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OnlineClass;
use App\Services\OnlineClassService;
use App\Services\ZoomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OnlineClassSessionController extends Controller
{
    public function __construct(protected OnlineClassService $service)
    {
    }

    /**
     * Teacher pressed "Start Class".
     */
    public function start(OnlineClass $onlineClass)
    {
        $this->authorize('start', $onlineClass);

        try {
            $this->service->startClass($onlineClass);

            return response()->json(['success' => true, 'status' => $onlineClass->status]);
        } catch (\Throwable $e) {
            Log::error('Start class failed', ['class_id' => $onlineClass->id, 'error' => $e->getMessage()]);

            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Teacher pressed "End Class".
     */
    public function end(OnlineClass $onlineClass)
    {
        $this->authorize('end', $onlineClass);

        try {
            $this->service->endClass($onlineClass);

            return response()->json(['success' => true, 'status' => $onlineClass->status]);
        } catch (\Throwable $e) {
            Log::error('End class failed', ['class_id' => $onlineClass->id, 'error' => $e->getMessage()]);

            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Returns SDK join context (host) for the embedded Meeting SDK.
     */
    public function joinToken(OnlineClass $onlineClass)
    {
        $this->authorize('join', $onlineClass);

        if (! app(ZoomService::class)->isConfigured() || ! $onlineClass->meeting_id) {
            return response()->json(['error' => 'Zoom not configured'], 400);
        }

        $context = app(ZoomService::class)->getJoinContext($onlineClass, auth()->user(), true);

        return response()->json($context);
    }

    /**
     * Quickly update the meeting link directly from the classroom.
     */
    public function updateLink(Request $request, OnlineClass $onlineClass)
    {
        $this->authorize('update', $onlineClass);

        $validated = $request->validate([
            'meeting_link' => 'required|url|max:500',
        ]);

        $onlineClass->forceFill([
            'meeting_link' => $validated['meeting_link'],
            'platform' => 'manual',
        ])->save();

        return response()->json(['success' => true, 'meeting_link' => $onlineClass->meeting_link]);
    }
}