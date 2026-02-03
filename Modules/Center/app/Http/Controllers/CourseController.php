<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\CourseService;
use App\Queries\CourseQuery;
use App\Http\Requests\Center\StoreCourseRequest;
use App\Http\Requests\Center\UpdateCourseRequest;
use App\DTOs\CourseData;
use App\Services\CertificateService;
use App\Models\LessonProgress;
use App\Models\Enrollment;

class CourseController extends Controller
{
    use \App\Traits\HandlesFileUploads;

    protected $courseService;
    protected $courseQuery;
    protected $certificateService;

    public function __construct(CourseService $courseService, CourseQuery $courseQuery, CertificateService $certificateService)
    {
        $this->courseService = $courseService;
        $this->courseQuery = $courseQuery;
        $this->certificateService = $certificateService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Course::class);
        $query = Course::query()->with(['instructor', 'schedules.classroom']);

        $query = $this->courseQuery->apply($query, $request->all());

        $courses = $query->latest()->paginate(10);

        return view('center::courses.index', compact('courses'));
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

        if (!app('tenant')->hasFeature('max_courses')) {
            return redirect()->back()->with('error', 'لقد وصلت للحد الأقصى من الكورسات المسموح به في باقتك.');
        }

        $data = $request->validated();
        
        $data['image'] = $this->handleFileUpload($request, 'image', null, 'courses');

        $this->courseService->createCourse(CourseData::fromArray($data));

        return redirect()->route('center.courses.index')->with('success', 'تم إنشاء الكورس بنجاح');
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, $id)
    {
        $course = Course::with(['instructor', 'enrollments.user.student'])->findOrFail($id);
        $this->authorize('view', $course);
        // Get students NOT enrolled in this course
        $students = \App\Models\Student::whereDoesntHave('user.enrollments', function($q) use ($id) {
            $q->where('course_id', $id);
        })->get();

        $stages = \App\Models\Stage::getCached();
        $auto_enroll = $request->has('enroll');
        
        return view('center::courses.show', compact('course', 'students', 'stages', 'auto_enroll'));
    }

    public function enroll(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        
        $this->authorize('enroll', $course);
        
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $student = \App\Models\Student::where('tenant_id', app('tenant')->id)
            ->findOrFail($request->student_id);

        try {
            $this->courseService->enrollStudent($course, $student);
            return back()->with('success', 'تم تسجيل الطالب في الدورة بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function quickEnroll(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $this->authorize('enroll', $course);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'grade_id' => 'required|exists:grades,id',
            'parent_phone' => 'nullable|string|max:20',
        ]);

        try {
            return \DB::transaction(function() use ($request, $course) {
                // 1. Create Student
                $studentData = \App\DTOs\StudentData::fromArray($request->all());
                $registrationResult = app(\App\Services\StudentService::class)->registerStudent($studentData, auth()->user());
                $student = $registrationResult['student'];

                // 2. Enroll in Course
                $this->courseService->enrollStudent($course, $student);

                return back()->with('success', 'تم إنشاء الطالب وتسجيله في الدورة بنجاح');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $course = Course::with('schedules')->findOrFail($id);
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
        $course = Course::findOrFail($id);
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
            \Log::info('CourseController@update: Update successful for course ID ' . $id);
        } catch (\Throwable $e) {
            \Log::error('CourseController@update: EXCEPTION', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->withErrors(['error' => 'حدث خطأ أثناء التحديث: ' . $e->getMessage()]);
        }

        return redirect()->route('center.courses.index')->with('success', 'تم تحديث الكورس بنجاح');
    }

    public function completeLesson(Course $course, $lessonId)
    {
        try {
            $this->courseService->completeLesson($course, $lessonId, auth()->user());
            return back()->with('success', 'Lesson marked as complete!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $this->authorize('delete', $course);
        
        $this->courseService->deleteCourse($course);
        
        return redirect()->route('center.courses.index')->with('success', 'تم حذف الدورة بنجاح');
    }
}
