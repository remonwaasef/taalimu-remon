<?php

namespace App\Services;

use App\Models\Course;
use App\Models\DemandRequest;
use App\Models\Enrollment;
use App\Models\PublicProfile;
use App\Models\Waitlist;
use Illuminate\Support\Facades\DB;

class InsightService
{
    public function getInsights(int $tenantId): array
    {
        $insights = [];

        $insights = array_merge($insights, $this->getDemandInsights($tenantId));
        $insights = array_merge($insights, $this->getCapacityInsights($tenantId));
        $insights = array_merge($insights, $this->getConversionInsights($tenantId));
        $insights = array_merge($insights, $this->getGrowthInsights($tenantId));

        usort($insights, fn ($a, $b) => $b['severity'] <=> $a['severity']);

        return $insights;
    }

    protected function getDemandInsights(int $tenantId): array
    {
        $insights = [];

        $unmatchedDemand = DemandRequest::where('tenant_id', $tenantId)
            ->where('status', 'new')
            ->select('subject', DB::raw('count(*) as count'))
            ->groupBy('subject')
            ->having('count', '>=', 2)
            ->orderByDesc('count')
            ->get();

        foreach ($unmatchedDemand as $demand) {
            $hasCourse = Course::where('tenant_id', $tenantId)
                ->where('published', true)
                ->whereRaw("LOWER(title) LIKE ?", ['%' . strtolower($demand->subject) . '%'])
                ->exists();

            if (! $hasCourse) {
                $insights[] = [
                    'type' => 'demand_opportunity',
                    'severity' => 'high',
                    'fact' => "{$demand->count} students want {$demand->subject} but no matching course exists",
                    'evidence' => [
                        'demand_count' => $demand->count,
                        'subject' => $demand->subject,
                        'has_course' => false,
                    ],
                    'recommendation' => "Create a {$demand->subject} course to serve {$demand->count} waiting students",
                    'action' => ['label' => 'Create Course', 'route' => 'center.courses.create'],
                    'confidence' => min(1.0, $demand->count / 5),
                ];
            }
        }

        $totalDemand = DemandRequest::where('tenant_id', $tenantId)->count();
        $convertedDemand = DemandRequest::where('tenant_id', $tenantId)
            ->where('status', 'converted')
            ->count();

        if ($totalDemand >= 5 && $convertedDemand == 0) {
            $insights[] = [
                'type' => 'conversion_gap',
                'severity' => 'high',
                'fact' => "{$totalDemand} demand requests received but none converted to enrollment",
                'evidence' => [
                    'total_demand' => $totalDemand,
                    'converted' => $convertedDemand,
                    'conversion_rate' => 0,
                ],
                'recommendation' => 'Follow up with pending demand requests to convert them into students',
                'action' => ['label' => 'View Demand', 'route' => 'growth.dashboard'],
                'confidence' => 0.9,
            ];
        }

        return $insights;
    }

    protected function getCapacityInsights(int $tenantId): array
    {
        $insights = [];

        $fullCourses = Course::where('tenant_id', $tenantId)
            ->where('published', true)
            ->whereColumn('enrolled_count', '>=', 'capacity')
            ->get();

        foreach ($fullCourses as $course) {
            $waitlistCount = Waitlist::where('tenant_id', $tenantId)
                ->where('course_id', $course->id)
                ->where('status', 'pending')
                ->count();

            if ($waitlistCount >= 3) {
                $insights[] = [
                    'type' => 'capacity_expansion',
                    'severity' => 'medium',
                    'fact' => "{$course->title} is full with {$waitlistCount} students on the waitlist",
                    'evidence' => [
                        'course_id' => $course->id,
                        'course_title' => $course->title,
                        'capacity' => $course->capacity,
                        'enrolled' => $course->enrolled_count,
                        'waitlist_count' => $waitlistCount,
                    ],
                    'recommendation' => "Add another session or increase capacity to serve {$waitlistCount} waiting students",
                    'action' => ['label' => 'Edit Course', 'route' => 'center.courses.edit', 'params' => ['course' => $course->id]],
                    'confidence' => 1.0,
                ];
            }
        }

        $lowCourses = Course::where('tenant_id', $tenantId)
            ->where('published', true)
            ->whereColumn('enrolled_count', '<', 'capacity')
            ->whereRaw('enrolled_count < capacity * 0.3')
            ->get();

        foreach ($lowCourses as $course) {
            $emptySeats = $course->capacity - $course->enrolled_count;
            $insights[] = [
                'type' => 'underutilized_capacity',
                'severity' => 'low',
                'fact' => "{$course->title} has {$emptySeats} empty seats (only " . round(($course->enrolled_count / max($course->capacity, 1)) * 100) . "% full)",
                'evidence' => [
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'capacity' => $course->capacity,
                    'enrolled' => $course->enrolled_count,
                    'utilization' => round(($course->enrolled_count / max($course->capacity, 1)) * 100),
                ],
                'recommendation' => 'Promote this course or offer a discount to fill remaining seats',
                'action' => ['label' => 'Edit Course', 'route' => 'courses.edit', 'params' => $course->id],
                'confidence' => 0.8,
            ];
        }

        return $insights;
    }

    protected function getConversionInsights(int $tenantId): array
    {
        $insights = [];

        $profile = PublicProfile::where('tenant_id', $tenantId)->first();

        if ($profile) {
            $views = DB::table('growth_events')
                ->where('tenant_id', $tenantId)
                ->where('event_name', 'profile_viewed')
                ->count();

            $enrollments = Enrollment::where('tenant_id', $tenantId)->count();

            if ($views >= 10 && $enrollments == 0) {
                $insights[] = [
                    'type' => 'conversion_optimization',
                    'severity' => 'medium',
                    'fact' => "Your profile received {$views} views but no enrollments yet",
                    'evidence' => [
                        'profile_views' => $views,
                        'enrollments' => $enrollments,
                        'conversion_rate' => 0,
                    ],
                    'recommendation' => 'Review your profile and programs to improve visitor-to-student conversion',
                    'action' => ['label' => 'Edit Profile', 'route' => 'growth.profile.edit'],
                    'confidence' => 0.7,
                ];
            }
        }

        $recentDemand = DemandRequest::where('tenant_id', $tenantId)
            ->where('status', 'new')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        if ($recentDemand >= 3) {
            $insights[] = [
                'type' => 'demand_surge',
                'severity' => 'high',
                'fact' => "{$recentDemand} new demand requests in the last 7 days",
                'evidence' => [
                    'demand_count' => $recentDemand,
                    'period' => '7 days',
                ],
                'recommendation' => 'High demand detected! Consider creating new courses or adding sessions',
                'action' => ['label' => 'View Demand', 'route' => 'growth.dashboard'],
                'confidence' => 0.85,
            ];
        }

        return $insights;
    }

    protected function getGrowthInsights(int $tenantId): array
    {
        $insights = [];

        $recentViews = DB::table('growth_events')
            ->where('tenant_id', $tenantId)
            ->where('event_name', 'profile_viewed')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        $previousViews = DB::table('growth_events')
            ->where('tenant_id', $tenantId)
            ->where('event_name', 'profile_viewed')
            ->where('created_at', '>=', now()->subDays(60))
            ->where('created_at', '<', now()->subDays(30))
            ->count();

        if ($previousViews > 0 && $recentViews > $previousViews * 1.5) {
            $growth = round((($recentViews - $previousViews) / $previousViews) * 100);
            $insights[] = [
                'type' => 'growth_acceleration',
                'severity' => 'medium',
                'fact' => "Profile views grew {$growth}% this month ({$recentViews} vs {$previousViews} last month)",
                'evidence' => [
                    'current_views' => $recentViews,
                    'previous_views' => $previousViews,
                    'growth_percentage' => $growth,
                ],
                'recommendation' => 'Great momentum! Keep sharing your profile to maintain growth',
                'action' => null,
                'confidence' => 0.9,
            ];
        }

        if ($previousViews > 0 && $recentViews < $previousViews * 0.5) {
            $decline = round((($previousViews - $recentViews) / $previousViews) * 100);
            $insights[] = [
                'type' => 'growth_decline',
                'severity' => 'high',
                'fact' => "Profile views dropped {$decline}% this month ({$recentViews} vs {$previousViews} last month)",
                'evidence' => [
                    'current_views' => $recentViews,
                    'previous_views' => $previousViews,
                    'decline_percentage' => $decline,
                ],
                'recommendation' => 'Views are declining. Share your profile on social media or update your content',
                'action' => ['label' => 'Edit Profile', 'route' => 'growth.profile.edit'],
                'confidence' => 0.85,
            ];
        }

        return $insights;
    }
}
