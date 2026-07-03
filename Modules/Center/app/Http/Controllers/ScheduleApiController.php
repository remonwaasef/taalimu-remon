<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ScheduleConflictService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleApiController extends Controller
{
    protected $conflictService;

    public function __construct(ScheduleConflictService $conflictService)
    {
        $this->conflictService = $conflictService;
    }

    /**
     * Check for schedule conflicts via AJAX.
     */
    public function checkConflict(Request $request): JsonResponse
    {
        $request->validate([
            'day_of_week' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'instructor_id' => 'nullable|exists:instructors,id',
            'exclude_course_id' => 'nullable|integer',
        ]);

        $scheduleData = [
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'classroom_id' => $request->classroom_id,
            'instructor_id' => $request->instructor_id,
        ];

        $excludeCourseId = $request->exclude_course_id;

        $conflicts = $this->conflictService->validateSchedule($scheduleData, $excludeCourseId);

        if (empty($conflicts)) {
            return response()->json([
                'status' => 'available',
                'message' => '✅ '.__('center::schedules.schedule_available'),
                'conflicts' => [],
            ]);
        }

        return response()->json([
            'status' => 'conflict',
            'message' => '❌ '.__('center::schedules.schedule_conflict_short'),
            'conflicts' => $conflicts,
        ]);
    }

    /**
     * Get available time slots for a classroom on a given day.
     */
    public function getAvailableSlots(Request $request): JsonResponse
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'day_of_week' => 'required|integer|min:0|max:6',
        ]);

        $slots = $this->conflictService->getAvailableSlots(
            $request->classroom_id,
            $request->day_of_week
        );

        return response()->json([
            'slots' => $slots,
        ]);
    }
}
