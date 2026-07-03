<?php

namespace Modules\Center\Http\Controllers;

use App\DTOs\CourseData;
use App\Http\Requests\Center\StoreCourseRequest;
use App\Http\Requests\Center\UpdateCourseRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Queries\CourseQuery;
use App\Services\CertificateService;
use App\Services\CourseService;
use App\Services\FinanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Center\Http\Controllers\CenterBaseController as Controller;

class CourseController extends Controller
{
    use \App\Traits\HandlesFileUploads;

    protected $courseService;

    protected $courseQuery;

    protected $certificateService;

    protected $financeService;

    public function __construct(CourseService $courseService, CourseQuery $courseQuery, CertificateService $certificateService, FinanceService $financeService)
    {
        parent::__construct();
        $this->courseService = $courseService;
        $this->courseQuery = $courseQuery;
        $this->certificateService = $certificateService;
        $this->financeService = $financeService;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Course::class);
        $query = Course::query()->with(['instructor', 'schedules.classroom']);

        $query = $this->courseQuery->apply($query, $request->all());

        $courses = $query->latest()->paginate(10);

        // Load data needed for the unified Quick Enroll Modal
        $students = \App\Models\Student::select('id', 'name', 'phone')->get();
        $stages = \App\Models\Stage::getCached();

        return view('center::courses.index', compact('courses', 'students', 'stages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Course::class);
        $instructors = Instructor::select('id', 'name', 'email')->get();
        $classrooms = \App\Models\Classroom::select('id', 'name')->get();

        return view('center::courses.create', compact('instructors', 'classrooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $this->authorize('create', Course::class);

        if (! $this->tenant->hasFeature('max_courses')) {
            return redirect()->back()->with('error', __('center::messages.msg_025'));
        }

        $data = $request->validated();

        try {
            $data['image'] = $this->handleFileUpload($request, 'image', null, 'courses');
            $this->courseService->createCourse(CourseData::fromArray($data));
        } catch (\Exception $e) {
            \Log::error('Course creation failed: '.$e->getMessage());

            return redirect()->back()->withInput()->with('error', __('center::messages.registration_failed') ?? 'حدث خطأ: '.$e->getMessage());
        }

        // Smart Onboarding Routing: If this is the first course, guide them to register a student
        $courseCount = Course::where('tenant_id', $this->tenant->id)->count();
        if ($courseCount === 1) {
            return redirect()->route('center.students.create')->with('success', __('center::messages.first_course_onboarding'));
        }

        return redirect()->route('center.courses.index')->with('success', __('center::messages.msg_026'));
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, $id)
    {
        $course = Course::where('tenant_id', app('tenant')->id)->with(['instructor', 'enrollments.user.student'])->findOrFail($id);
        $this->authorize('view', $course);
        // Get students NOT enrolled in this course
        $students = \App\Models\Student::whereDoesntHave('user.enrollments', function ($q) use ($id) {
            $q->where('course_id', $id);
        })->get();

        $stages = \App\Models\Stage::getCached();
        $auto_enroll = $request->has('enroll');

        return view('center::courses.show', compact('course', 'students', 'stages', 'auto_enroll'));
    }

    public function enroll(Request $request, $id)
    {
        $course = Course::where('tenant_id', app('tenant')->id)->findOrFail($id);

        $this->authorize('enroll', $course);

        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $student = \App\Models\Student::where('tenant_id', $this->tenant->id)
            ->findOrFail($request->student_id);

        $lockKey = "enrollment_lock_{$student->user_id}_{$course->id}";
        $lock = \Illuminate\Support\Facades\Cache::lock($lockKey, 10);

        if (! $lock->get()) {
            return back()->with('error', __('center::messages.registration_in_progress') ?? 'جاري معالجة طلبك...');
        }

        try {
            // Prevent duplicate enrollment
            $alreadyEnrolled = Enrollment::where('user_id', $student->user_id)
                ->where('course_id', $course->id)
                ->exists();

            if ($alreadyEnrolled) {
                return back()->with('error', __('center::messages.student_already_enrolled'));
            }

            // إنشاء فاتورة غير مدفوعة تلقائياً عند التسجيل
            $this->financeService->createSale([
                'student_id' => $student->id,
                'items' => [['id' => $course->id, 'price' => $course->price]],
                'payment_method' => 'cash',
                'paid_amount' => 0, // فاتورة غير مدفوعة
            ]);

            return back()->with('success', __('center::messages.msg_027'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        } finally {
            $lock->release();
        }
    }

    public function quickEnroll(Request $request, $id)
    {
        $course = Course::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('enroll', $course);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'grade_id' => 'required|exists:grades,id',
            'parent_phone' => 'nullable|string|max:20',
        ]);

        try {
            // 1. Create Student
            $studentData = \App\DTOs\StudentData::fromArray($request->all());
            $registrationResult = app(\App\Services\StudentService::class)->registerStudent($studentData, auth()->user());
            $student = $registrationResult['student'];

            // 2. إنشاء فاتورة غير مدفوعة تلقائياً (بدلاً من التسجيل المباشر)
            $this->financeService->createSale([
                'student_id' => $student->id,
                'items' => [['id' => $course->id, 'price' => $course->price]],
                'payment_method' => 'cash',
                'paid_amount' => 0,
            ]);

            return back()->with('success', __('center::messages.msg_028'));
        } catch (\Exception $e) {
            \Log::error('Quick enroll failed: '.$e->getMessage());

            return back()->with('error', __('center::messages.registration_failed') ?? 'حدث خطأ أثناء التسجيل السريع.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $course = Course::where('tenant_id', app('tenant')->id)->with('schedules')->findOrFail($id);
        $this->authorize('update', $course);
        $instructors = Instructor::select('id', 'name', 'email')->get();
        $classrooms = \App\Models\Classroom::select('id', 'name')->get();

        return view('center::courses.edit', compact('course', 'instructors', 'classrooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, $id): RedirectResponse
    {
        $course = Course::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('update', $course);

        $data = $request->validated();

        \Log::info('CourseController@update: Validated data', ['data' => $data]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->handleFileUpload($request, 'image', $course->image, 'courses');
        } else {
            $data['image'] = $course->image;
        }

        try {
            $dto = CourseData::fromArray($data);
            \Log::info('CourseController@update: DTO created', ['dto_array' => $dto->toArray()]);
            $this->courseService->updateCourse($course, $dto);
            \Log::info('CourseController@update: Update successful for course ID '.$id);
        } catch (\Throwable $e) {
            \Log::error('CourseController@update: EXCEPTION', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('center.courses.index')->with('success', __('center::messages.msg_029'));
    }

    public function completeLesson(Course $course, $lessonId)
    {
        try {
            $this->courseService->completeLesson($course, $lessonId, auth()->user());

            return back()->with('success', __('center::messages.msg_030'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $course = Course::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('delete', $course);

        $this->courseService->deleteCourse($course);

        return redirect()->route('center.courses.index')->with('success', __('center::messages.msg_031'));
    }
}
