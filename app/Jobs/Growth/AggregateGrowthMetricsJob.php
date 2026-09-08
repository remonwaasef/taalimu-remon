<?php

namespace App\Jobs\Growth;

use App\Models\GrowthEvent;
use App\Models\PublicProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class AggregateGrowthMetricsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(
        public int $tenantId
    ) {}

    public function handle(): void
    {
        $cacheKey = "tenant_{$this->tenantId}:growth_metrics";

        $from = now()->subDays(30);
        $to = now();

        $totalViews = GrowthEvent::where('tenant_id', $this->tenantId)
            ->named('profile_viewed')
            ->between($from, $to)
            ->count();

        $uniqueVisitors = GrowthEvent::where('tenant_id', $this->tenantId)
            ->named('profile_viewed')
            ->between($from, $to)
            ->whereNotNull('actor_id')
            ->distinct('actor_id')
            ->count('actor_id');

        $sources = GrowthEvent::where('tenant_id', $this->tenantId)
            ->named('profile_viewed')
            ->between($from, $to)
            ->whereNotNull('source')
            ->select('source', \DB::raw('COUNT(*) as count'))
            ->groupBy('source')
            ->pluck('count', 'source')
            ->toArray();

        Cache::put($cacheKey, [
            'total_profile_views' => $totalViews,
            'unique_visitors' => $uniqueVisitors,
            'sources' => $sources,
            'computed_at' => now()->toISOString(),
        ], now()->addHours(6));
    }
}
