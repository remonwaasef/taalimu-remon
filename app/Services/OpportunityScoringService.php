<?php

namespace App\Services;

use App\Models\Course;
use App\Models\DemandRequest;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class OpportunityScoringService
{
    public function getOpportunities(int $tenantId): array
    {
        $demandGroups = DemandRequest::where('tenant_id', $tenantId)
            ->where('status', 'new')
            ->select('subject', 'level', DB::raw('COUNT(*) as demand_count'))
            ->groupBy('subject', 'level')
            ->orderByDesc('demand_count')
            ->get();

        $opportunities = [];

        foreach ($demandGroups as $demand) {
            $score = $this->scoreOpportunity($tenantId, $demand->subject, $demand->level);

            $opportunities[] = [
                'subject' => $demand->subject,
                'level' => $demand->level,
                'demand_count' => $demand->demand_count,
                'score' => $score['total'],
                'breakdown' => $score['breakdown'],
                'explanation' => $this->generateExplanation($score),
                'estimated_revenue' => $this->estimateRevenue($tenantId, $demand->subject),
            ];
        }

        usort($opportunities, fn ($a, $b) => $b['score'] <=> $a['score']);

        return $opportunities;
    }

    protected function scoreOpportunity(int $tenantId, string $subject, ?string $level): array
    {
        $demandVolume = $this->scoreDemandVolume($tenantId, $subject);
        $capacity = $this->scoreAvailableCapacity($tenantId, $subject);
        $conversion = $this->scoreHistoricalConversion($tenantId, $subject);
        $schedule = $this->scoreScheduleCompatibility($tenantId);
        $location = $this->scoreLocationMatch($tenantId);

        $total = round(
            ($demandVolume * 0.30) +
            ($capacity * 0.25) +
            ($conversion * 0.25) +
            ($schedule * 0.10) +
            ($location * 0.10)
        );

        return [
            'total' => $total,
            'breakdown' => [
                'demand_volume' => ['score' => $demandVolume, 'weight' => 30, 'label' => 'Demand volume'],
                'available_capacity' => ['score' => $capacity, 'weight' => 25, 'label' => 'Available capacity'],
                'conversion_history' => ['score' => $conversion, 'weight' => 25, 'label' => 'Conversion history'],
                'schedule_match' => ['score' => $schedule, 'weight' => 10, 'label' => 'Schedule match'],
                'location_match' => ['score' => $location, 'weight' => 10, 'label' => 'Location match'],
            ],
        ];
    }

    protected function scoreDemandVolume(int $tenantId, string $subject): int
    {
        $count = DemandRequest::where('tenant_id', $tenantId)
            ->where('subject', $subject)
            ->count();

        return match (true) {
            $count >= 10 => 100,
            $count >= 5 => 80,
            $count >= 3 => 60,
            $count >= 1 => 40,
            default => 0,
        };
    }

    protected function scoreAvailableCapacity(int $tenantId, string $subject): int
    {
        $courses = Course::where('tenant_id', $tenantId)
            ->where('published', true)
            ->whereRaw("LOWER(title) LIKE ?", ['%' . strtolower($subject) . '%'])
            ->get();

        if ($courses->isEmpty()) {
            return 100;
        }

        $totalEmptySeats = $courses->sum(fn ($c) => max(0, $c->capacity - $c->enrolled_count));

        return match (true) {
            $totalEmptySeats >= 20 => 100,
            $totalEmptySeats >= 10 => 80,
            $totalEmptySeats >= 5 => 60,
            $totalEmptySeats > 0 => 40,
            default => 0,
        };
    }

    protected function scoreHistoricalConversion(int $tenantId, string $subject): int
    {
        $totalDemand = DemandRequest::where('tenant_id', $tenantId)
            ->where('subject', $subject)
            ->count();

        if ($totalDemand === 0) {
            return 50;
        }

        $converted = DemandRequest::where('tenant_id', $tenantId)
            ->where('subject', $subject)
            ->where('status', 'converted')
            ->count();

        $rate = ($converted / $totalDemand) * 100;

        return min(100, (int) $rate);
    }

    protected function scoreScheduleCompatibility(int $tenantId): int
    {
        $courses = Course::where('tenant_id', $tenantId)
            ->where('published', true)
            ->with('schedules')
            ->get();

        if ($courses->isEmpty()) {
            return 50;
        }

        $hasSchedules = $courses->contains(fn ($c) => $c->schedules->isNotEmpty());

        return $hasSchedules ? 80 : 40;
    }

    protected function scoreLocationMatch(int $tenantId): int
    {
        $hasLocation = DemandRequest::where('tenant_id', $tenantId)
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->exists();

        return $hasLocation ? 70 : 50;
    }

    protected function generateExplanation(array $score): string
    {
        $parts = [];

        if ($score['breakdown']['demand_volume']['score'] >= 80) {
            $parts[] = 'High demand';
        } elseif ($score['breakdown']['demand_volume']['score'] >= 50) {
            $parts[] = 'Moderate demand';
        }

        if ($score['breakdown']['available_capacity']['score'] >= 80) {
            $parts[] = 'available capacity';
        } elseif ($score['breakdown']['available_capacity']['score'] >= 50) {
            $parts[] = 'some capacity';
        } else {
            $parts[] = 'capacity needed';
        }

        if ($score['breakdown']['conversion_history']['score'] >= 70) {
            $parts[] = 'strong conversion history';
        }

        return implode(' + ', $parts) . ".";
    }

    protected function estimateRevenue(int $tenantId, string $subject): array
    {
        $avgPrice = Course::where('tenant_id', $tenantId)
            ->whereRaw("LOWER(title) LIKE ?", ['%' . strtolower($subject) . '%'])
            ->avg('price') ?? 0;

        $demandCount = DemandRequest::where('tenant_id', $tenantId)
            ->where('subject', $subject)
            ->count();

        $conversionRate = 0.3;

        return [
            'estimated_enrollments' => (int) round($demandCount * $conversionRate),
            'estimated_revenue' => round($avgPrice * $demandCount * $conversionRate, 2),
            'avg_course_price' => round($avgPrice, 2),
        ];
    }
}
