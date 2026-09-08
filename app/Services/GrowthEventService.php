<?php

namespace App\Services;

use App\Models\GrowthEvent;
use App\Models\PublicProfile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GrowthEventService
{
    /**
     * Record a generic growth event.
     */
    public function record(array $data): void
    {
        GrowthEvent::create([
            'tenant_id' => $data['tenant_id'],
            'event_name' => $data['event_name'],
            'eventable_type' => $data['eventable_type'] ?? null,
            'eventable_id' => $data['eventable_id'] ?? null,
            'actor_type' => $data['actor_type'] ?? 'visitor',
            'actor_id' => $data['actor_id'] ?? session()->getId(),
            'source' => $data['source'] ?? null,
            'campaign' => $data['campaign'] ?? null,
            'metadata' => $data['metadata'] ?? null,
        ]);

        $this->clearMetricsCache($data['tenant_id']);
    }

    /**
     * Track a profile view event.
     */
    public function trackProfileView(PublicProfile $profile, ?string $source = null, ?string $campaign = null): void
    {
        GrowthEvent::record(
            'profile_viewed',
            $profile,
            'visitor',
            session()->getId(),
            $source,
            $campaign
        );

        $this->clearMetricsCache($profile->tenant_id);
    }

    /**
     * Get profile view count for a profile within a date range.
     */
    public function getProfileViewCount(PublicProfile $profile, $from = null, $to = null): int
    {
        $query = GrowthEvent::where('tenant_id', $profile->tenant_id)
            ->where('eventable_type', $profile->getMorphClass())
            ->where('eventable_id', $profile->id)
            ->named('profile_viewed');

        if ($from && $to) {
            $query->between($from, $to);
        }

        return $query->count();
    }

    /**
     * Get acquisition source breakdown for a profile.
     */
    public function getAcquisitionSources(PublicProfile $profile, $from = null, $to = null): array
    {
        $query = GrowthEvent::where('tenant_id', $profile->tenant_id)
            ->where('eventable_type', $profile->getMorphClass())
            ->where('eventable_id', $profile->id)
            ->named('profile_viewed')
            ->whereNotNull('source');

        if ($from && $to) {
            $query->between($from, $to);
        }

        return $query->select('source', DB::raw('COUNT(*) as count'))
            ->groupBy('source')
            ->pluck('count', 'source')
            ->toArray();
    }

    /**
     * Get aggregated metrics for a tenant's growth dashboard.
     */
    public function getTenantMetrics(int $tenantId, $from = null, $to = null): array
    {
        $cacheKey = "tenant_{$tenantId}:growth_metrics";

        return Cache::remember($cacheKey, 300, function () use ($tenantId, $from, $to) {
            $query = DB::table('growth_events')->where('tenant_id', $tenantId);

            if ($from && $to) {
                $query->whereBetween('created_at', [$from, $to]);
            }

            $profileViewQuery = (clone $query)->where('event_name', 'profile_viewed');

            $totalProfileViews = (clone $profileViewQuery)->count();

            $uniqueVisitors = (clone $profileViewQuery)
                ->whereNotNull('actor_id')
                ->distinct()
                ->count('actor_id');

            $sources = (clone $profileViewQuery)
                ->whereNotNull('source')
                ->select('source', DB::raw('COUNT(*) as count'))
                ->groupBy('source')
                ->pluck('count', 'source')
                ->toArray();

            return [
                'total_profile_views' => $totalProfileViews,
                'unique_visitors' => $uniqueVisitors,
                'sources' => $sources,
            ];
        });
    }

    /**
     * Clear metrics cache for a tenant.
     */
    protected function clearMetricsCache(int $tenantId): void
    {
        Cache::forget("tenant_{$tenantId}:growth_metrics");
    }
}
