<?php

namespace Modules\Parent\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Sale;
use App\Models\Schedule;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ParentController extends Controller implements HasMiddleware
{
    /**
     * Role guard: only users linked to a Guardian can access the parent portal.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $user = auth()->user();
                if ($user && ! $user->guardian) {
                    return redirect()->route('center.dashboard', ['tenant' => app('tenant')->domain])
                        ->with('error', 'هذه الصفحة مخصصة لأولياء الأمور فقط.');
                }

                return $next($request);
            }),
        ];
    }

    protected function children()
    {
        return auth()->user()->guardian
            ->students()
            ->with('grade')
            ->get();
    }

    protected function aggregatePerChild()
    {
        $tenantId = app('tenant')->id;
        $aggregates = [];

        foreach ($this->children() as $student) {
            $enrollments = $student->enrollments()->count();
            $sales = Sale::where('student_id', $student->id)->get();
            $totalDebt = $sales->sum('total_amount') - $sales->sum('paid_amount');

            $attendances = \Modules\Center\Models\Attendance::where('student_id', $student->id)->get();
            $totalAtt = $attendances->count();
            $presentAtt = $attendances->whereIn('status', ['present', 'late'])->count();
            $attendanceRate = $totalAtt > 0 ? round(($presentAtt / $totalAtt) * 100) : null;

            $aggregates[$student->id] = [
                'enrollments_count' => $enrollments,
                'total_debt' => $totalDebt,
                'attendance_rate' => $attendanceRate,
            ];
        }

        return $aggregates;
    }

    public function index()
    {
        $guardian = auth()->user()->guardian;
        $children = $this->children();
        $aggregates = $this->aggregatePerChild();

        $totalDebt = array_sum(array_column($aggregates, 'total_debt'));
        $totalChildren = $children->count();
        $totalCourses = array_sum(array_column($aggregates, 'enrollments_count'));

        return view('parent::index', compact('guardian', 'children', 'aggregates', 'totalDebt', 'totalChildren', 'totalCourses'));
    }

    public function courses()
    {
        $guardian = auth()->user()->guardian;
        $children = $this->children()->load(['enrollments.course.sections.lessons']);

        return view('parent::courses', compact('guardian', 'children'));
    }

    public function schedule()
    {
        $guardian = auth()->user()->guardian;

        $courseIds = $this->children()
            ->flatMap(fn ($student) => $student->enrollments()->pluck('course_id'))
            ->unique();

        $schedules = Schedule::with(['course', 'classroom' => function ($q) {
            $q->withoutGlobalScopes();
        }, 'instructor'])
            ->whereIn('course_id', $courseIds)
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $days = [
            0 => 'الأحد',
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
        ];

        return view('parent::schedule', compact('guardian', 'schedules', 'days'));
    }

    public function attendance()
    {
        $guardian = auth()->user()->guardian;

        $studentIds = $this->children()->pluck('id');

        $attendances = \Modules\Center\Models\Attendance::with(['student', 'course'])
            ->whereIn('student_id', $studentIds)
            ->latest()
            ->paginate(12);

        return view('parent::attendance', compact('guardian', 'attendances'));
    }

    public function finances()
    {
        $guardian = auth()->user()->guardian;
        $children = $this->children();

        $perChild = [];
        $totalDebt = 0;

        foreach ($children as $student) {
            $sales = Sale::with(['payments'])
                ->where('student_id', $student->id)
                ->latest()
                ->get();

            $total = $sales->sum('total_amount');
            $paid = $sales->sum('paid_amount');
            $debt = $total - $paid;
            $totalDebt += $debt;

            $perChild[$student->id] = [
                'sales' => $sales,
                'total' => $total,
                'paid' => $paid,
                'debt' => $debt,
            ];
        }

        return view('parent::finances', compact('guardian', 'children', 'perChild', 'totalDebt'));
    }

    public function profile()
    {
        $guardian = auth()->user()->guardian;
        $children = $this->children();

        return view('parent::profile', compact('guardian', 'children'));
    }
}