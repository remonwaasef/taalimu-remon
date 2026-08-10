<?php

namespace Modules\Instructor\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\Schedule;
use App\Models\Student;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Modules\Center\Models\Attendance;
use Modules\Instructor\Http\Controllers\Traits\ResolvesInstructor;

class InstructorController extends Controller
{
    use ResolvesInstructor;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instructor = $this->instructor;

        if (! $instructor) {
            $courses = Course::take(5)->get();
            $totalStudents = Student::count();
            $totalCourses = Course::count();
            $monthlyRevenue = Sale::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('paid_amount');
        } else {
            $courses = $instructor->courses()->withCount('enrollments')->get();
            $totalStudents = Student::whereHas('enrollments', function ($q) use ($instructor) {
                $q->whereIn('course_id', $instructor->courses->pluck('id'));
            })->count();
            $totalCourses = $courses->count();

            $monthlyRevenue = Sale::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->whereHas('student.enrollments', function ($q) use ($instructor) {
                    $q->whereIn('course_id', $instructor->courses->pluck('id'));
                })->sum('paid_amount');
        }

        // Attendance Rate Calculation
        $attendanceQuery = Attendance::query();
        if ($instructor) {
            $attendanceQuery->whereIn('course_id', $instructor->courses->pluck('id'));
        }
        $totalAttendanceCount = (clone $attendanceQuery)->count();
        $presentAttendanceCount = (clone $attendanceQuery)->where('status', 'present')->count();
        $attendanceRate = $totalAttendanceCount > 0 ? round(($presentAttendanceCount / $totalAttendanceCount) * 100) : 0;

        // Today's Schedules
        $dayOfWeek = now()->dayOfWeek;
        $todaySchedulesQuery = Schedule::where('day_of_week', $dayOfWeek)->with('course');
        if ($instructor) {
            $todaySchedulesQuery->whereIn('course_id', $instructor->courses->pluck('id'));
        }
        $todaySchedules = $todaySchedulesQuery->get();

        // Real Setup Progress
        $hasProfile = !empty(auth()->user()->name);
        $hasGroup = $totalCourses > 0;
        $hasStudents = $totalStudents > 0;
        
        $completedSteps = ($hasProfile ? 1 : 0) + ($hasGroup ? 1 : 0) + ($hasStudents ? 1 : 0);
        $setupProgress = round(($completedSteps / 3) * 100);

        // Attendance Analytics (Last 7 Days)
        $attendanceData = [];
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days[] = $date->translatedFormat('D');

            $query = Attendance::whereDate('session_date', $date->toDateString());
            if ($instructor) {
                $query->whereIn('course_id', $instructor->courses->pluck('id'));
            }
            $attendanceData[] = $query->count();
        }

        return view('instructor::index', compact(
            'courses',
            'totalStudents',
            'totalCourses',
            'monthlyRevenue',
            'attendanceRate',
            'totalAttendanceCount',
            'todaySchedules',
            'hasProfile',
            'hasGroup',
            'hasStudents',
            'setupProgress',
            'attendanceData',
            'days'
        ));
    }

    /**
     * Show the QR Scanner interface for a specific course/group
     */
    public function scanner(Course $course)
    {
        $dayOfWeek = now()->dayOfWeek;
        $schedule = Schedule::where('course_id', $course->id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        return view('instructor::scanner', compact('course', 'schedule'));
    }

    /**
     * Process a scanned QR identifier
     */
    public function scan(Request $request, Course $course)
    {
        $request->validate([
            'qr_identifier' => 'required|string',
            'schedule_id' => 'required|exists:schedules,id',
        ]);

        $user = \App\Models\User::where('qr_identifier', $request->qr_identifier)->first();

        if (! $user || ! $user->student) {
            return response()->json([
                'success' => false,
                'message' => __('instructor::messages.student_not_found'),
            ], 404);
        }

        $student = $user->student;

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (! $enrollment) {
            return response()->json([
                'success' => false,
                'student_name' => $student->name,
                'message' => __('instructor::messages.student_not_enrolled'),
            ], 403);
        }

        $attendanceService = app(AttendanceService::class);

        if ($attendanceService->hasAttendedToday($student->id, $request->schedule_id)) {
            return response()->json([
                'success' => true,
                'student_name' => $student->name,
                'already_marked' => true,
                'message' => __('instructor::messages.already_attended'),
            ]);
        }

        $attendanceService->markAttendance([
            'tenant_id' => $student->tenant_id,
            'student_id' => $student->id,
            'course_id' => $course->id,
            'schedule_id' => $request->schedule_id,
            'session_date' => today(),
            'status' => 'present',
        ]);

        $msg = __('instructor::messages.attendance_notification', [
            'student' => $student->name,
            'course' => $course->title,
            'center' => $this->tenant->name,
        ]);
        $phoneToNotify = $student->parent_phone ?: $student->phone;
        $whatsappUrl = 'https://wa.me/'.preg_replace('/[^0-9]/', '', $phoneToNotify).'?text='.urlencode($msg);

        return response()->json([
            'success' => true,
            'student_name' => $student->name,
            'remaining_sessions' => $enrollment->fresh()->remaining_sessions,
            'whatsapp_url' => $whatsappUrl,
            'message' => __('instructor::messages.scanned_success'),
        ]);
    }

    /**
     * Display billing information for students
     */
    public function billing()
    {
        $instructor = $this->instructor;

        if (! $instructor) {
            $students = Student::with(['user', 'sales', 'enrollments.course'])->take(10)->get();
        } else {
            $students = Student::whereHas('enrollments', function ($q) use ($instructor) {
                $q->whereIn('course_id', $instructor->courses->pluck('id'));
            })->with(['user', 'sales', 'enrollments.course'])->get();
        }

        return view('instructor::billing', compact('students'));
    }

    /**
     * Quickly mark a student as paid for a specific amount
     */
    public function markPaid(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $student = Student::with(['enrollments.course', 'sales'])->findOrFail($request->student_id);

        // SEC-02: the acting user must actually be the instructor of record;
        // never allow arbitrary authenticated users to manufacture paid Sales.
        $this->authorizeInstructor($student);

        $totalDue = $student->enrollments->sum(function ($enrollment) {
            return $enrollment->course->price ?? 0;
        });
        $totalPaid = $student->sales->sum('paid_amount');
        $balance = $totalDue - $totalPaid;

        if ($request->amount > $balance) {
            return back()->with('error', __('instructor::messages.collection_error', ['amount' => $request->amount, 'balance' => $balance]));
        }

        $sale = Sale::create([
            'tenant_id' => $student->tenant_id,
            'student_id' => $student->id,
            'total_amount' => $request->amount,
            'paid_amount' => $request->amount,
            'status' => 'paid',
            'payment_method' => 'cash',
            'notes' => $request->notes ?? __('instructor::messages.quick_collection_note'),
        ]);

        Payment::create([
            'tenant_id' => $student->tenant_id,
            'sale_id' => $sale->id,
            'amount' => $request->amount,
            'payment_method' => 'cash',
            'received_by' => auth()->id(),
            'paid_at' => now(),
        ]);

        try {
            $tenant = $this->tenant;
            $tenantSettings = $tenant->settings['email_templates'] ?? [];

            $realEmail = null;
            $studentEmail = $student->email ?? ($student->user ? $student->user->email : null);
            if ($studentEmail && ! preg_match('/^std\d+\..+@taalimu\.com$/', $studentEmail)) {
                $realEmail = $studentEmail;
            }

            if (! empty($tenantSettings['notif_payment_confirmed_enabled']) && ($realEmail || $student->parent_email)) {
                $subject = $tenantSettings['notif_payment_confirmed_subject'] ?? 'تأكيد استلام دفعة';
                $body = $tenantSettings['notif_payment_confirmed_body'] ?? '';

                $variables = [
                    'اسم_الطالب' => $student->name,
                    'اسم_المركز' => $tenant->name,
                    'المبلغ_المدفوع' => $request->amount.' ج.م',
                    'تاريخ_الدفع' => now()->format('Y-m-d'),
                    'المتبقي' => max(0, $balance - $request->amount).' ج.م',
                    'طريقة_الدفع' => 'نقدي',
                ];

                if ($realEmail) {
                    \Illuminate\Support\Facades\Mail::to($realEmail)->queue(new \App\Mail\NotifPaymentConfirmedMail(
                        $subject, $body, $variables, $tenant->name, $student->name
                    ));
                }

                if ($student->parent_email) {
                    \Illuminate\Support\Facades\Mail::to($student->parent_email)->queue(new \App\Mail\NotifPaymentConfirmedMail(
                        $subject, $body, $variables, $tenant->name, $student->name
                    ));
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Payment confirmation email failed: '.$e->getMessage());
        }

        return back()->with('success', __('instructor::messages.collection_success', ['amount' => $request->amount, 'student' => $student->name]));
    }

    public function studentReports()
    {
        $instructor = $this->instructor;
        $courseIds = $instructor ? $instructor->courses->pluck('id') : Course::pluck('id');

        $students = Student::whereHas('enrollments', function ($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds);
        })->with(['enrollments' => function ($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds)->with('course');
        }])->get();

        foreach ($students as $student) {
            $totalSessions = $student->enrollments->sum('course.sessions_count');
            $attendedSessions = Attendance::where('student_id', $student->id)
                ->whereIn('course_id', $courseIds)
                ->where('status', 'present')
                ->count();

            $student->attendance_percentage = $totalSessions > 0 ? round(($attendedSessions / $totalSessions) * 100) : 0;
            $student->attended_count = $attendedSessions;
            $student->total_sessions = $totalSessions;
        }

        return view('instructor::reports.students', compact('students'));
    }

    public function paymentReports()
    {
        $instructor = $this->instructor;
        $courseIds = $instructor ? $instructor->courses->pluck('id') : Course::pluck('id');

        $students = Student::whereHas('enrollments', function ($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds);
        })->with(['enrollments' => function ($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds)->with('course');
        }, 'sales'])->get();

        foreach ($students as $student) {
            $student->total_due = $student->enrollments->sum(function ($e) {
                return $e->course->price ?? 0;
            });
            $student->total_paid = $student->sales->sum('paid_amount');
            $student->balance = $student->total_due - $student->total_paid;
            $student->financial_status = $student->balance <= 0 ? 'paid' : ($student->total_paid > 0 ? 'partial' : 'unpaid');
        }

        $totalDue = $students->sum('total_due');
        $totalPaid = $students->sum('total_paid');
        $totalBalance = $students->sum('balance');

        return view('instructor::reports.payments', compact('students', 'totalDue', 'totalPaid', 'totalBalance'));
    }
}
