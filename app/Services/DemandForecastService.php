<?php

namespace App\Services;

use App\Models\DemandRequest;
use Illuminate\Support\Facades\DB;

class DemandForecastService
{
    public function forecast(int $tenantId, int $monthsAhead = 3): array
    {
        $monthlyDemand = $this->getMonthlyDemand($tenantId, 6);

        $hasDemand = $monthlyDemand->filter(fn ($m) => $m->count > 0)->count();

        if ($hasDemand < 2) {
            return [
                'available' => false,
                'message' => 'Not enough data to forecast. At least 2 months of demand data needed.',
                'data_points' => $hasDemand,
            ];
        }

        $values = $monthlyDemand->filter(fn ($m) => $m->count > 0)->pluck('count')->values()->toArray();
        $movingAvg = $this->weightedMovingAverage($values);
        $trend = $this->calculateTrend($values);

        $forecast = [];
        for ($i = 1; $i <= $monthsAhead; $i++) {
            $projected = max(0, round($movingAvg + ($trend * $i)));
            $confidence = max(0.3, 1.0 - ($i * 0.15));

            $forecast[] = [
                'month' => now()->addMonths($i)->format('Y-m'),
                'projected_demand' => $projected,
                'confidence' => round($confidence, 2),
                'range' => [
                    'low' => max(0, round($projected * (1 - (1 - $confidence)))),
                    'high' => round($projected * (1 + (1 - $confidence))),
                ],
            ];
        }

        $bySubject = $this->getDemandBySubject($tenantId);

        return [
            'available' => true,
            'historical' => $monthlyDemand->toArray(),
            'forecast' => $forecast,
            'trend' => $trend > 0 ? 'increasing' : ($trend < 0 ? 'decreasing' : 'stable'),
            'trend_value' => round($trend, 2),
            'by_subject' => $bySubject,
        ];
    }

    protected function getMonthlyDemand(int $tenantId, int $months): \Illuminate\Support\Collection
    {
        $rows = DB::table('demand_requests')
            ->where('tenant_id', $tenantId)
            ->where('created_at', '>=', now()->subMonths($months))
            ->select('created_at')
            ->orderBy('created_at')
            ->get();

        $grouped = $rows->groupBy(fn ($row) => \Carbon\Carbon::parse($row->created_at)->format('Y-m'));

        $result = collect();
        for ($i = $months; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $result->push((object) [
                'month' => $month,
                'count' => $grouped->has($month) ? $grouped[$month]->count() : 0,
            ]);
        }

        return $result;
    }

    protected function getDemandBySubject(int $tenantId): array
    {
        return DB::table('demand_requests')
            ->where('tenant_id', $tenantId)
            ->select('subject', DB::raw('COUNT(*) as count'))
            ->groupBy('subject')
            ->orderByDesc('count')
            ->pluck('count', 'subject')
            ->toArray();
    }

    protected function weightedMovingAverage(array $values): float
    {
        $weights = [3, 2, 1];
        $windowSize = min(3, count($values));
        $window = array_slice($values, -$windowSize);

        $weightedSum = 0;
        $weightSum = 0;

        for ($i = 0; $i < $windowSize; $i++) {
            $weight = $weights[$i] ?? 1;
            $weightedSum += $window[$windowSize - 1 - $i] * $weight;
            $weightSum += $weight;
        }

        return $weightSum > 0 ? $weightedSum / $weightSum : 0;
    }

    protected function calculateTrend(array $values): float
    {
        $n = count($values);
        if ($n < 2) {
            return 0;
        }

        $sumX = 0;
        $sumY = 0;
        $sumXY = 0;
        $sumX2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $sumX += $i;
            $sumY += $values[$i];
            $sumXY += $i * $values[$i];
            $sumX2 += $i * $i;
        }

        $denominator = ($n * $sumX2) - ($sumX * $sumX);
        if ($denominator == 0) {
            return 0;
        }

        return (($n * $sumXY) - ($sumX * $sumY)) / $denominator;
    }
}
