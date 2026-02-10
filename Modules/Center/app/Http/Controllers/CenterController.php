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

        // 1. Summary Metrics (Cached for 15 minutes)
        $tenantId = auth()->user()->tenant_id;
        $cacheKey = "tenant_{$tenantId}_dashboard_stats";

        $stats = \App\Support\TenantCache::remember($cacheKey, now()->addMinutes(15), function () {
            return [
                'activeStudents' => Student::where('status', 'active')->count(),
                'activeCourses' => Course::where('status', 'published')->count(),
                'monthlyRevenue' => Sale::where('status', 'paid')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('paid_amount'),
                'monthlyExpenses' => Expense::whereMonth('date', now()->month)
                    ->whereYear('date', now()->year)
                    ->sum('amount'),
            ];
        });

        $activeStudents = $stats['activeStudents'];
        $activeCourses = $stats['activeCourses'];
        $monthlyRevenue = $stats['monthlyRevenue'];
        $monthlyExpenses = $stats['monthlyExpenses'];
        $netProfit = $monthlyRevenue - $monthlyExpenses;

        // 1.1 Fetch Recent Activities for this tenant
        $recentActivities = \Spatie\Activitylog\Models\Activity::where('properties->tenant_id', $tenantId)
            ->latest()
            ->take(10)
            ->get();

        // 2. AI Early Warning Logic
        $atRiskStudents = $this->getAtRiskStudents($tenantId);
        $performanceTrends = $this->getPerformanceTrends($tenantId);
        $aiInsights = $this->getAIInsights($tenantId, $performanceTrends['data']);

        // 3. Onboarding Logic (The Living Dashboard)
        $onboardingService = app(\Modules\Center\Services\OnboardingService::class);
        $onboardingStatus = $onboardingService->getStatus($tenantId);

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
            'onboardingStatus'
        ));
    }

    public function quickAddInstructor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20'
        ]);

        $instructor = Instructor::create([
            'tenant_id' => auth()->user()->tenant_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'status' => 'active'
        ]);

        return response()->json(['success' => true, 'instructor' => $instructor]);
    }

    public function quickAddCourse(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0'
        ]);

        $course = Course::create([
            'tenant_id' => auth()->user()->tenant_id,
            'title' => $request->name,
            'price' => $request->price,
            'status' => 'published'
        ]);

        return response()->json(['success' => true, 'course' => $course]);
    }

    public function quickAddStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20'
        ]);

        $tenantId = auth()->user()->tenant_id;
        
        // Try to find the first grade in the first stage as default
        $grade = \App\Models\Grade::where('tenant_id', $tenantId)->first();

        $student = Student::create([
            'tenant_id' => $tenantId,
            'name' => $request->name,
            'phone' => $request->phone,
            'grade_id' => $grade?->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        return response()->json(['success' => true, 'student' => $student]);
    }

    public function quickAddSchedule(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        $course = \App\Models\Course::where('tenant_id', $tenantId)->first();
        $classroom = \App\Models\Classroom::where('tenant_id', $tenantId)->first();
        $instructor = \App\Models\Instructor::where('tenant_id', $tenantId)->first();

        if (!$course || !$instructor) {
            return response()->json(['success' => false, 'message' => 'يرجى إضافة مدرس ودورة أولاً']);
        }

        // Create a default classroom if none exists
        if (!$classroom) {
            $classroom = \App\Models\Classroom::create([
                'tenant_id' => $tenantId,
                'name' => 'القاعة الرئيسية',
                'capacity' => 50
            ]);
        }

        $schedule = \App\Models\Schedule::create([
            'tenant_id' => $tenantId,
            'course_id' => $course->id,
            'instructor_id' => $instructor->id,
            'classroom_id' => $classroom->id,
            'day_of_week' => strtolower(now()->format('l')),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
        ]);

        return response()->json(['success' => true, 'schedule' => $schedule]);
    }

    public function quickAddAttendance(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        $student = \App\Models\Student::where('tenant_id', $tenantId)->first();
        $schedule = \App\Models\Schedule::where('tenant_id', $tenantId)->first();

        if (!$student || !$schedule) {
            return response()->json(['success' => false, 'message' => 'يرجى إضافة طالب وجدول أولاً']);
        }

        $attendance = \Modules\Center\Models\Attendance::create([
            'tenant_id' => $tenantId,
            'student_id' => $student->id,
            'course_id' => $schedule->course_id,
            'schedule_id' => $schedule->id,
            'session_date' => now()->toDateString(),
            'status' => 'present',
        ]);

        return response()->json(['success' => true, 'attendance' => $attendance]);
    }

    public function completeOnboarding(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        $tenant = \App\Models\Tenant::find($tenantId);
        $tenant->update(['onboarding_completed_at' => now()]);

        return response()->json(['success' => true]);
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
