<?php

namespace App\Services;

use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleConflictService
{
    /**
     * Check for any conflicts with the given schedule data.
     * Returns an array of conflict messages, empty if no conflicts.
     *
     * @param array $scheduleData
     * @param int|null $excludeCourseId Exclude schedules from this course (for updates)
     * @return array Array of conflict messages
     */
    public function validateSchedule(array $scheduleData, ?int $excludeCourseId = null): array
    {
        $conflicts = [];

        $classroomId = $scheduleData['classroom_id'] ?? null;
        $instructorId = $scheduleData['instructor_id'] ?? null;
        $dayOfWeek = $this->normalizeDayOfWeek($scheduleData['day_of_week'] ?? null);
        $startTime = $scheduleData['start_time'] ?? null;
        $endTime = $scheduleData['end_time'] ?? null;

        if (!$dayOfWeek || !$startTime || !$endTime) {
            return $conflicts;
        }

        // Check classroom conflict
        if ($classroomId) {
            $classroomConflict = $this->checkClassroomConflict(
                $classroomId, $dayOfWeek, $startTime, $endTime, $excludeCourseId
            );
            if ($classroomConflict) {
                $conflicts[] = $classroomConflict;
            }
        }

        // Check instructor conflict
        if ($instructorId) {
            $instructorConflict = $this->checkInstructorConflict(
                $instructorId, $dayOfWeek, $startTime, $endTime, $excludeCourseId
            );
            if ($instructorConflict) {
                $conflicts[] = $instructorConflict;
            }
        }

        return $conflicts;
    }

    /**
     * Check if a classroom is already booked at the given time.
     */
    public function checkClassroomConflict(
        int $classroomId,
        int $dayOfWeek,
        string $startTime,
        string $endTime,
        ?int $excludeCourseId = null
    ): ?string {
        $query = Schedule::where('classroom_id', $classroomId)
            ->where('day_of_week', $dayOfWeek)
            ->where('tenant_id', \Modules\Tenancy\app\Services\TenantResolver::get()->id)
            ->where(function ($q) use ($startTime, $endTime) {
                // Check for time overlap
                $q->where(function ($inner) use ($startTime, $endTime) {
                    $inner->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                });
            });

        if ($excludeCourseId) {
            $query->where('course_id', '!=', $excludeCourseId);
        }

        $conflicting = $query->with(['course', 'classroom'])->first();

        if ($conflicting) {
            $courseName = $conflicting->course->title ?? 'دورة غير معروفة';
            $classroomName = $conflicting->classroom->name ?? 'القاعة';
            $startTimeFormatted = Carbon::parse($conflicting->start_time)->format('h:i A');
            $endTimeFormatted = Carbon::parse($conflicting->end_time)->format('h:i A');
            
            return __('center::schedules.classroom_conflict_detailed', [
                'classroom' => $classroomName,
                'course' => $courseName,
                'start' => $startTimeFormatted,
                'end' => $endTimeFormatted
            ]);
        }

        return null;
    }

    /**
     * Check if an instructor is already assigned at the given time.
     */
    public function checkInstructorConflict(
        int $instructorId,
        int $dayOfWeek,
        string $startTime,
        string $endTime,
        ?int $excludeCourseId = null
    ): ?string {
        $query = Schedule::where('instructor_id', $instructorId)
            ->where('day_of_week', $dayOfWeek)
            ->where('tenant_id', \Modules\Tenancy\app\Services\TenantResolver::get()->id)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($inner) use ($startTime, $endTime) {
                    $inner->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                });
            });

        if ($excludeCourseId) {
            $query->where('course_id', '!=', $excludeCourseId);
        }

        $conflicting = $query->with(['course', 'instructor'])->first();

        if ($conflicting) {
            $courseName = $conflicting->course->title ?? 'دورة غير معروفة';
            $instructorName = $conflicting->instructor->name ?? 'المدرس';
            $startTimeFormatted = Carbon::parse($conflicting->start_time)->format('h:i A');
            $endTimeFormatted = Carbon::parse($conflicting->end_time)->format('h:i A');
            
            return __('center::schedules.instructor_conflict_detailed', [
                'instructor' => $instructorName,
                'course' => $courseName,
                'start' => $startTimeFormatted,
                'end' => $endTimeFormatted
            ]);
        }

        return null;
    }

    /**
     * Normalize day of week from string or integer to integer.
     */
    protected function normalizeDayOfWeek($day): ?int
    {
        if (is_numeric($day)) {
            return (int) $day;
        }

        $mapping = [
            'sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3,
            'thursday' => 4, 'friday' => 5, 'saturday' => 6,
            'الأحد' => 0, 'الاثنين' => 1, 'الثلاثاء' => 2, 'الأربعاء' => 3,
            'الخميس' => 4, 'الجمعة' => 5, 'السبت' => 6,
        ];

        return $mapping[strtolower($day)] ?? null;
    }

    /**
     * Get available time slots for a classroom on a given day.
     */
    public function getAvailableSlots(int $classroomId, int $dayOfWeek): array
    {
        $bookedSlots = Schedule::where('classroom_id', $classroomId)
            ->where('day_of_week', $dayOfWeek)
            ->where('tenant_id', \Modules\Tenancy\app\Services\TenantResolver::get()->id)
            ->orderBy('start_time')
            ->get(['start_time', 'end_time']);

        // Generate available slots (simplified: 8 AM to 10 PM)
        $allSlots = [];
        $workStart = Carbon::createFromTimeString('08:00');
        $workEnd = Carbon::createFromTimeString('22:00');
        
        $current = $workStart->copy();
        while ($current < $workEnd) {
            $slotEnd = $current->copy()->addHours(2);
            $isAvailable = true;

            foreach ($bookedSlots as $booked) {
                $bookedStart = Carbon::parse($booked->start_time);
                $bookedEnd = Carbon::parse($booked->end_time);
                
                if ($current < $bookedEnd && $slotEnd > $bookedStart) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $allSlots[] = [
                    'start' => $current->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                ];
            }

            $current->addHour();
        }

        return $allSlots;
    }
}
