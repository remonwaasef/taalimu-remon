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
        // For now, schedules are not fully implemented in DB, 
        // we'll pass the student and handle empty state or mock data in view.
        return view('campus::schedule', compact('student'));
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
}
