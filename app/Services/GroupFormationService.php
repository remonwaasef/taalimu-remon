<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Opportunity;
use App\Models\OpportunityGroup;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class GroupFormationService
{
    /**
     * Attribution markers stored on enrollments created through the Phase 3
     * growth loop. The enrollments table has no opportunity_id/student_id
     * columns, so group membership is identified by (course_id, source, campaign).
     */
    public const ATTRIBUTION_SOURCE = 'growth_opportunity';

    public function enrollmentCampaign(\App\Models\OpportunityGroup $group): string
    {
        return 'opportunity:' . $group->opportunity_id;
    }

    public function createGroup(\App\Models\Opportunity $opportunity, array $data): \App\Models\OpportunityGroup
    {
        return \DB::transaction(function () use ($opportunity, $data) {
            if (!$opportunity->canFormGroup()) {
                throw new \InvalidArgumentException('Opportunity cannot form group in current state');
            }

            $course = \App\Models\Course::findOrFail($data['course_id']);

            if ($course->tenant_id !== $opportunity->tenant_id) {
                throw new \InvalidArgumentException('Course must belong to the same tenant');
            }

            if ($course->enrolled_count >= $course->capacity) {
                throw new \InvalidArgumentException('Course is at full capacity');
            }

            // Check if group already exists for this opportunity
            $existingGroup = \App\Models\OpportunityGroup::where('opportunity_id', $opportunity->id)
                ->where('course_id', $course->id)
                ->first();

            if ($existingGroup) {
                throw new \InvalidArgumentException('Group already exists for this course');
            }

            $group = \App\Models\OpportunityGroup::create([
                'tenant_id' => $opportunity->tenant_id,
                'opportunity_id' => $opportunity->id,
                'course_id' => $course->id,
                'student_count' => 0,
                'status' => 'forming',
            ]);

            // Start group formation on opportunity
            $opportunity->startGroupFormation();

            return $group->fresh();
        });
    }

    public function addStudentToGroup(\App\Models\OpportunityGroup $group, int $studentId, int $enrolledById): \App\Models\Enrollment
    {
        return \DB::transaction(function () use ($group, $studentId, $enrolledById) {
            $group = \App\Models\OpportunityGroup::where('id', $group->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($group->status !== 'forming') {
                throw new \InvalidArgumentException('Group is not in forming state');
            }

            $course = \App\Models\Course::where('id', $group->course_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($course->tenant_id !== $group->opportunity->tenant_id) {
                throw new \InvalidArgumentException('Course must belong to the same tenant');
            }

            if ($course->enrolled_count >= $course->capacity) {
                throw new \InvalidArgumentException('Course is at full capacity');
            }

            $student = \App\Models\Student::findOrFail($studentId);

            if ($student->tenant_id !== $group->opportunity->tenant_id) {
                throw new \InvalidArgumentException('Student must belong to the same tenant');
            }

            if (!$student->user_id) {
                throw new \InvalidArgumentException('Student has no linked user account');
            }

            $enrolledBy = \App\Models\Instructor::findOrFail($enrolledById);

            if ($enrolledBy->tenant_id !== $group->opportunity->tenant_id) {
                throw new \InvalidArgumentException('Enrolling teacher must belong to the same tenant');
            }

            // Canonical enrollment identity is (tenant_id, user_id, course_id).
            // The enrollments table has no student_id column.
            $existing = \App\Models\Enrollment::where('tenant_id', $group->opportunity->tenant_id)
                ->where('course_id', $group->course_id)
                ->where('user_id', $student->user_id)
                ->where('status', '!=', 'cancelled')
                ->first();

            if ($existing) {
                throw new \InvalidArgumentException('Student is already enrolled in this course');
            }

            // Check group capacity
            if ($group->student_count >= $course->capacity) {
                throw new \InvalidArgumentException('Group is at full capacity');
            }

            // A previously cancelled enrollment for the same (tenant, user, course)
            // still occupies the DB-level unique key: reactivate it instead of
            // inserting a duplicate row.
            $cancelled = \App\Models\Enrollment::where('tenant_id', $group->opportunity->tenant_id)
                ->where('course_id', $group->course_id)
                ->where('user_id', $student->user_id)
                ->where('status', 'cancelled')
                ->lockForUpdate()
                ->first();

            try {
                if ($cancelled) {
                    $cancelled->update([
                        'status' => 'active',
                        'enrolled_at' => now(),
                        'source' => self::ATTRIBUTION_SOURCE,
                        'campaign' => $this->enrollmentCampaign($group),
                    ]);
                    $enrollment = $cancelled->fresh();
                } else {
                    $enrollment = \App\Models\Enrollment::create([
                        'tenant_id' => $group->opportunity->tenant_id,
                        'course_id' => $group->course_id,
                        'user_id' => $student->user_id,
                        'status' => 'active',
                        'enrolled_at' => now(),
                        'source' => self::ATTRIBUTION_SOURCE,
                        'campaign' => $this->enrollmentCampaign($group),
                    ]);
                }
            } catch (\Illuminate\Database\QueryException $e) {
                throw new \InvalidArgumentException('Student is already enrolled in this course');
            }

            // Update group student count and keep the course counter in sync.
            // increment() refreshes the in-memory attribute and both rows are
            // locked, so these values are current within this transaction.
            $group->increment('student_count');
            $course->increment('enrolled_count');

            // Check if group is now full
            if ($group->student_count >= $course->capacity) {
                $group->update(['status' => 'filled']);
            }

            // Create notification for student/parent
            $this->notifyStudentEnrolled($group, $student);

            // Attribute the enrollment to the student's demand request, if any.
            // Only the matching demand converts — never the whole opportunity.
            $this->attributeDemandConversion($group, $student, $enrollment);

            return $enrollment;
        });
    }

    /**
     * Link an enrollment back to the exact demand request that produced it.
     *
     * Match priority: linked user → email → phone, scoped to the same tenant,
     * subject (and level when the demand specifies one), considering only
     * demands that have not converted yet. Walk-in students without a demand
     * simply produce no attribution row.
     */
    protected function attributeDemandConversion(
        \App\Models\OpportunityGroup $group,
        \App\Models\Student $student,
        \App\Models\Enrollment $enrollment
    ): void {
        $opportunity = $group->opportunity;

        $candidates = \App\Models\DemandRequest::where('tenant_id', $opportunity->tenant_id)
            ->where('subject', $opportunity->subject)
            ->whereIn('status', ['new', 'contacted'])
            ->orderBy('created_at')
            ->get();

        if ($candidates->isEmpty()) {
            return;
        }

        $student->loadMissing('user');

        $match = $candidates->first(function ($demand) use ($student, $opportunity) {
            // Level must agree when the demand specifies one.
            if ($demand->level && $opportunity->level && $demand->level !== $opportunity->level) {
                return false;
            }

            if ($demand->user_id && $student->user_id && (int) $demand->user_id === (int) $student->user_id) {
                return true;
            }

            $demandEmail = $demand->email ? mb_strtolower(trim($demand->email)) : null;
            $studentEmails = array_filter([
                $student->email ? mb_strtolower(trim($student->email)) : null,
                $student->user?->email ? mb_strtolower(trim($student->user->email)) : null,
            ]);

            if ($demandEmail && in_array($demandEmail, $studentEmails, true)) {
                return true;
            }

            $demandPhone = $demand->phone ? trim($demand->phone) : null;
            $studentPhones = array_filter([
                $student->phone ? trim($student->phone) : null,
                $student->user?->phone ? trim($student->user->phone) : null,
            ]);

            return (bool) ($demandPhone && in_array($demandPhone, $studentPhones, true));
        });

        if (!$match) {
            return;
        }

        $match->update([
            'status' => 'converted',
            'converted_at' => now(),
            'course_id' => $group->course_id,
        ]);

        try {
            \App\Models\DemandAttribution::create([
                'tenant_id' => $opportunity->tenant_id,
                'demand_request_id' => $match->id,
                'opportunity_id' => $opportunity->id,
                'enrollment_id' => $enrollment->id,
                'converted_at' => now(),
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Unique demand_request_id: another concurrent enrollment already
            // converted this demand. The demand row itself is already flipped.
        }
    }

    /**
     * Real conversion rate for an opportunity: converted demands that carry
     * an attribution row for this opportunity over its demand volume.
     */
    public function conversionRateForOpportunity(\App\Models\Opportunity $opportunity): float
    {
        $volume = max(1, (int) $opportunity->demand_volume);

        $converted = \App\Models\DemandAttribution::where('tenant_id', $opportunity->tenant_id)
            ->where('opportunity_id', $opportunity->id)
            ->count();

        return round(($converted / $volume) * 100, 1);
    }

    public function removeStudentFromGroup(\App\Models\OpportunityGroup $group, int $studentId): bool
    {
        return \DB::transaction(function () use ($group, $studentId) {
            $group = \App\Models\OpportunityGroup::where('id', $group->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($group->status, ['forming', 'filled'])) {
                throw new \InvalidArgumentException('Cannot remove student from group in current state');
            }

            $student = \App\Models\Student::findOrFail($studentId);

            if ($student->tenant_id !== $group->opportunity->tenant_id) {
                throw new \InvalidArgumentException('Student must belong to the same tenant');
            }

            $enrollment = \App\Models\Enrollment::where('tenant_id', $group->opportunity->tenant_id)
                ->where('course_id', $group->course_id)
                ->where('user_id', $student->user_id)
                ->where('source', self::ATTRIBUTION_SOURCE)
                ->where('campaign', $this->enrollmentCampaign($group))
                ->where('status', 'active')
                ->lockForUpdate()
                ->firstOrFail();

            $enrollment->update(['status' => 'cancelled']);

            $group->decrement('student_count');

            $course = \App\Models\Course::where('id', $group->course_id)
                ->lockForUpdate()
                ->firstOrFail();
            $course->decrement('enrolled_count');

            if ($group->status === 'filled' && $group->student_count < $course->capacity) {
                $group->update(['status' => 'forming']);
            }

            return true;
        });
    }

    public function completeGroup(\App\Models\OpportunityGroup $group): \App\Models\OpportunityGroup
    {
        return \DB::transaction(function () use ($group) {
            $group = \App\Models\OpportunityGroup::where('id', $group->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($group->status !== 'forming') {
                throw new \InvalidArgumentException('Group is not in forming state');
            }

            if ($group->student_count === 0) {
                throw new \InvalidArgumentException('Cannot complete group with no students');
            }

            $group->update(['status' => 'filled']);

            // Complete group formation on opportunity
            $opportunity = $group->opportunity;
            $opportunity->completeGroupFormation();

            // A full group satisfies the underlying demand
            $courseCapacity = $group->course?->capacity ?? 0;
            if ($courseCapacity > 0 && $group->student_count >= $courseCapacity) {
                $opportunity->markAsFilled();
            }

            // Notify all enrolled students
            $this->notifyGroupCompleted($group);

            return $group->fresh();
        });
    }

    public function getGroupDetails(\App\Models\OpportunityGroup $group): array
    {
        $group->load(['course', 'course.instructor', 'opportunity', 'enrollments.user.student']);

        $students = $group->groupEnrollments()
            ->filter(fn ($enrollment) => $enrollment->status === 'active')
            ->map(function ($enrollment) {
                $student = $enrollment->user?->student;
                $taalimuSourced = $enrollment->source === self::ATTRIBUTION_SOURCE;

                return [
                    'id' => $student?->id,
                    'name' => $student?->name,
                    'email' => $enrollment->user?->email,
                    'phone' => $enrollment->user?->phone,
                    'enrolled_at' => $enrollment->enrolled_at,
                    'status' => $enrollment->status,
                    'source' => $enrollment->source,
                    'is_taalimu_sourced' => $taalimuSourced,
                    'source_label' => $taalimuSourced ? 'Taalimu' : '—',
                ];
            })
            ->values();

        return [
            'group' => $group,
            'course' => $group->course,
            'teacher' => $group->course->instructor,
            'opportunity' => $group->opportunity,
            'students' => $students,
            'taalimu_sourced_count' => $students->where('is_taalimu_sourced', true)->count(),
            'capacity' => $group->course->capacity,
            'enrolled_count' => $group->student_count,
            'available_seats' => max(0, $group->course->capacity - $group->student_count),
            'fill_percentage' => $group->course->capacity > 0
                ? round(($group->student_count / $group->course->capacity) * 100, 1)
                : 0,
        ];
    }

    public function getGroupsForTeacher(int $teacherId, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $teacher = \App\Models\Instructor::findOrFail($teacherId);

        $query = \App\Models\OpportunityGroup::whereHas('opportunity', function ($q) use ($teacherId) {
            $q->where('matched_teacher_id', $teacherId);
        })
            ->with(['course', 'course.instructor', 'opportunity', 'enrollments.user.student'])
            ->orderByDesc('created_at');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        return $query->paginate(20);
    }

    public function getGroupsForOpportunity(\App\Models\Opportunity $opportunity): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return \App\Models\OpportunityGroup::where('opportunity_id', $opportunity->id)
            ->with(['course', 'course.instructor', 'enrollments.user.student'])
            ->orderByDesc('created_at')
            ->paginate(20);
    }

    public function getGroupStudents(\App\Models\OpportunityGroup $group): \Illuminate\Database\Eloquent\Collection
    {
        $group->loadMissing(['enrollments.user.student']);

        return $group->groupEnrollments()
            ->filter(fn ($enrollment) => $enrollment->status === 'active')
            ->map(function ($enrollment) {
                $student = $enrollment->user?->student;

                return [
                    'enrollment_id' => $enrollment->id,
                    'student_id' => $student?->id,
                    'name' => $student?->name,
                    'email' => $enrollment->user?->email,
                    'phone' => $enrollment->user?->phone,
                    'enrolled_at' => $enrollment->enrolled_at,
                ];
            })
            ->values();
    }

    protected function notifyStudentEnrolled(\App\Models\OpportunityGroup $group, \App\Models\Student $student): void
    {
        if ($student->user_id && $student->user) {
            app(\App\Services\GrowthNotificationService::class)->send(
                $group->opportunity->tenant_id,
                $student->user_id,
                'group_enrollment_confirmed',
                [
                    'group_id' => $group->id,
                    'course_title' => $group->course->title,
                    'teacher_name' => $group->course->instructor?->name ?? 'Teacher',
                    'message' => "You have been enrolled in {$group->course->title}",
                ]
            );
        }
    }

    protected function notifyGroupCompleted(\App\Models\OpportunityGroup $group): void
    {
        $group->loadMissing(['enrollments.user']);

        $enrollments = $group->groupEnrollments()
            ->filter(fn ($enrollment) => $enrollment->status === 'active')
            ->values();

        $reviewUrl = $this->reviewUrlForGroup($group);

        foreach ($enrollments as $enrollment) {
            if ($enrollment->user_id && $enrollment->user) {
                $notifications = app(\App\Services\GrowthNotificationService::class);
                $notifications->send(
                    $group->opportunity->tenant_id,
                    $enrollment->user_id,
                    'group_completed',
                    [
                        'group_id' => $group->id,
                        'course_title' => $group->course->title,
                        'teacher_name' => $group->course->instructor?->name ?? 'Teacher',
                        'message' => "Your group for {$group->course->title} is now complete!",
                    ]
                );

                // Evidence loop: prompt every placed student to rate the teacher.
                // Reviews themselves stay manual (one per reviewer per profile).
                $notifications->send(
                    $group->opportunity->tenant_id,
                    $enrollment->user_id,
                    'review_requested',
                    [
                        'group_id' => $group->id,
                        'course_id' => $group->course_id,
                        'course_title' => $group->course->title,
                        'teacher_name' => $group->course->instructor?->name ?? 'Teacher',
                        'enrollment_id' => $enrollment->id,
                        'review_url' => $reviewUrl,
                        'message' => "How was {$group->course->title}? Rate your teacher and help others discover great teaching.",
                    ]
                );
            }
        }
    }

    /**
     * Public review page for the group's teacher, if they have a published
     * profile. Null when unavailable — the prompt is still sent without a link.
     */
    protected function reviewUrlForGroup(\App\Models\OpportunityGroup $group): ?string
    {
        $teacher = $group->course?->instructor;

        if (!$teacher) {
            return null;
        }

        $slug = \App\Models\PublicProfile::where('tenant_id', $group->opportunity->tenant_id)
            ->where('profilable_type', \App\Models\Instructor::class)
            ->where('profilable_id', $teacher->id)
            ->where('published', true)
            ->value('slug');

        if (!$slug) {
            return null;
        }

        try {
            return route('growth.reviews.index', $slug);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function getGroupStats(int $teacherId): array
    {
        $teacher = \App\Models\Instructor::findOrFail($teacherId);

        $totalGroups = \App\Models\OpportunityGroup::whereHas('opportunity', function ($q) use ($teacherId) {
            $q->where('matched_teacher_id', $teacherId);
        })->count();

        $formingGroups = \App\Models\OpportunityGroup::whereHas('opportunity', function ($q) use ($teacherId) {
            $q->where('matched_teacher_id', $teacherId);
        })->where('status', 'forming')->count();

        $filledGroups = \App\Models\OpportunityGroup::whereHas('opportunity', function ($q) use ($teacherId) {
            $q->where('matched_teacher_id', $teacherId);
        })->where('status', 'filled')->count();

        $totalStudents = \App\Models\OpportunityGroup::whereHas('opportunity', function ($q) use ($teacherId) {
            $q->where('matched_teacher_id', $teacherId);
        })->sum('student_count');

        $avgFillRate = $totalStudents > 0
            ? round(($teacher->courses()->where('published', true)->avg('enrolled_count') / $teacher->courses()->where('published', true)->avg('capacity')) * 100, 1)
            : 0;

        return [
            'total_groups' => $totalGroups,
            'forming_groups' => $formingGroups,
            'filled_groups' => $filledGroups,
            'total_students' => $totalStudents,
            'avg_fill_rate' => $avgFillRate,
        ];
    }
}