<?php

namespace App\Services;

use App\Models\Course;
use App\Models\DemandAggregation;
use App\Models\DemandRequest;
use App\Models\Instructor;
use App\Models\Opportunity;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class OpportunityService
{
    public function createFromAggregation(DemandAggregation $aggregation): \App\Models\Opportunity
    {
        return \DB::transaction(function () use ($aggregation) {
            // Idempotency: never create a second active opportunity for the same aggregation/subject/level
            $existing = \App\Models\Opportunity::where('tenant_id', $aggregation->tenant_id)
                ->where('demand_aggregation_id', $aggregation->id)
                ->where('subject', $aggregation->subject)
                ->where('level', $aggregation->level)
                ->whereIn('status', ['open', 'matched', 'group_forming', 'group_formed'])
                ->lockForUpdate()
                ->first();

            if ($existing) {
                return $existing;
            }

            $opportunity = \App\Models\Opportunity::create([
                'tenant_id' => $aggregation->tenant_id,
                'demand_aggregation_id' => $aggregation->id,
                'title' => $this->generateTitle($aggregation),
                'subject' => $aggregation->subject,
                'level' => $aggregation->level,
                'description' => $this->generateDescription($aggregation),
                'explanation' => $this->generateExplanation($aggregation),
                'demand_volume' => $aggregation->demand_count,
                'score' => $this->calculateScore($aggregation),
                'score_breakdown' => $this->calculateScoreBreakdown($aggregation),
                'metadata' => array_merge($aggregation->metadata ?? [], [
                    'demand_volume' => $aggregation->demand_count,
                ]),
                'status' => 'open',
            ]);

            // Mark aggregation as completed
            $aggregation->markAsCompleted();

            // Create notification for matched teachers (will be sent when matched)
            // This will be triggered when a teacher is matched

            return $opportunity;
        });
    }

    public function createFromDemandRequest(\App\Models\DemandRequest $demand): \App\Models\Opportunity
    {
        return \DB::transaction(function () use ($demand) {
            // Find or create aggregation
            $aggregation = \App\Models\DemandAggregation::firstOrCreate(
                [
                    'tenant_id' => $demand->tenant_id,
                    'subject' => $demand->subject,
                    'level' => $demand->level,
                ],
                [
                    'tenant_id' => $demand->tenant_id,
                    'subject' => $demand->subject,
                    'level' => $demand->level,
                    'demand_count' => 1,
                    'status' => 'open',
                    'metadata' => [
                        'demand_ids' => [$demand->id],
                        'locations' => $demand->location ? [$demand->location] : [],
                        'preferred_days' => $demand->preferred_days ? [$demand->preferred_days] : [],
                        'preferred_times' => $demand->preferred_time ? [$demand->preferred_time] : [],
                        'delivery_modes' => $demand->delivery_mode ? [$demand->delivery_mode] : [],
                    ],
                    'status' => 'processing',
                ]);

            return $this->createFromAggregation($aggregation);
        });
    }

    public function getOpportunitiesForTeacher(int $teacherId, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        // Single canonical teacher feed lives in TeacherMatchingService.
        return app(\App\Services\TeacherMatchingService::class)->getMatchingOpportunities($teacherId, $filters);
    }

    public function getOpportunitiesForTenant(int $tenantId, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = \App\Models\Opportunity::forTenant($tenantId)
            ->active()
            ->with(['tenant', 'demandAggregation', 'matchedTeacher'])
            ->orderByDesc('score')
            ->orderByDesc('created_at');

        if (!empty($filters['subject'])) {
            $query->where('subject', 'LIKE', '%' . $filters['subject'] . '%');
        }

        if (!empty($filters['level'])) {
            $query->where('level', $filters['level']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhere('subject', 'LIKE', '%' . $filters['search'] . '%');
            });
        }

        return $query->paginate(20);
    }

    public function getOpportunityDetails(int $opportunityId, int $tenantId): ?\App\Models\Opportunity
    {
        return \App\Models\Opportunity::forTenant($tenantId)
            ->with(['tenant', 'demandAggregation', 'matchedTeacher', 'matchedCourse', 'groups'])
            ->find($opportunityId);
    }

    public function acceptOpportunity(int $opportunityId, int $teacherId): \App\Models\Opportunity
    {
        return \DB::transaction(function () use ($opportunityId, $teacherId) {
            $opportunity = \App\Models\Opportunity::where('id', $opportunityId)
                ->lockForUpdate()
                ->firstOrFail();

            // Verify teacher is active and belongs to same tenant
            $teacher = \App\Models\Instructor::where('id', $teacherId)
                ->where('status', 'active')
                ->firstOrFail();

            if ($opportunity->tenant_id !== $teacher->tenant_id) {
                throw new \InvalidArgumentException('Teacher and opportunity must belong to same tenant');
            }

            if (!$opportunity->canBeMatched()) {
                throw new \InvalidArgumentException('Opportunity cannot be matched in current state');
            }

            // Determine course to match with (first available course matching subject)
            $course = $this->findMatchingCourse($teacher, $opportunity);

            if (!$course) {
                throw new \InvalidArgumentException('No suitable course found for this opportunity');
            }

            // Check capacity
            if ($course->enrolled_count >= $course->capacity) {
                throw new \InvalidArgumentException('Course is at full capacity');
            }

            // Update opportunity
            $opportunity->markAsMatched($teacher->id, $course->id);

            // Create notification for teacher
            $this->notifyTeacherAccepted($opportunity);

            // Notify demand owners
            $this->notifyDemandOwners($opportunity);

            return $opportunity->fresh();
        });
    }

    public function declineOpportunity(int $opportunityId, int $teacherId, ?string $reason = null): \App\Models\Opportunity
    {
        $opportunity = \App\Models\Opportunity::where('id', $opportunityId)
            ->where('status', 'open')
            ->firstOrFail();

        $teacher = \App\Models\Instructor::where('id', $teacherId)
            ->where('status', 'active')
            ->firstOrFail();

        if ($opportunity->tenant_id !== $teacher->tenant_id) {
            throw new \InvalidArgumentException('Teacher and opportunity must belong to same tenant');
        }

        if (!$opportunity->canBeMatched()) {
            throw new \InvalidArgumentException('Opportunity cannot be declined in current state');
        }

        // For now, we just log the decline - opportunity remains open for other teachers
        // In future, we could track declined teachers to avoid re-matching
        \App\Models\GrowthEvent::create([
            'tenant_id' => $opportunity->tenant_id,
            'event_name' => 'opportunity_declined',
            'eventable_type' => \App\Models\Opportunity::class,
            'eventable_id' => $opportunity->id,
            'metadata' => [
                'teacher_id' => $teacherId,
                'reason' => $reason,
            ],
        ]);

        return $opportunity->fresh();
    }

    public function startGroupFormation(int $opportunityId, int $teacherId): \App\Models\Opportunity
    {
        $opportunity = \App\Models\Opportunity::where('id', $opportunityId)
            ->where('status', 'matched')
            ->where('matched_teacher_id', $teacherId)
            ->firstOrFail();

        $opportunity->startGroupFormation();

        // Notify demand owners that group formation started
        $this->notifyGroupFormationStarted($opportunity);

        return $opportunity->fresh();
    }

    public function completeGroupFormation(int $opportunityId, int $teacherId): \App\Models\Opportunity
    {
        $opportunity = \App\Models\Opportunity::where('id', $opportunityId)
            ->where('status', 'group_forming')
            ->where('matched_teacher_id', $teacherId)
            ->firstOrFail();

        $opportunity->completeGroupFormation();

        // Notify demand owners that group is formed
        $this->notifyGroupFormed($opportunity);

        return $opportunity->fresh();
    }

    public function getTeacherOpportunities(int $teacherId, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        // Single canonical teacher feed lives in TeacherMatchingService.
        return app(\App\Services\TeacherMatchingService::class)->getMatchingOpportunities($teacherId, $filters);
    }

    public function getMatchedOpportunities(int $teacherId): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return app(\App\Services\TeacherMatchingService::class)->getMatchedOpportunities($teacherId);
    }

    public function getGroupFormingOpportunities(int $teacherId): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return app(\App\Services\TeacherMatchingService::class)->getGroupFormingOpportunities($teacherId);
    }

    protected function generateTitle(\App\Models\DemandAggregation $aggregation): string
    {
        $parts = [];
        if ($aggregation->subject) {
            $parts[] = $aggregation->subject;
        }
        if ($aggregation->level) {
            $parts[] = $aggregation->level;
        }
        return implode(' - ', $parts) . ' Opportunity';
    }

    protected function generateDescription(\App\Models\DemandAggregation $aggregation): string
    {
        $parts = [];
        $parts[] = "Demand: {$aggregation->demand_count} students";

        if (!empty($aggregation->metadata['available_capacity']) && $aggregation->metadata['available_capacity'] > 0) {
            $parts[] = 'capacity available';
        }

        return implode(' | ', $parts);
    }

    protected function generateExplanation(\App\Models\DemandAggregation $aggregation): string
    {
        $parts = [];

        if (($aggregation->metadata['demand_volume'] ?? 0) >= 10) {
            $parts[] = 'High demand';
        } elseif (($aggregation->metadata['demand_volume'] ?? 0) >= 5) {
            $parts[] = 'Moderate demand';
        }

        if (($aggregation->metadata['available_capacity'] ?? 0) > 0) {
            $parts[] = 'available capacity';
        }

        return implode(' + ', $parts) . '.';
    }

    protected function calculateScore(\App\Models\DemandAggregation $aggregation): float
    {
        // Use the OpportunityScoringService
        return app(\App\Services\OpportunityScoringService::class)
            ->scoreOpportunity($aggregation->tenant_id, $aggregation->subject, $aggregation->level)['total'];
    }

    protected function calculateScoreBreakdown(\App\Models\DemandAggregation $aggregation): array
    {
        return app(\App\Services\OpportunityScoringService::class)
            ->scoreOpportunity($aggregation->tenant_id, $aggregation->subject, $aggregation->level)['breakdown'];
    }

    protected function findMatchingCourse(\App\Models\Instructor $teacher, \App\Models\Opportunity $opportunity): ?\App\Models\Course
    {
        return \App\Models\Course::where('tenant_id', $teacher->tenant_id)
            ->where('instructor_id', $teacher->id)
            ->where('published', true)
            ->where('status', 'active')
            ->where(function ($q) use ($opportunity) {
                $q->whereRaw("LOWER(title) LIKE ?", ['%' . strtolower($opportunity->subject) . '%'])
                    ->orWhereRaw("LOWER(category) LIKE ?", ['%' . strtolower($opportunity->subject) . '%']);
            })
            ->whereRaw('enrolled_count < capacity')
            ->first();
    }

    protected function notifyTeacherAccepted(\App\Models\Opportunity $opportunity): void
    {
        // Send notification to teacher
        $teacher = \App\Models\Instructor::find($opportunity->matched_teacher_id);
        if ($teacher && $teacher->user_id && $teacher->user) {
            app(\App\Services\GrowthNotificationService::class)->send(
                $opportunity->tenant_id,
                $teacher->user_id,
                'opportunity_accepted',
                [
                    'opportunity_id' => $opportunity->id,
                    'opportunity_title' => $opportunity->title,
                    'message' => "You've accepted the {$opportunity->title} opportunity!",
                ]
            );
        }
    }

    protected function notifyDemandOwners(\App\Models\Opportunity $opportunity): void
    {
        $demandIds = $opportunity->demandAggregation?->metadata['demand_ids'] ?? [];
        if (!empty($demandIds)) {
            $notificationService = app(\App\Services\GrowthNotificationService::class);
            \App\Models\DemandRequest::whereIn('id', $demandIds)->whereNotNull('user_id')->chunk(100, function ($demands) use ($opportunity, $notificationService) {
                foreach ($demands as $demand) {
                    if (!$demand->user_id) {
                        continue;
                    }
                    $notificationService->send(
                        $opportunity->tenant_id,
                        $demand->user_id,
                        'demand_matched',
                        [
                            'demand_id' => $demand->id,
                            'opportunity_id' => $opportunity->id,
                            'opportunity_title' => $opportunity->title,
                            'teacher_name' => $opportunity->matchedTeacher?->name ?? 'A teacher',
                            'message' => "Your demand for {$opportunity->subject} has been matched!",
                        ]
                    );
                }
            });
        }
    }

    protected function notifyGroupFormationStarted(\App\Models\Opportunity $opportunity): void
    {
        $demandIds = $opportunity->demandAggregation?->metadata['demand_ids'] ?? [];
        if (!empty($demandIds)) {
            $notificationService = app(\App\Services\GrowthNotificationService::class);
            \App\Models\DemandRequest::whereIn('id', $demandIds)->whereNotNull('user_id')->chunk(100, function ($demands) use ($opportunity, $notificationService) {
                foreach ($demands as $demand) {
                    if (!$demand->user_id) {
                        continue;
                    }
                    $notificationService->send(
                        $opportunity->tenant_id,
                        $demand->user_id,
                        'group_formation_started',
                        [
                            'opportunity_id' => $opportunity->id,
                            'opportunity_title' => $opportunity->title,
                            'teacher_name' => $opportunity->matchedTeacher?->name ?? 'A teacher',
                            'message' => "Group formation started for {$opportunity->title}!",
                        ]
                    );
                }
            });
        }
    }

    protected function notifyGroupFormed(\App\Models\Opportunity $opportunity): void
    {
        $demandIds = $opportunity->demandAggregation?->metadata['demand_ids'] ?? [];
        if (!empty($demandIds)) {
            $notificationService = app(\App\Services\GrowthNotificationService::class);
            \App\Models\DemandRequest::whereIn('id', $demandIds)->whereNotNull('user_id')->chunk(100, function ($demands) use ($opportunity, $notificationService) {
                foreach ($demands as $demand) {
                    if (!$demand->user_id) {
                        continue;
                    }
                    $notificationService->send(
                        $opportunity->tenant_id,
                        $demand->user_id,
                        'group_formed',
                        [
                            'opportunity_id' => $opportunity->id,
                            'opportunity_title' => $opportunity->title,
                            'teacher_name' => $opportunity->matchedTeacher?->name ?? 'A teacher',
                            'course_title' => $opportunity->matchedCourse?->title ?? 'A course',
                            'message' => "Group formed for {$opportunity->title}! Teacher: {$opportunity->matchedTeacher?->name}",
                        ]
                    );
                }
            });
        }
    }

    public function expireOpportunities(): int
    {
        return \App\Models\Opportunity::open()
            ->where('expires_at', '<', now())
            ->get()
            ->each(function ($opportunity) {
                $opportunity->expire();
                $this->notifyOpportunityExpired($opportunity);
            })
            ->count();
    }

    protected function notifyOpportunityExpired(\App\Models\Opportunity $opportunity): void
    {
        if (!$opportunity->matched_teacher_id) {
            return;
        }

        $teacher = \App\Models\Instructor::find($opportunity->matched_teacher_id);
        if (!$teacher || !$teacher->user_id) {
            return;
        }

        app(\App\Services\GrowthNotificationService::class)->send(
            $opportunity->tenant_id,
            $teacher->user_id,
            'opportunity_expired',
            [
                'opportunity_id' => $opportunity->id,
                'opportunity_title' => $opportunity->title,
                'message' => "The opportunity '{$opportunity->title}' has expired.",
            ]
        );
    }
}