<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Sale;
use App\Models\Expense;
use Modules\Center\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    protected $analyticsQuery;

    public function __construct(\App\Queries\CenterAnalyticsQuery $analyticsQuery)
    {
        $this->analyticsQuery = $analyticsQuery;
    }

    public function index()
    {
        $this->authorize('viewAny', Student::class);
        
        // --- 1. General Summary ---
        $stats = \App\Support\TenantCache::remember("dashboard_stats", 300, function () {
            return [
                'totalStudents' => Student::count(),
                'totalCourses' => Course::count(),
                'totalInstructors' => Instructor::count(),
                'totalRevenue' => Sale::sum('paid_amount'),
                'monthlyRevenue' => Sale::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('paid_amount'),
                'totalExpenses' => Expense::sum('amount'),
                'totalDue' => Sale::sum(\Illuminate\Support\Facades\DB::raw('total_amount - paid_amount')),
            ];
        });

        $totalStudents = $stats['totalStudents'];
        $totalCourses = $stats['totalCourses'];
        $totalInstructors = $stats['totalInstructors'];
        
        // --- 2. Financials ---
        $totalRevenue = $stats['totalRevenue'];
        $monthlyRevenueSum = $stats['monthlyRevenue'];
        $totalExpenses = $stats['totalExpenses'];
        $netProfit = $totalRevenue - $totalExpenses;
        $totalDue = $stats['totalDue'];
        
        $monthlyRevenue = $this->analyticsQuery->getMonthlyRevenue(6);
        $revenueLabels = $monthlyRevenue->pluck('months');
        $revenueData = $monthlyRevenue->pluck('sums');

        // --- 3. Attendance ---
        $attendanceStats = \App\Support\TenantCache::remember("analytics_attendance", 300, function () {
            return $this->analyticsQuery->getAttendanceStats();
        });
        $attendanceData = [
            $attendanceStats['present'] ?? 0,
            $attendanceStats['late'] ?? 0,
            $attendanceStats['absent'] ?? 0
        ];
        // --- 4. Course Performance ---
        $popularCourses = Course::withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->take(5)
            ->get();
            
        $popularCoursesLabels = $popularCourses->pluck('title');
        $popularCoursesData = $popularCourses->pluck('enrollments_count');

        $coursePerformance = $this->analyticsQuery->getCoursePerformance(5);

        // --- 5. Student Growth ---
        $studentGrowth = $this->analyticsQuery->getStudentGrowth(6);
        $growthLabels = $studentGrowth->pluck('months');
        $growthData = $studentGrowth->pluck('count');

        // --- 6. Recent Activity ---
        $recentSales = Sale::with('student')->latest()->take(5)->get();
        
        return view('center::analytics.index', compact(
            'totalStudents', 
            'totalCourses', 
            'totalInstructors',
            'totalRevenue',
            'monthlyRevenueSum',
            'totalExpenses',
            'netProfit',
            'totalDue',
            'revenueLabels',
            'revenueData',
            'attendanceData',
            'popularCoursesLabels',
            'popularCoursesData',
            'coursePerformance',
            'growthLabels',
            'growthData',
            'recentSales'
        ));
    }

    public function students()
    {
        $this->authorize('viewAny', Student::class);
        // 1. Summary Metrics
        $studentStats = \App\Support\TenantCache::remember("student_analytics_stats", 300, function () {
            return [
                'totalStudents' => Student::count(),
                'activeStudents' => Student::where('status', 'active')->count(),
                'inactiveStudents' => Student::where('status', 'inactive')->count(),
            ];
        });

        $totalStudents = $studentStats['totalStudents'];
        $activeStudents = $studentStats['activeStudents'];
        $inactiveStudents = $studentStats['inactiveStudents'];
        
        // 2. Growth Chart Data
        $studentGrowth = $this->analyticsQuery->getStudentGrowth(12);
            
        // 3. Demographics (Grade Levels)
        $studentsByGrade = $this->analyticsQuery->getStudentsByGrade();

        // 4. Top Spenders - Use database aggregation
        $topStudents = \App\Support\TenantCache::remember("analytics_top_students", 300, function () {
            return Student::select('students.*')
                ->with(['grade.stage'])
                ->withCount('bookings')
                ->withSum('sales', 'paid_amount')
                ->orderByDesc('sales_sum_paid_amount')
                ->take(5)
                ->get();
        });
            
        // 5. Debtors (Students with outstanding payments) - Optimized with DB aggregation
        $debtorStudents = \App\Support\TenantCache::remember("analytics_debtor_students", 300, function () {
            return Student::select('students.*')
                ->with(['grade.stage'])
                ->selectRaw('SUM(sales.total_amount - sales.paid_amount) as total_debt')
                ->join('sales', 'students.id', '=', 'sales.student_id')
                ->whereRaw('sales.paid_amount < sales.total_amount')
                ->groupBy('students.id', 'students.name', 'students.email', 'students.phone', 
                         'students.status', 'students.grade_id', 'students.tenant_id', 
                         'students.profile_photo', 'students.created_at', 'students.updated_at')
                ->orderByDesc('total_debt')
                ->limit(5)
                ->get();
        });
        return view('center::analytics.students', compact(
            'totalStudents', 
            'activeStudents',
            'inactiveStudents',
            'studentGrowth', 
            'topStudents',
            'studentsByGrade',
            'debtorStudents'
        ));
    }

    public function instructors()
    {
        $this->authorize('viewAny', Instructor::class);
        
        // Cache for 5 minutes
        $instructorStats = \App\Support\TenantCache::remember("instructor_stats", 300, function () {
            // Optimized query: Get instructors with course count and total enrollments count via HasManyThrough
            $instructors = Instructor::withCount(['courses', 'enrollments as total_students'])
                ->get();

            return $instructors;
        });

        return view('center::analytics.instructors', compact('instructorStats'));
    }

    public function courses()
    {
        $this->authorize('viewAny', Course::class);
        
        $courses = Course::select('courses.id', 'courses.title', 'courses.instructor_id', 'courses.created_at', 'courses.price')
            ->with(['instructor:id,name,email'])
            ->withCount(['enrollments', 'schedules'])
            ->latest()
            ->paginate(15); // Added pagination
            
        return view('center::analytics.courses', compact('courses'));
    }

    public function finance(Request $request)
    {
        $this->authorize('viewAny', Sale::class);
        
        $year = $request->get('year', now()->year);
        
        // 1. Core Summary (All Time or Current Settings)
        $totalRevenueAllTime = Sale::sum('paid_amount');
        $totalExpensesAllTime = Expense::sum('amount');
        $totalCommissionsAllTime = \App\Models\Commission::sum('amount');
        $netProfitAllTime = $totalRevenueAllTime - ($totalExpensesAllTime + $totalCommissionsAllTime);

        // 2. Yearly Breakdown Logic (For the Profit/Loss Table)
        $monthlyRevenue = Sale::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(paid_amount) as total')
        )->whereYear('created_at', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('total', 'month');

        $monthlyExpenses = Expense::select(
            DB::raw('MONTH(date) as month'),
            DB::raw('SUM(amount) as total')
        )->whereYear('date', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('total', 'month');

        $monthlyCommissions = \App\Models\Commission::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amount) as total')
        )->whereYear('created_at', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('total', 'month');

        $expenseCategories = Expense::select('category', DB::raw('SUM(amount) as total'))
            ->whereYear('date', $year)
            ->groupBy('category')
            ->get();

        $reportData = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenue = $monthlyRevenue[$m] ?? 0;
            $opExpenses = $monthlyExpenses[$m] ?? 0;
            $commissions = $monthlyCommissions[$m] ?? 0;
            $profit = $revenue - ($opExpenses + $commissions);
            
            $reportData[$m] = [
                'month_name' => \Carbon\Carbon::create()->month($m)->translatedFormat('F'),
                'revenue' => (float)$revenue,
                'op_expenses' => (float)$opExpenses,
                'commissions' => (float)$commissions,
                'profit' => (float)$profit,
            ];
        }

        $totalYearlyRevenue = $monthlyRevenue->sum();
        $totalYearlyOpExpenses = $monthlyExpenses->sum();
        $totalYearlyCommissions = $monthlyCommissions->sum();
        $totalYearlyProfit = $totalYearlyRevenue - ($totalYearlyOpExpenses + $totalYearlyCommissions);
        
        $totalYearlyRequired = DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->whereYear('enrollments.enrolled_at', $year)
            ->where('enrollments.tenant_id', $this->tenant->id)
            ->sum('courses.price');

        $totalYearlyDue = max(0, $totalYearlyRequired - $totalYearlyRevenue);

        // 3. Recent Transactions
        $sales = Sale::with('student')->latest()->paginate(10);
        $recentExpenses = Expense::latest()->take(5)->get();
        $recentCommissions = \App\Models\Commission::with(['instructor', 'sale'])->latest()->take(5)->get();

        return view('center::analytics.finance', compact(
            'year', 'reportData', 'expenseCategories',
            'totalYearlyRevenue', 'totalYearlyOpExpenses', 'totalYearlyCommissions', 'totalYearlyProfit', 'totalYearlyDue',
            'totalRevenueAllTime', 'netProfitAllTime', 'sales', 'recentExpenses', 'recentCommissions'
        ));
    }

    public function profitLoss(Request $request)
    {
        return redirect()->route('center.analytics.finance', $request->all());
    }

    public function commissions()
    {
        $this->authorize('viewAny', Sale::class);
        $commissions = \App\Models\Commission::with(['instructor', 'sale'])->latest()->paginate(20);
        $totalCommissions = \App\Models\Commission::sum('amount');
        return view('center::analytics.finance.commissions', compact('commissions', 'totalCommissions'));
    }

    public function discounts()
    {
        $this->authorize('viewAny', Sale::class);
        $discounts = Sale::where('discount_amount', '>', 0)->with('student')->latest()->paginate(20);
        $totalDiscounts = Sale::sum('discount_amount');
        return view('center::analytics.finance.discounts', compact('discounts', 'totalDiscounts'));
    }

    public function taxes()
    {
        $this->authorize('viewAny', Sale::class);
        $taxes = Sale::where('tax_amount', '>', 0)->with('student')->latest()->paginate(20);
        $totalTaxes = Sale::sum('tax_amount');
        return view('center::analytics.finance.taxes', compact('taxes', 'totalTaxes'));
    }

    public function attendance()
    {
        $this->authorize('viewAny', Attendance::class);
        $attendanceStats = $this->analyticsQuery->getAttendanceStats();
        $recentAttendance = Attendance::with(['student', 'course', 'schedule'])
            ->latest()
            ->paginate(20);

        return view('center::analytics.attendance', compact('attendanceStats', 'recentAttendance'));
    }
}
