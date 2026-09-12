<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Opportunity;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherMatchingService
{
    public function getMatchingOpportunities(int $teacherId, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $teacher = \App\Models\Instructor::findOrFail($teacherId);

        $query = \App\Models\Opportunity::forTenant($teacher->tenant_id)
            ->active()
            ->where('subject', 'LIKE', '%' . $teacher->specialization . '%')
            ->with(['tenant', 'demandAggregation', 'matchedCourse'])
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

    public function getMatchedOpportunities(int $teacherId): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $teacher = \App\Models\Instructor::findOrFail($teacherId);

        return \App\Models\Opportunity::forTenant($teacher->tenant_id)
            ->where('status', 'matched')
            ->where('matched_teacher_id', $teacherId)
            ->with(['tenant', 'demandAggregation', 'matchedCourse', 'groups'])
            ->orderByDesc('matched_at')
            ->paginate(20);
    }

    public function getGroupFormingOpportunities(int $teacherId): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $teacher = \App\Models\Instructor::findOrFail($teacherId);

        return \App\Models\Opportunity::forTenant($teacher->tenant_id)
            ->where('status', 'group_forming')
            ->where('matched_teacher_id', $teacherId)
            ->with(['tenant', 'demandAggregation', 'matchedCourse', 'groups'])
            ->orderByDesc('group_formed_at')
            ->paginate(20);
    }

    public function getGroupFormedOpportunities(int $teacherId): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $teacher = \App\Models\Instructor::findOrFail($teacherId);

        return \App\Models\Opportunity::forTenant($teacher->tenant_id)
            ->where('status', 'group_formed')
            ->where('matched_teacher_id', $teacherId)
            ->with(['tenant', 'demandAggregation', 'matchedCourse', 'groups'])
            ->orderByDesc('group_formed_at')
            ->paginate(20);
    }

    public function acceptOpportunity(int $opportunityId, int $teacherId): \App\Models\Opportunity
    {
        return app(\App\Services\OpportunityService::class)->acceptOpportunity($opportunityId, $teacherId);
    }

    public function declineOpportunity(int $opportunityId, int $teacherId, ?string $reason = null): \App\Models\Opportunity
    {
        return app(\App\Services\OpportunityService::class)->declineOpportunity($opportunityId, $teacherId, $reason);
    }

    public function startGroupFormation(int $opportunityId, int $teacherId): \App\Models\Opportunity
    {
        return app(\App\Services\OpportunityService::class)->startGroupFormation($opportunityId, $teacherId);
    }

    public function completeGroupFormation(int $opportunityId, int $teacherId): \App\Models\Opportunity
    {
        return app(\App\Services\OpportunityService::class)->completeGroupFormation($opportunityId, $teacherId);
    }

    public function getTeacherStats(int $teacherId): array
    {
        $teacher = \App\Models\Instructor::findOrFail($teacherId);
        $tenantId = $teacher->tenant_id;

        $totalOpportunities = \App\Models\Opportunity::forTenant($teacher->tenant_id)
            ->where('matched_teacher_id', $teacherId)
            ->count();

        $matched = \App\Models\Opportunity::forTenant($teacher->tenant_id)
            ->where('matched_teacher_id', $teacherId)
            ->whereIn('status', ['matched', 'group_forming', 'group_formed', 'filled'])
            ->count();

        $groupForming = \App\Models\Opportunity::forTenant($teacher->tenant_id)
            ->where('matched_teacher_id', $teacherId)
            ->where('status', 'group_forming')
            ->count();

        $groupFormed = \App\Models\Opportunity::forTenant($teacher->tenant_id)
            ->where('matched_teacher_id', $teacherId)
            ->where('status', 'group_formed')
            ->count();

        $filled = \App\Models\Opportunity::forTenant($teacher->tenant_id)
            ->where('matched_teacher_id', $teacherId)
            ->where('status', 'filled')
            ->count();

        $declined = \App\Models\GrowthEvent::where('tenant_id', $teacher->tenant_id)
            ->where('event_name', 'opportunity_declined')
            ->whereJsonContains('metadata->teacher_id', $teacherId)
            ->count();

        $totalStudents = \App\Models\Opportunity::where('matched_teacher_id', $teacherId)
            ->whereIn('status', ['group_formed', 'filled'])
            ->with('groups')
            ->get()
            ->sum(fn ($o) => $o->groups->sum('student_count'));

        return [
            'total_opportunities' => $totalOpportunities,
            'matched' => $matched,
            'group_forming' => $groupForming,
            'group_formed' => $groupFormed,
            'filled' => $filled,
            'declined' => $declined,
            'total_students' => $totalStudents,
            'acceptance_rate' => $totalOpportunities > 0
                ? round((($totalOpportunities - $declined) / $totalOpportunities) * 100, 1)
                : 0,
        ];
    }

    public function getAvailableOpportunitiesForTeacher(int $teacherId, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->getMatchingOpportunities($teacherId, array_merge($filters, ['status' => 'open']));
    }
}