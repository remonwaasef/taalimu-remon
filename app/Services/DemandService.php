<?php

namespace App\Services;

use App\Models\Course;
use App\Models\DemandRequest;
use Illuminate\Support\Facades\DB;

class DemandService
{
    public function createDemandRequest(array $data, ?string $source = null, ?string $campaign = null): DemandRequest
    {
        return DemandRequest::create([
            'tenant_id' => $data['tenant_id'],
            'user_id' => $data['user_id'] ?? null,
            'course_id' => $data['course_id'] ?? null,
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'subject' => $data['subject'],
            'level' => $data['level'] ?? null,
            'preferred_days' => $data['preferred_days'] ?? null,
            'preferred_time' => $data['preferred_time'] ?? null,
            'delivery_mode' => $data['delivery_mode'] ?? null,
            'location' => $data['location'] ?? null,
            'budget_range' => $data['budget_range'] ?? null,
            'message' => $data['message'] ?? null,
            'status' => 'new',
            'source' => $source,
            'campaign' => $campaign,
        ]);
    }

    public function getDemandSummary(int $tenantId): array
    {
        $stats = DemandRequest::where('tenant_id', $tenantId)
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as new_count'),
                DB::raw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as converted_count')
            )
            ->setBindings(['new', 'converted'])
            ->first();

        $total = (int) $stats->total;
        $new = (int) $stats->new_count;
        $converted = (int) $stats->converted_count;

        $bySubject = DemandRequest::where('tenant_id', $tenantId)
            ->select('subject', DB::raw('count(*) as count'))
            ->groupBy('subject')
            ->orderByDesc('count')
            ->get();

        $recentDemand = DemandRequest::where('tenant_id', $tenantId)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return [
            'total' => $total,
            'new' => $new,
            'converted' => $converted,
            'conversion_rate' => $total > 0 ? round(($converted / $total) * 100, 1) : 0,
            'by_subject' => $bySubject,
            'recent' => $recentDemand,
        ];
    }

    public function aggregateDemand(int $tenantId): \Illuminate\Support\Collection
    {
        return DemandRequest::where('tenant_id', $tenantId)
            ->where('status', 'new')
            ->select(
                'subject',
                'level',
                'preferred_days',
                'preferred_time',
                'delivery_mode',
                DB::raw('count(*) as demand_count'),
                DB::raw('GROUP_CONCAT(DISTINCT name SEPARATOR ", ") as student_names')
            )
            ->groupBy('subject', 'level', 'preferred_days', 'preferred_time', 'delivery_mode')
            ->having('demand_count', '>=', 3)
            ->orderByDesc('demand_count')
            ->get();
    }

    public function markContacted(DemandRequest $demand): void
    {
        $demand->markContacted();
    }

    public function markConverted(DemandRequest $demand): void
    {
        $demand->markConverted();
    }

    public function markDeclined(DemandRequest $demand): void
    {
        $demand->markDeclined();
    }
}
