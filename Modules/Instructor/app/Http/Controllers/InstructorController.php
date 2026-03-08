<?php

namespace Modules\Instructor\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\Enrollment;
use App\Models\Sale;
use App\Models\Payment;
use App\Services\AttendanceService;
use Modules\Center\Models\Attendance;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instructor = auth()->user()->instructor;
        
        if (!$instructor) {
            // Fallback for demo or admin
            $courses = Course::take(5)->get();
            $studentsCount = Student::count();
        } else {
            $courses = $instructor->courses()->withCount('enrollments')->get();
            $studentsCount = Student::whereHas('enrollments', function($q) use ($instructor) {
                $q->whereIn('course_id', $instructor->courses->pluck('id'));
            })->count();
        }

        return view('instructor::index', compact('courses', 'studentsCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('instructor::create');
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
        return view('instructor::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('instructor::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Show the QR Scanner interface for a specific course/group
     */
    public function scanner(Course $course)
    {
        // Try to find a schedule for today
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

        if (!$user || !$user->student) {
            return response()->json([
                'success' => false,
                'message' => 'كود الطالب غير صحيح أو غير مسجل النظام.'
            ], 404);
        }

        $student = $user->student;

        // Check enrollment
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'student_name' => $student->name,
                'message' => 'الطالب غير مسجل في هذه المجموعة!'
            ], 403);
        }

        // Mark Attendance using the existing service
        $attendanceService = app(AttendanceService::class);

        if ($attendanceService->hasAttendedToday($student->id, $request->schedule_id)) {
            return response()->json([
                'success' => true,
                'student_name' => $student->name,
                'already_marked' => true,
                'message' => 'تم تسجيل حضور الطالب مسبقاً.'
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

        return response()->json([
            'success' => true,
            'student_name' => $student->name,
            'remaining_sessions' => $enrollment->fresh()->remaining_sessions,
            'message' => 'تم تسجيل الحضور بنجاح!'
        ]);
    }

    /**
     * Display billing information for students
     */
    public function billing()
    {
        $instructor = auth()->user()->instructor;
        
        if (!$instructor) {
            $students = Student::with(['user', 'sales'])->take(10)->get();
        } else {
            $students = Student::whereHas('enrollments', function($q) use ($instructor) {
                $q->whereIn('course_id', $instructor->courses->pluck('id'));
            })->with(['user', 'sales'])->get();
        }

        return view('instructor::billing', compact('students'));
    }

    /**
     * Display a list of students enrolled in instructor's courses
     */
    public function students()
    {
        $instructor = auth()->user()->instructor;

        if (!$instructor) {
            $students = Student::with(['user', 'enrollments.course'])->take(20)->get();
        } else {
            $students = Student::whereHas('enrollments', function($q) use ($instructor) {
                $q->whereIn('course_id', $instructor->courses->pluck('id'));
            })->with(['user', 'enrollments.course' => function($q) use ($instructor) {
                $q->where('instructor_id', $instructor->id);
            }])->get();
        }

        return view('instructor::students.index', compact('students'));
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

        $student = Student::findOrFail($request->student_id);

        $sale = Sale::create([
            'tenant_id' => $student->tenant_id,
            'student_id' => $student->id,
            'total_amount' => $request->amount,
            'paid_amount' => $request->amount,
            'status' => 'paid',
            'payment_method' => 'cash',
            'notes' => $request->notes ?? 'تحصيل سريع من واجهة المدرس',
        ]);

        Payment::create([
            'tenant_id' => $student->tenant_id,
            'sale_id' => $sale->id,
            'amount' => $request->amount,
            'payment_method' => 'cash',
            'received_by' => auth()->id(),
            'paid_at' => now(),
        ]);

        return back()->with('success', "تم تسجيل استلام {$request->amount} ج.م من الطالب {$student->name}");
    }
}
