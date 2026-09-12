<?php

namespace App\Services;

use App\Models\DemandAggregation;
use App\Models\DemandRequest;
use App\Models\Opportunity;
use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DemandAggregationService
{
    public function aggregate(int $tenantId): int
    {
        $newDemands = DemandRequest::where('tenant_id', $tenantId)
            ->where('status', 'new')
            ->get();

        if ($newDemands->isEmpty()) {
            return 0;
        }

        $aggregatedCount = 0;

        // Group demands by subject and level
        $groups = $newDemands->groupBy(fn ($d) => $d->subject . '|' . ($d->level ?? ''));

        foreach ($groups as $key => $demands) {
            $first = $demands->first();
            $subject = $first->subject;
            $level = $first->level;

            // Reuse the aggregation for this subject+level when it is still
            // active — or reopen a completed one when fresh demand arrives
            // (the unique key forbids a second row for the same triple).
            $existing = DemandAggregation::where('tenant_id', $first->tenant_id)
                ->where('subject', $subject)
                ->where('level', $first->level)
                ->whereIn('status', ['open', 'processing', 'completed'])
                ->first();

            // Cumulative pending demand: every demand for this subject+level that
            // has not converted or declined yet (the current batch is still
            // 'new' at this point, so it is included automatically).
            $pendingIds = DemandRequest::where('tenant_id', $first->tenant_id)
                ->where('subject', $subject)
                ->where('level', $first->level)
                ->whereIn('status', ['new', 'contacted'])
                ->pluck('id')
                ->unique()
                ->values();

            $demandCount = $pendingIds->count();

            // Merge metadata across batches (union, never overwrite history).
            $previous = $existing?->metadata ?? [];
            $union = function (string $key, \Illuminate\Support\Collection $fresh) use ($previous) {
                return collect($previous[$key] ?? [])
                    ->merge($fresh)
                    ->filter()
                    ->flatten()
                    ->unique()
                    ->values()
                    ->toArray();
            };

            $metadata = [
                'locations' => $union('locations', $demands->pluck('location')),
                'preferred_days' => $union('preferred_days', $demands->pluck('preferred_days')),
                'preferred_times' => $union('preferred_times', $demands->pluck('preferred_time')),
                'delivery_modes' => $union('delivery_modes', $demands->pluck('delivery_mode')),
                'demand_ids' => $pendingIds->all(),
            ];

            if ($existing) {
                $existing->update([
                    'demand_count' => $demandCount,
                    'metadata' => $metadata,
                    'status' => 'processing',
                ]);
                $aggregation = $existing;
            } else {
                $aggregation = DemandAggregation::create([
                    'tenant_id' => $first->tenant_id,
                    'subject' => $subject,
                    'level' => $first->level,
                    'demand_count' => $demandCount,
                    'metadata' => $metadata,
                    'status' => 'processing',
                ]);
            }

            // Generate opportunities from this aggregation
            $this->generateOpportunitiesFromAggregation($aggregation);

            $aggregatedCount++;
        }

        // Mark demands as processed (move from 'new' to 'aggregated')
        $demandIds = $newDemands->pluck('id')->toArray();
        DemandRequest::whereIn('id', $demandIds)->update(['status' => 'contacted']);

        return $aggregatedCount;
    }

    public function aggregateForTenant(int $tenantId): int
    {
        return $this->aggregate($tenantId);
    }

    public function generateOpportunitiesFromAggregation(DemandAggregation $aggregation): int
    {
        // Use the OpportunityScoringService to evaluate and create opportunities
        $opportunities = app(\App\Services\OpportunityScoringService::class)
            ->getOpportunities($aggregation->tenant_id);

        $createdCount = 0;

        foreach ($opportunities as $opp) {
            // Only create opportunities for subjects that match this aggregation
            if (strtolower($opp['subject']) !== strtolower($aggregation->subject)) {
                continue;
            }

            // Check if opportunity already exists for this aggregation
            $existing = \App\Models\Opportunity::where('tenant_id', $aggregation->tenant_id)
                ->where('demand_aggregation_id', $aggregation->id)
                ->where('subject', $opp['subject'])
                ->where('level', $aggregation->level)
                ->whereIn('status', ['open', 'matched', 'group_forming', 'group_formed'])
                ->first();

            if ($existing) {
                // Update existing opportunity with latest scoring and volume
                $existing->update([
                    'demand_volume' => $aggregation->demand_count,
                    'score' => $opp['score'],
                    'score_breakdown' => $opp['breakdown'],
                    'metadata' => array_merge($existing->metadata ?? [], [
                        'demand_volume' => $aggregation->demand_count,
                        'available_capacity' => $opp['breakdown']['available_capacity']['score'] ?? 0,
                        'conversion_rate' => $existing->metadata['conversion_rate'] ?? 0,
                    ]),
                ]);
                continue;
            }

            // Create new opportunity
            $opportunity = \App\Models\Opportunity::create([
                'tenant_id' => $aggregation->tenant_id,
                'demand_aggregation_id' => $aggregation->id,
                'title' => $this->generateTitle($opp),
                'subject' => $opp['subject'],
                'level' => $aggregation->level,
                'description' => $this->generateDescription($opp),
                'explanation' => $opp['explanation'],
                'demand_volume' => $opp['demand_count'],
                'score' => $opp['score'],
                'score_breakdown' => $opp['breakdown'],
                'metadata' => [
                    'demand_volume' => $opp['demand_count'],
                    'available_capacity' => $opp['breakdown']['available_capacity']['score'] ?? 0,
                    'conversion_rate' => $opp['breakdown']['conversion_history']['score'] ?? 0,
                    'schedule_match' => $opp['breakdown']['schedule_match']['score'] ?? 0,
                    'location_match' => $opp['breakdown']['location_match']['score'] ?? 0,
                ],
                'status' => 'open',
            ]);

            $createdCount++;

            // Mark aggregation as completed if all opportunities generated
            $aggregation->markAsCompleted();
        }

        return $createdCount;
    }

    protected function generateTitle(array $opportunity): string
    {
        $parts = [];
        if ($opportunity['subject']) {
            $parts[] = $opportunity['subject'];
        }
        if ($opportunity['level']) {
            $parts[] = $opportunity['level'];
        }
        return implode(' - ', $parts) . ' Opportunity';
    }

    protected function generateDescription(array $opportunity): string
    {
        $parts = [];
        $parts[] = "Demand: {$opportunity['demand_count']} students";

        if (!empty($opportunity['breakdown']['available_capacity']['score']) && $opportunity['breakdown']['available_capacity']['score'] > 50) {
            $parts[] = 'capacity available';
        }

        if (!empty($opportunity['breakdown']['conversion_history']['score']) && $opportunity['breakdown']['conversion_history']['score'] > 30) {
            $parts[] = 'strong conversion history';
        }

        return implode(' | ', $parts);
    }

    public function processPendingAggregations(int $tenantId): int
    {
        $pending = \App\Models\DemandAggregation::where('tenant_id', $tenantId)
            ->whereIn('status', ['open', 'processing'])
            ->get();

        $count = 0;
        foreach ($pending as $aggregation) {
            $this->generateOpportunitiesFromAggregation($aggregation);
            $count++;
        }
        return $count;
    }
}