<?php

namespace Modules\Instructor\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Student;
use App\Models\User;
use Modules\Center\Models\Attendance;

class StudentPortalController extends Controller
{
    /**
     * Show the student portal
     */
    public function index($identifier)
    {
        abort_unless(request()->hasValidSignature(), 403);

        $user = User::where('qr_identifier', $identifier)->firstOrFail();
        $student = $user->student;

        if (! $student) {
            abort(404, 'البيانات غير مكتملة لهذا الطالب.');
        }

        // Get latest 10 attendances
        $attendances = Attendance::where('student_id', $student->id)
            ->with(['course', 'schedule'])
            ->latest('session_date')
            ->take(10)
            ->get();

        // Get latest 10 payments
        $sales = Sale::where('student_id', $student->id)
            ->latest()
            ->take(10)
            ->get();

        // Get latest enrollment for QR details
        $enrollment = $user->enrollments()->with('course.instructor')->latest()->first();
        $course = $enrollment ? $enrollment->course : null;

        // Get upcoming online classes
        $enrolledCourseIds = $user->enrollments()->pluck('course_id');
        $onlineClasses = \App\Models\OnlineClass::whereIn('course_id', $enrolledCourseIds)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->where('start_time', '>=', now()->subHours(2)) // Hide old classes
            ->orderBy('start_time', 'asc')
            ->get();

        return inertia('StudentPortal', [
            'student' => $student,
            'user' => $user,
            'attendances' => $attendances,
            'sales' => $sales,
            'course' => $course,
            'onlineClasses' => $onlineClasses,
            'locale' => app()->getLocale(),
            'currency' => app('tenant')->settings['currency'] ?? 'EGP',
        ]);
    }
}
