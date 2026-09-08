<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Waitlist;
use Illuminate\Support\Facades\DB;

class WaitlistService
{
    public function joinWaitlist(Course $course, array $data, ?string $source = null): Waitlist
    {
        $existing = Waitlist::where('tenant_id', $course->tenant_id)
            ->where('course_id', $course->id)
            ->where('user_id', $data['user_id'] ?? null)
            ->where('phone', $data['phone'] ?? null)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return $existing;
        }

        $nextPosition = (Waitlist::where('tenant_id', $course->tenant_id)
            ->where('course_id', $course->id)
            ->max('position') ?? 0) + 1;

        return Waitlist::create([
            'tenant_id' => $course->tenant_id,
            'course_id' => $course->id,
            'user_id' => $data['user_id'] ?? null,
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'position' => $nextPosition,
            'status' => 'pending',
            'source' => $source,
            'expires_at' => now()->addDays(30),
        ]);
    }

    public function notifyNext(Course $course): ?Waitlist
    {
        $next = Waitlist::where('tenant_id', $course->tenant_id)
            ->where('course_id', $course->id)
            ->where('status', 'pending')
            ->orderBy('position')
            ->first();

        if (! $next) {
            return null;
        }

        $next->update([
            'status' => 'notified',
            'notified_at' => now(),
        ]);

        return $next;
    }

    public function getCount(Course $course): int
    {
        return Waitlist::where('tenant_id', $course->tenant_id)
            ->where('course_id', $course->id)
            ->where('status', 'pending')
            ->count();
    }

    public function getPosition(Waitlist $waitlist): int
    {
        return Waitlist::where('tenant_id', $waitlist->tenant_id)
            ->where('course_id', $waitlist->course_id)
            ->where('status', 'pending')
            ->where('position', '<', $waitlist->position)
            ->count() + 1;
    }

    public function cancelPending(Waitlist $waitlist): void
    {
        $waitlist->update(['status' => 'expired']);
    }
}
