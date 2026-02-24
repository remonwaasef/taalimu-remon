<?php

namespace Modules\Campus\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CertificateService;
use App\Models\Certificate;

class CampusController extends Controller
{
    protected $studentProgressService;

    public function __construct(CertificateService $certificateService, \App\Services\StudentProgressService $studentProgressService)
    {
        $this->studentProgressService = $studentProgressService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $student = $user->student;
        $tenantId = app('tenant')->id;
        
        $enrollments = $student->enrollments()
            ->with(['course.sections.lessons'])
            ->latest('updated_at')
            ->get();

        // Manual Eager Load Certificates to avoid HasOne joining issue
        $certificates = $student->certificates()
            ->whereIn('course_id', $enrollments->pluck('course_id'))
            ->get()
            ->keyBy('course_id');

        foreach ($enrollments as $enrollment) {
            $enrollment->setRelation('certificate', $certificates->get($enrollment->course_id));
        }

        // 1. Gamification Stats
        $points = $user->points;
        $rank = $this->studentProgressService->getStudentRank($user);

        // 2. Identify "Continue Learning" Course & Lesson
        $lastEnrollment = $enrollments->first();
        $nextLesson = $lastEnrollment ? $this->studentProgressService->getNextLesson($lastEnrollment) : null;

        return view('campus::index', compact('student', 'enrollments', 'points', 'rank', 'lastEnrollment', 'nextLesson'));
    }

    public function courses()
    {
        $student = auth()->user()->student;
        
        // Get courses the student is already enrolled in
        $enrolledCourseIds = $student->enrollments()->pluck('course_id');
        
        // Get active courses that the student is NOT enrolled in
        $courses = \App\Models\Course::where('status', 'active')
            ->whereNotIn('id', $enrolledCourseIds)
            ->latest()
            ->get();
            
        return view('campus::courses', compact('student', 'courses'));
    }

    public function schedule()
    {
        $student = auth()->user()->student;
        
        // 1. Get Enrolled Course IDs
        $enrolledCourseIds = $student->enrollments()->pluck('course_id');

        // 2. Fetch Schedules for these courses
        $schedules = \App\Models\Schedule::with(['course', 'classroom' => function($q) {
                $q->withoutGlobalScopes(); 
            }, 'instructor'])
            ->whereIn('course_id', $enrolledCourseIds)
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        // 3. Define Week Days (Arabic) - Matches Center Module Convention (0=Sunday)
        $days = [
            0 => 'الأحد',
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
        ];

        return view('campus::schedule', compact('student', 'schedules', 'days'));
    }

    public function finances()
    {
        $student = auth()->user()->student;
        $sales = \App\Models\Sale::where('student_id', $student->id)->latest()->get();
        $totalDebt = $sales->sum('total_amount') - $sales->sum('paid_amount');
        
        return view('campus::finances', compact('student', 'sales', 'totalDebt'));
    }

    public function attendance()
    {
        $student = auth()->user()->student;
        $attendances = \Modules\Center\Models\Attendance::where('student_id', $student->id)
            ->with('course')
            ->latest()
            ->paginate(10);
            
        return view('campus::attendance', compact('student', 'attendances'));
    }

    public function profile()
    {
        $student = auth()->user()->student;
        return view('campus::profile', compact('student'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('campus::create');
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
        return view('campus::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('campus::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    public function downloadCertificate(Certificate $certificate, CertificateService $certificateService)
    {
        // Security check: must belong to the logged in student
        if ($certificate->student_id !== auth()->user()->student->id) {
            abort(403);
        }

        return $certificateService->generatePdf($certificate)
            ->download('certificate-' . $certificate->uuid . '.pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}

    /**
     * TEMPORARY DIAGNOSTIC METHOD
     */
    public function debugStudentData()
    {
        $user = auth()->user();
        $student = $user->student;
        $tenant = app('tenant');
        
        $data = [
            'auth_user' => [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'tenant_id' => $user->tenant_id,
            ],
            'student_profile' => $student ? [
                'id' => $student->id,
                'user_id' => $student->user_id,
                'name' => $student->name,
                'tenant_id' => $student->tenant_id,
            ] : null,
            'tenant' => [
                'id' => $tenant->id,
                'domain' => $tenant->domain,
            ],
            'feature_student_portal' => $tenant->hasFeature('student_portal') ? 'ENABLED' : 'DISABLED',
            'enrollments' => \App\Models\Enrollment::where('user_id', $user->id)
                ->with('course:id,title')
                ->get()
                ->map(fn($e) => [
                    'id' => $e->id,
                    'course_id' => $e->course_id,
                    'course_title' => $e->course->title ?? 'N/A',
                    'status' => $e->status,
                    'user_id' => $e->user_id,
                ]),
            'all_enrollments_for_this_tenant_count' => \App\Models\Enrollment::where('tenant_id', $tenant->id)->count(),
            'total_active_courses_count' => \App\Models\Course::where('tenant_id', $tenant->id)->where('status', 'active')->count(),
        ];

        return response()->json($data);
    }
}
