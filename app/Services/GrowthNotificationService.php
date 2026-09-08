<?php

namespace App\Services;

use App\Models\Course;
use App\Models\DemandRequest;
use App\Models\TeacherNotification;
use App\Models\User;
use App\Models\Waitlist;

class GrowthNotificationService
{
    public function notifyNewDemand(User $user, DemandRequest $demand, int $tenantId): void
    {
        TeacherNotification::create([
            'tenant_id' => $tenantId,
            'user_id' => $user->id,
            'type' => 'new_demand',
            'title' => 'New demand request',
            'message' => "{$demand->name} is interested in {$demand->subject}.",
            'data' => [
                'demand_id' => $demand->id,
                'subject' => $demand->subject,
                'level' => $demand->level,
            ],
        ]);
    }

    public function notifyNewRegistration(User $user, Course $course, string $studentName, int $tenantId): void
    {
        TeacherNotification::create([
            'tenant_id' => $tenantId,
            'user_id' => $user->id,
            'type' => 'new_registration',
            'title' => 'New student registered',
            'message' => "{$studentName} enrolled in {$course->title}.",
            'data' => [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'student_name' => $studentName,
            ],
        ]);
    }

    public function notifyWaitlistThreshold(User $user, Course $course, int $waitlistCount, int $tenantId): void
    {
        if ($waitlistCount < 3) {
            return;
        }

        TeacherNotification::create([
            'tenant_id' => $tenantId,
            'user_id' => $user->id,
            'type' => 'waitlist_threshold',
            'title' => 'Waitlist growing',
            'message' => "{$waitlistCount} students are waiting for {$course->title}. Consider adding another session.",
            'data' => [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'waitlist_count' => $waitlistCount,
            ],
        ]);
    }

    public function notifyProfileViewMilestone(User $user, int $viewCount, int $tenantId): void
    {
        $milestones = [10, 25, 50, 100, 250, 500, 1000];

        if (! in_array($viewCount, $milestones)) {
            return;
        }

        TeacherNotification::create([
            'tenant_id' => $tenantId,
            'user_id' => $user->id,
            'type' => 'view_milestone',
            'title' => 'Profile milestone reached',
            'message' => "Your profile has been viewed {$viewCount} times!",
            'data' => [
                'view_count' => $viewCount,
            ],
        ]);
    }

    public function getUnreadCount(User $user, int $tenantId): int
    {
        return TeacherNotification::where('tenant_id', $tenantId)
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    public function getRecent(User $user, int $tenantId, int $limit = 10): \Illuminate\Support\Collection
    {
        return TeacherNotification::where('tenant_id', $tenantId)
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function markAllAsRead(User $user, int $tenantId): void
    {
        TeacherNotification::where('tenant_id', $tenantId)
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
