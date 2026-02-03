<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Sale;
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
                'totalDue' => Sale::sum(\Illuminate\Support\Facades\DB::raw('total_amount - paid_amount')),
            ];
        });

        $totalStudents = $stats['totalStudents'];
        $totalCourses = $stats['totalCourses'];
        $totalInstructors = $stats['totalInstructors'];
        
        // --- 2. Financials ---
        $totalRevenue = $stats['totalRevenue'];
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

    public function finance()
    {
        $this->authorize('viewAny', Sale::class);
        $totalRevenue = Sale::sum('paid_amount');
        $totalDue = Sale::sum(DB::raw('total_amount - paid_amount'));
        
        $sales = Sale::with('student')->latest()->paginate(20);
        $monthlyRevenue = \App\Support\TenantCache::remember("analytics_monthly_revenue", 600, function () {
            return $this->analyticsQuery->getMonthlyRevenue(12);
        });
        return view('center::analytics.finance', compact('totalRevenue', 'totalDue', 'sales', 'monthlyRevenue'));
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
