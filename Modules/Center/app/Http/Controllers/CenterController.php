<?php

namespace Modules\Center\Http\Controllers;

use App\Models\Instructor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Redirect instructors to their specific dashboard
        if ($user && ($user->role === 'instructor' || ($user->tenant && $user->tenant->type === 'instructor'))) {
            return redirect()->route('instructor.dashboard', ['tenant' => $user->tenant->domain]);
        }

        if ($user->role !== 'center_admin' && !$user->hasAnyRole(['admin', 'center_admin', 'instructor'])) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Unauthorized role'], 403);
            }
            return redirect()->route('campus.index');
        }
        

        
        if ($user && $user->role === 'student') {
             if (request()->expectsJson()) {
                 return response()->json(['message' => 'Unauthorized role'], 403);
             }
             return redirect()->route('campus.index');
        }

        // 1. Summary Metrics & Setup Progress (Cached for 15 minutes)
        $tenant = app('tenant');
        $tenantId = $tenant->id;
        $cacheKey = "dashboard_stats_v3";

        $dashboardData = \App\Support\TenantCache::remember($cacheKey, now()->addMinutes(15), function () use ($tenantId) {
            $activeStudentsCount = Student::where('status', 'active')->count();
            
            $launchpadSteps = [
                'education_system' => \App\Models\Stage::where('tenant_id', $tenantId)->exists(),
                'instructor' => Instructor::where('tenant_id', $tenantId)->exists(),
                'course' => Course::where('tenant_id', $tenantId)->exists(),
                'student' => $activeStudentsCount > 0,
                'attendance' => \Modules\Center\Models\Attendance::where('tenant_id', $tenantId)->exists(),
            ];

            return [
                'activeStudents' => $activeStudentsCount,
                'activeCourses' => Course::where('status', 'published')->count(),
                'monthlyRevenue' => Sale::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('paid_amount'),
                'monthlyExpenses' => Expense::whereMonth('date', now()->month)
                    ->whereYear('date', now()->year)
                    ->sum('amount'),
                'launchpadSteps' => $launchpadSteps,
            ];
        });

        $activeStudents = $dashboardData['activeStudents'];
        $activeCourses = $dashboardData['activeCourses'];
        $monthlyRevenue = $dashboardData['monthlyRevenue'];
        $monthlyExpenses = $dashboardData['monthlyExpenses'];
        $launchpadSteps = $dashboardData['launchpadSteps'];
        $netProfit = $monthlyRevenue - $monthlyExpenses;
        
        $completedSteps = count(array_filter($launchpadSteps));
        $launchpadProgress = ($completedSteps / 5) * 100;

        // 1.1 Fetch Recent Activities (Cached for 5 minutes)
        $activityCacheKey = "recent_activities";
        $recentActivities = \App\Support\TenantCache::remember($activityCacheKey, now()->addMinutes(5), function () use ($tenantId) {
            return \Spatie\Activitylog\Models\Activity::where('properties->tenant_id', $tenantId)
                ->with(['causer', 'subject'])
                ->latest()
                ->take(10)
                ->get();
        });

        // 2. AI Early Warning Logic
        $atRiskStudents = $this->getAtRiskStudents($tenantId);
        $performanceTrends = $this->getPerformanceTrends($tenantId);
        $aiInsights = $this->getAIInsights($tenantId, $performanceTrends['data']);

        return view('center::index', compact(
            'activeStudents',
            'activeCourses',
            'monthlyRevenue',
            'monthlyExpenses',
            'netProfit',
            'recentActivities',
            'atRiskStudents',
            'aiInsights',
            'performanceTrends',
            'launchpadProgress',
            'launchpadSteps'
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
            ->map(function($item) {
                return [
                    'name' => $item->name,
                    'risk_level' => 'high',
                    'reason' => __('center::dashboard.insights.score_drop_detected')
                ];
            })->toArray();

        // 2. Inactive students (no activity in 10 days)
        $inactiveStudents = Student::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->whereDoesntHave('user.activities', function($q) {
                $q->where('created_at', '>=', now()->subDays(10));
            })
            ->limit(2)
            ->get()
            ->map(function($student) {
                return [
                    'name' => $student->name,
                    'risk_level' => 'medium',
                    'reason' => __('center::dashboard.insights.inactivity_detected')
                ];
            })->toArray();

        $merged = array_merge($studentsWithDrops, $inactiveStudents);
        
        // Fallback for demo if no real data yet
        if (empty($merged)) {
            return [
                ['name' => 'Demo Student', 'risk_level' => 'low', 'reason' => 'Healthy engagement patterns']
            ];
        }

        return $merged;
    }

    private function getPerformanceTrends($tenantId)
    {
        $days = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->format('Y-m-d'));
        
        $hasData = DB::table('quiz_attempts')
            ->join('students', 'quiz_attempts.user_id', '=', 'students.user_id')
            ->where('students.tenant_id', $tenantId)
            ->where('quiz_attempts.tenant_id', $tenantId) // Added explicit tenant filter
            ->exists();

        if (!$hasData) {
            // Demo Trend: A nice gentle upward curve
            return [
                'data' => [65, 70, 68, 72, 75, 78, 85],
                'is_demo' => true
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
            'data' => $days->map(fn($date) => round($data->get($date, 0)))->toArray(),
            'is_demo' => false
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('center::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('center::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('center::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
