<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class GrowthMaintain extends Command
{
    protected $signature = 'growth:maintain';

    protected $description = 'Growth Network maintenance: expire stale opportunities/listings and aggregate new demand into opportunities';

    public function handle(): int
    {
        $expiredOpportunities = app(\App\Services\OpportunityService::class)->expireOpportunities();
        $this->info("Expired opportunities: {$expiredOpportunities}");

        $expiredListings = app(\App\Services\MarketplaceService::class)->expireStaleListings();
        $this->info("Expired marketplace listings: {$expiredListings}");

        $aggregations = 0;
        $pending = 0;
        Tenant::where('status', 'active')->chunkById(100, function ($tenants) use (&$aggregations, &$pending) {
            $service = app(\App\Services\DemandAggregationService::class);
            foreach ($tenants as $tenant) {
                $aggregations += $service->aggregateForTenant($tenant->id);
                $pending += $service->processPendingAggregations($tenant->id);
            }
        });
        $this->info("Demand aggregations processed: {$aggregations}");
        $this->info("Pending aggregations processed: {$pending}");

        return self::SUCCESS;
    }
}
