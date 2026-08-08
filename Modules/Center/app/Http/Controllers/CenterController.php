<?php

namespace Modules\Center\Http\Controllers;

use App\Models\Course;
use App\Models\Expense;
use App\Models\Sale;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Center\Http\Controllers\CenterBaseController as Controller;

class CenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $tenant = $this->tenant ?? (app()->bound('tenant') ? app('tenant') : null);
        $tenantDomain = $tenant?->domain ?? $user?->tenant?->domain;

        // Redirect instructors to their specific dashboard
        if ($user && ($user->role === 'instructor' || ($user->tenant && $user->tenant->type === 'instructor'))) {
            return redirect()->route('instructor.dashboard', ['tenant' => $tenantDomain]);
        }

        $validCenterRoles = ['admin', 'center_admin', 'instructor', 'secretary', 'accountant', 'staff', 'support_agent', 'finance_manager', 'content_manager'];

        if ($user && $user->role !== 'center_admin' && ! $user->hasAnyRole($validCenterRoles)) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Unauthorized role'], 403);
            }

            return redirect()->route('campus.index', ['tenant' => $tenantDomain]);
        }

        if ($user && $user->role === 'student') {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Unauthorized role'], 403);
            }

            return redirect()->route('campus.index', ['tenant' => $tenantDomain]);
        }

        // 1. Summary Metrics & Setup Progress (Cached for 15 minutes)
        $tenant = $this->tenant;
        $tenantId = $tenant->id;
        $cacheKey = 'dashboard_stats_v3';

        $dashboardData = \App\Support\TenantCache::remember($cacheKey, now()->addMinutes(15), function () {
            $activeStudentsCount = Student::where('status', 'active')->count();

            // Sargable month boundaries so the created_at/date indexes are used
            // instead of MONTH()/YEAR() forcing a full table scan.
            $monthStart = now()->startOfMonth();
            $monthEnd = now()->endOfMonth();

            // Attendance Rate for the current week
            $thisWeekAttendance = \Modules\Center\Models\Attendance::where('created_at', '>=', now()->startOfWeek())
                ->count();
            $expectedAttendance = \App\Models\Enrollment::where('status', 'active')->count(); // Rough estimation
            $attendanceRate = $expectedAttendance > 0 ? round(($thisWeekAttendance / $expectedAttendance) * 100) : 0;

            return [
                'activeStudents' => $activeStudentsCount,
                'activeCourses' => Course::where('status', 'published')->count(),
                'monthlyRevenue' => Sale::whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('paid_amount'),
                'monthlyExpenses' => Expense::whereBetween('date', [$monthStart, $monthEnd])
                    ->sum('amount'),
                'sessionsToday' => \App\Models\Schedule::where('day_of_week', strtolower(now()->format('l')))->count(),
                'attendanceRate' => min($attendanceRate, 100),
                'overdueAmount' => Sale::whereRaw('total_amount > paid_amount')->sum(DB::raw('total_amount - paid_amount')),
            ];
        });

        $activeStudents = $dashboardData['activeStudents'];
        $activeCourses = $dashboardData['activeCourses'];
        $monthlyRevenue = $dashboardData['monthlyRevenue'];
        $monthlyExpenses = $dashboardData['monthlyExpenses'];
        $sessionsToday = $dashboardData['sessionsToday'];
        $attendanceRate = $dashboardData['attendanceRate'];
        $overdueAmount = $dashboardData['overdueAmount'];
        $netProfit = $monthlyRevenue - $monthlyExpenses;

        // 1.1 Fetch Recent Activities (Cached for 5 minutes)
        $activityCacheKey = 'recent_activities';
        $recentActivities = \App\Support\TenantCache::remember($activityCacheKey, now()->addMinutes(5), function () use ($tenantId) {
            return \Spatie\Activitylog\Models\Activity::where('properties->tenant_id', $tenantId)
                ->where('created_at', '>=', now()->subDays(90))
                ->with(['causer', 'subject'])
                ->latest()
                ->take(10)
                ->get();
        });

        // 2. AI Early Warning Logic (Cached for 30 minutes)
        $aiCacheKey = "dashboard_ai_insights_v3_{$tenantId}";
        $aiData = \App\Support\TenantCache::remember($aiCacheKey, now()->addMinutes(30), function () use ($tenantId) {
            $performanceTrends = $this->getPerformanceTrends($tenantId);

            return [
                'atRiskStudents' => $this->getAtRiskStudents($tenantId),
                'performanceTrends' => $performanceTrends,
                'aiInsights' => $this->getAIInsights($tenantId, $performanceTrends['data']),
            ];
        });

        $atRiskStudents = $aiData['atRiskStudents'];
        $performanceTrends = $aiData['performanceTrends'];
        $aiInsights = $aiData['aiInsights'];

        $activeGroups = $activeCourses;
        $activeInstructors = \App\Support\TenantCache::remember('active_instructors_count', now()->addMinutes(15), function () {
            return \App\Models\Instructor::count();
        });
        $recentStudents = Student::latest()->take(5)->get();
        $recentGroups = Course::with('instructor')->withCount('enrollments as students_count')->latest()->take(5)->get();

        // Launchpad Setup Steps Detection
        $launchpadSteps = [
            'education_system' => \App\Models\Stage::exists(),
            'instructor'       => \App\Models\Instructor::exists(),
            'course'           => Course::exists(),
            'student'          => Student::exists(),
            'attendance'       => \Modules\Center\Models\Attendance::exists(),
        ];

        $completedCount = count(array_filter($launchpadSteps));
        $launchpadProgress = round(($completedCount / count($launchpadSteps)) * 100);
        $showLaunchpad = $launchpadProgress < 100;

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'activeStudents' => $activeStudents,
                    'activeCourses' => $activeCourses,
                    'activeGroups' => $activeGroups,
                    'activeInstructors' => $activeInstructors,
                    'monthlyRevenue' => $monthlyRevenue,
                    'monthlyExpenses' => $monthlyExpenses,
                    'netProfit' => $netProfit,
                    'sessionsToday' => $sessionsToday,
                    'attendanceRate' => $attendanceRate,
                    'overdueAmount' => $overdueAmount,
                    'launchpadProgress' => $launchpadProgress,
                    'showLaunchpad' => $showLaunchpad,
                ],
            ]);
        }

        return view('center::index', compact(
            'activeStudents',
            'activeCourses',
            'activeGroups',
            'activeInstructors',
            'monthlyRevenue',
            'monthlyExpenses',
            'netProfit',
            'recentActivities',
            'recentStudents',
            'recentGroups',
            'atRiskStudents',
            'aiInsights',
            'performanceTrends',
            'sessionsToday',
            'attendanceRate',
            'overdueAmount',
            'launchpadSteps',
            'launchpadProgress',
            'showLaunchpad'
        ));
    }

    private function getAtRiskStudents($tenantId)
    {
        // 1. Students with significant score drops
        // We get students who have at least 5 attempts in total
        $subquery = DB::table('quiz_attempts')
            ->join('students', 'quiz_attempts.user_id', '=', 'students.user_id')
            ->where('students.tenant_id', $tenantId)
            ->where('quiz_attempts.tenant_id', $tenantId) // Extra security layer
            ->select('quiz_attempts.*', 'students.name', DB::raw('ROW_NUMBER() OVER(PARTITION BY quiz_attempts.user_id ORDER BY quiz_attempts.created_at DESC) as row_num'));

        $studentsWithDrops = DB::query()
            ->fromSub($subquery, 'ranked_attempts')
            ->select('name', 'user_id')
            ->selectRaw('AVG(CASE WHEN row_num <= 3 THEN score END) as recent_avg')
            ->selectRaw('AVG(CASE WHEN row_num > 3 THEN score END) as baseline_avg')
            ->groupBy('user_id', 'name')
            ->havingRaw('baseline_avg IS NOT NULL AND recent_avg < (baseline_avg * 0.85)')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'risk_level' => 'high',
                    'reason' => __('center::dashboard.insights.score_drop_detected'),
                ];
            })->toArray();

        // 2. Inactive students (no activity in 10 days)
        $inactiveStudents = Student::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->whereDoesntHave('user.activities', function ($q) {
                $q->where('created_at', '>=', now()->subDays(10));
            })
            ->limit(2)
            ->get()
            ->map(function ($student) {
                return [
                    'name' => $student->name,
                    'risk_level' => 'medium',
                    'reason' => __('center::dashboard.insights.inactivity_detected'),
                ];
            })->toArray();

        $merged = array_merge($studentsWithDrops, $inactiveStudents);

        // Fallback for demo if no real data yet
        if (empty($merged)) {
            return [
                ['name' => 'Demo Student', 'risk_level' => 'low', 'reason' => 'Healthy engagement patterns'],
            ];
        }

        return $merged;
    }

    private function getPerformanceTrends($tenantId)
    {
        $days = collect(range(6, 0))->map(fn ($i) => now()->subDays($i)->format('Y-m-d'));

        $hasData = DB::table('quiz_attempts')
            ->join('students', 'quiz_attempts.user_id', '=', 'students.user_id')
            ->where('students.tenant_id', $tenantId)
            ->where('quiz_attempts.tenant_id', $tenantId) // Added explicit tenant filter
            ->exists();

        if (! $hasData) {
            // Demo Trend: A nice gentle upward curve
            return [
                'data' => [65, 70, 68, 72, 75, 78, 85],
                'is_demo' => true,
            ];
        }

        $data = DB::table('quiz_attempts')
            ->join('students', 'quiz_attempts.user_id', '=', 'students.user_id')
            ->where('students.tenant_id', $tenantId)
            ->where('quiz_attempts.tenant_id', $tenantId) // Added explicit tenant filter
            ->where('quiz_attempts.created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(quiz_attempts.created_at) as date, AVG(score) as avg_score')
            ->groupBy('date')
            ->pluck('avg_score', 'date');

        return [
            'data' => $days->map(fn ($date) => round($data->get($date, 0)))->toArray(),
            'is_demo' => false,
        ];
    }

    private function getAIInsights($tenantId, $performanceTrends)
    {
        $insights = [];

        // Check 1: General Trend
        $lastValue = end($performanceTrends);
        $prevValue = prev($performanceTrends);

        if ($lastValue > $prevValue) {
            $insights[] = ['type' => 'success', 'text' => __('center::dashboard.insights.upward_trend')];
        } elseif ($lastValue < $prevValue && $lastValue > 0) {
            $insights[] = ['type' => 'warning', 'text' => __('center::dashboard.insights.downward_trend')];
        }

        // Check 2: Top Course
        $topCourse = Course::where('status', 'published')
            ->withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->first();

        if ($topCourse) {
            $insights[] = ['type' => 'info', 'text' => __('center::dashboard.insights.top_performer', ['course' => $topCourse->title])];
        }

        if (empty($insights)) {
            $insights[] = ['type' => 'info', 'text' => __('center::dashboard.insights.low_engagement')];
        }

        return $insights;
    }


}
