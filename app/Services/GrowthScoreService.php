<?php

namespace App\Services;

use App\Models\Course;
use App\Models\DemandRequest;
use App\Models\Enrollment;
use App\Models\PublicProfile;
use App\Models\Tenant;
use App\Models\Waitlist;
use Illuminate\Support\Facades\DB;

class GrowthScoreService
{
    public function calculate(Tenant $tenant): array
    {
        $profile = PublicProfile::where('tenant_id', $tenant->id)
            ->where('published', true)
            ->first();

        $profileScore = $this->calculateProfileCompleteness($profile);
        $programsScore = $this->calculateProgramsScore($tenant->id);
        $conversionScore = $this->calculateConversionScore($tenant->id);
        $responseScore = $this->calculateResponseScore($tenant->id);
        $retentionScore = $this->calculateRetentionScore($tenant->id);
        $demandScore = $this->calculateDemandFulfillmentScore($tenant->id);

        $total = round(
            ($profileScore * 0.20) +
            ($programsScore * 0.20) +
            ($conversionScore * 0.20) +
            ($responseScore * 0.15) +
            ($retentionScore * 0.15) +
            ($demandScore * 0.10)
        );

        return [
            'total' => $total,
            'breakdown' => [
                'profile_completeness' => ['score' => $profileScore, 'weight' => 20, 'label' => 'Profile completeness'],
                'active_programs' => ['score' => $programsScore, 'weight' => 20, 'label' => 'Active programs'],
                'conversion_rate' => ['score' => $conversionScore, 'weight' => 20, 'label' => 'Conversion rate'],
                'response_rate' => ['score' => $responseScore, 'weight' => 15, 'label' => 'Response rate'],
                'retention' => ['score' => $retentionScore, 'weight' => 15, 'label' => 'Student retention'],
                'demand_fulfillment' => ['score' => $demandScore, 'weight' => 10, 'label' => 'Demand fulfillment'],
            ],
        ];
    }

    protected function calculateProfileCompleteness(?PublicProfile $profile): int
    {
        if (! $profile) {
            return 0;
        }

        $fields = ['title', 'headline', 'bio', 'photo_url'];
        $filled = 0;

        foreach ($fields as $field) {
            if (! empty($profile->{$field})) {
                $filled++;
            }
        }

        return (int) round(($filled / count($fields)) * 100);
    }

    protected function calculateProgramsScore(int $tenantId): int
    {
        $publishedCount = Course::where('tenant_id', $tenantId)
            ->where('published', true)
            ->count();

        return match (true) {
            $publishedCount >= 5 => 100,
            $publishedCount >= 3 => 80,
            $publishedCount >= 1 => 60,
            default => 0,
        };
    }

    protected function calculateConversionScore(int $tenantId): int
    {
        $profileViews = DB::table('growth_events')
            ->where('tenant_id', $tenantId)
            ->where('event_name', 'profile_viewed')
            ->count();

        if ($profileViews === 0) {
            return 0;
        }

        $enrollments = Enrollment::where('tenant_id', $tenantId)->count();
        $conversionRate = ($enrollments / $profileViews) * 100;

        return min(100, (int) ($conversionRate * 10));
    }

    protected function calculateResponseScore(int $tenantId): int
    {
        $totalDemand = DemandRequest::where('tenant_id', $tenantId)->count();

        if ($totalDemand === 0) {
            return 50;
        }

        $contacted = DemandRequest::where('tenant_id', $tenantId)
            ->whereIn('status', ['contacted', 'converted'])
            ->count();

        return (int) round(($contacted / $totalDemand) * 100);
    }

    protected function calculateRetentionScore(int $tenantId): int
    {
        $totalEnrollments = Enrollment::where('tenant_id', $tenantId)->count();

        if ($totalEnrollments === 0) {
            return 0;
        }

        $uniqueStudents = Enrollment::where('tenant_id', $tenantId)
            ->distinct('student_id')
            ->count('student_id');

        $retentionRate = $uniqueStudents > 0
            ? (($totalEnrollments - $uniqueStudents) / $totalEnrollments) * 100
            : 0;

        return min(100, (int) $retentionRate);
    }

    protected function calculateDemandFulfillmentScore(int $tenantId): int
    {
        $totalDemand = DemandRequest::where('tenant_id', $tenantId)->count();

        if ($totalDemand === 0) {
            return 50;
        }

        $converted = DemandRequest::where('tenant_id', $tenantId)
            ->where('status', 'converted')
            ->count();

        return (int) round(($converted / $totalDemand) * 100);
    }
}
