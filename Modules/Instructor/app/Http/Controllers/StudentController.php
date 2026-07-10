<?php

namespace Modules\Instructor\Http\Controllers;

use App\DTOs\StudentData;
use App\Helpers\PhoneHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Instructor\Http\Controllers\Traits\ResolvesInstructor;
use Modules\Instructor\Http\Requests\StoreStudentRequest;

class StudentController extends Controller
{
    use ResolvesInstructor;

    public function index()
    {
        $instructor = $this->instructor;
        $courseIds = $this->getInstructorCourseIds();

        if (! $instructor) {
            $students = Student::with(['user', 'enrollments.course', 'sales'])->take(20)->get();
        } else {
            $students = Student::whereHas('enrollments', function ($q) use ($courseIds) {
                $q->whereIn('course_id', $courseIds);
            })->with(['user', 'enrollments.course' => function ($q) use ($courseIds) {
                $q->whereIn('course_id', $courseIds);
            }, 'sales'])->limit(200)->get();
        }

        $studentIds = $students->pluck('id')->filter()->toArray();
        $attendanceCounts = [];
        if (! empty($studentIds)) {
            $attendanceCounts = \Modules\Center\Models\Attendance::whereIn('student_id', $studentIds)
                ->where('status', 'present')
                ->groupBy('student_id')
                ->selectRaw('student_id, count(*) as total')
                ->pluck('total', 'student_id')
                ->toArray();
        }

        $uniqueCourses = collect();
        foreach ($students as $student) {
            foreach ($student->enrollments as $enrollment) {
                if ($enrollment->course) {
                    $uniqueCourses->put($enrollment->course->id, $enrollment->course->title);
                }
            }
        }

        $totalRevenue = ! empty($studentIds)
            ? Sale::whereIn('student_id', $studentIds)->sum('paid_amount')
            : 0;

        return view('instructor::students.index', compact('students', 'attendanceCounts', 'uniqueCourses', 'totalRevenue'));
    }

    public function create()
    {
        $instructor = $this->instructor;
        $courses = $instructor
            ? Course::where('instructor_id', $instructor->id)->select('id', 'title', 'price')->get()
            : Course::select('id', 'title', 'price')->get();

        if ($courses->isEmpty()) {
            return redirect()->route('instructor.groups.create')
                ->with('info', __('instructor::messages.create_group_first'));
        }

        return view('instructor::students.create', compact('courses'));
    }

    public function store(StoreStudentRequest $request, StudentService $studentService)
    {
        $instructor = $this->instructor;
        if (! $instructor) {
            return back()->with('error', __('instructor::messages.not_instructor_error'));
        }

        $validated = $request->validated();

        $courseIds = Course::whereIn('id', $validated['course_ids'] ?? [])
            ->where('instructor_id', $instructor->id)
            ->pluck('id')
            ->all();

        if (empty($courseIds)) {
            return back()->withInput()->with('error', __('instructor::messages.unauthorized'));
        }

        try {
            if ($request->filled('student_id')) {
                $student = Student::findOrFail($validated['student_id']);
                $this->authorizeInstructor($student);
                $studentService->enrollInCourses($student, $courseIds);
            } else {
                $result = $studentService->registerStudent(
                    StudentData::fromArray([
                        'name' => $validated['name'],
                        'phone' => $validated['phone'],
                        'email' => $validated['email'] ?? null,
                        'parent_phone' => $validated['parent_phone'] ?? null,
                        'parent_email' => $validated['parent_email'] ?? null,
                        'course_ids' => $courseIds,
                    ]),
                    auth()->user()
                );
                $student = $result['student'];
            }

            return redirect()->route('instructor.students.list')
                ->with('success', __('instructor::messages.student_added', ['name' => $student->name]));
        } catch (\Exception $e) {
            Log::error('Manual student registration failed: '.$e->getMessage());

            return back()->withInput()->with('error', __('instructor::messages.error_adding_student'));
        }
    }

    public function show(Student $student)
    {
        $courseIds = $this->getInstructorCourseIds();

        if ($this->instructor && ! $this->isStudentEnrolledInCourse($student, $courseIds)) {
            abort(403);
        }

        $student->load(['user', 'enrollments.course', 'sales' => function ($q) {
            $q->latest();
        }]);

        $attendances = \Modules\Center\Models\Attendance::where('student_id', $student->id)
            ->when($this->instructor, function ($q) use ($courseIds) {
                $q->whereIn('course_id', $courseIds);
            })
            ->with(['course', 'schedule'])
            ->latest()
            ->get();

        return view('instructor::students.show', compact('student', 'attendances'));
    }

    public function destroy(Student $student)
    {
        $courseIds = $this->getInstructorCourseIds();

        if ($this->instructor && ! $this->isStudentEnrolledInCourse($student, $courseIds)) {
            abort(403, __('instructor::messages.unauthorized'));
        }

        try {
            \DB::beginTransaction();

            $studentName = $student->name;
            $userId = $student->user_id;

            Enrollment::where('user_id', $userId)->delete();
            \Modules\Center\Models\Attendance::where('student_id', $student->id)->delete();
            Payment::whereHas('sale', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })->delete();
            Sale::where('student_id', $student->id)->delete();
            $student->delete();

            $user = \App\Models\User::find($userId);
            if ($user && $user->roles()->count() <= 1) {
                $user->delete();
            }

            \DB::commit();

            return redirect()->route('instructor.students.list')->with('success', __('instructor::messages.student_deleted', ['name' => $studentName]));
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Student deletion failed: '.$e->getMessage());

            return back()->with('error', __('instructor::messages.error_deleting_student'));
        }
    }

    public function toggleStatus(Student $student)
    {
        $this->authorizeInstructor($student);
        $student->update(['status' => $student->status === 'active' ? 'frozen' : 'active']);

        return back()->with('success', __('instructor::messages.updated'));
    }

    public function updateNotes(Request $request, Student $student)
    {
        $this->authorizeInstructor($student);
        $student->update(['notes' => $request->notes]);

        return back()->with('success', __('instructor::messages.saved'));
    }

    public function transfer(Request $request, Student $student)
    {
        $request->validate([
            'from_course_id' => 'required|exists:courses,id',
            'to_course_id' => 'required|exists:courses,id',
        ]);

        $this->authorizeInstructor($student);
        Enrollment::where('user_id', $student->user_id)
            ->where('course_id', $request->from_course_id)
            ->update(['course_id' => $request->to_course_id]);

        return back()->with('success', __('instructor::messages.updated'));
    }

    public function export()
    {
        $courseIds = $this->getInstructorCourseIds();

        if (! $this->instructor) {
            $students = Student::with(['enrollments.course'])->get();
        } else {
            $students = Student::whereHas('enrollments', function ($q) use ($courseIds) {
                $q->whereIn('course_id', $courseIds);
            })->with(['enrollments.course'])->get();
        }

        $filename = 'students_export_'.date('Y-m-d').'.csv';
        $headers = [
            'Content-type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = [
            __('instructor::messages.csv_name'),
            __('instructor::messages.csv_phone'),
            __('instructor::messages.csv_parent_phone'),
            __('instructor::messages.csv_groups'),
            __('instructor::messages.csv_registration_date'),
            __('instructor::messages.csv_status'),
        ];

        $callback = function () use ($students, $columns) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($students as $student) {
                fputcsv($file, [
                    $student->name,
                    $student->phone,
                    $student->parent_phone,
                    $student->enrollments->pluck('course.title')->implode(', '),
                    $student->created_at->format('Y-m-d'),
                    $student->status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
            'course_id' => 'required|exists:courses,id',
        ]);

        $instructor = $this->instructor;
        $course = Course::findOrFail($request->course_id);

        if ($course->instructor_id !== $instructor->id) {
            return back()->with('error', __('instructor::messages.unauthorized'));
        }

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        fgetcsv($handle);

        $imported = 0;
        $errors = 0;

        while (($data = fgetcsv($handle)) !== false) {
            try {
                $name = $data[0] ?? null;
                $phone = $data[1] ?? null;
                $parent_phone = $data[2] ?? null;

                if (! $name || ! $phone) {
                    continue;
                }

                $student = Student::firstOrCreate(
                    ['phone' => $phone, 'tenant_id' => $instructor->tenant_id],
                    ['name' => $name, 'parent_phone' => $parent_phone]
                );

                Enrollment::firstOrCreate([
                    'user_id' => $student->user_id ?: $this->getOrCreateUserForStudent($student),
                    'course_id' => $course->id,
                    'tenant_id' => $instructor->tenant_id,
                ]);

                $imported++;
            } catch (\Exception $e) {
                $errors++;
            }
        }
        fclose($handle);

        return back()->with('success', __('instructor::messages.import_success', ['count' => $imported]).($errors ? ' '.__('instructor::messages.import_errors', ['count' => $errors]) : ''));
    }

    public function sendEmail(Request $request, Student $student, StudentService $studentService)
    {
        $courseIds = $this->getInstructorCourseIds();

        if ($this->instructor && ! $this->isStudentEnrolledInCourse($student, $courseIds)) {
            abort(403);
        }

        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            $sent = $studentService->sendCustomEmail(
                $student,
                $request->subject,
                $request->message,
                $this->instructor ? $this->instructor->name : $this->tenant->name
            );

            if (! $sent) {
                return back()->with('error', 'هذا الطالب لا يمتلك بريداً إلكترونياً مسجلاً.');
            }

            return back()->with('success', 'تم إرسال البريد الإلكتروني للطالب بنجاح.');
        } catch (\Exception $e) {
            Log::error("Failed to send email to student {$student->id}: ".$e->getMessage());

            return back()->with('error', 'حدث خطأ أثناء الإرسال.');
        }
    }

    public function checkPhone(Request $request)
    {
        $phone = PhoneHelper::strip((string) $request->get('phone'));
        if (! PhoneHelper::isValid($phone)) {
            return response()->json(['status' => 'invalid']);
        }

        $userQuery = \App\Models\User::query()->where('phone', $phone);

        if (app()->bound('tenant')) {
            $userQuery->where('tenant_id', app('tenant')->id);
        }

        return response()->json(['status' => $userQuery->exists() ? 'exists' : 'available']);
    }

    public function updatePayment(Request $request, Student $student)
    {
        $request->validate([
            'monthly_fee' => 'nullable|numeric|min:0',
            'payment_due_day' => 'nullable|integer|min:1|max:28',
            'parent_email' => 'nullable|email|max:255',
        ]);

        $student->update([
            'monthly_fee' => $request->monthly_fee ?: null,
            'payment_due_day' => $request->payment_due_day ?: null,
            'parent_email' => $request->parent_email ?: null,
        ]);

        return back()->with('success', __('instructor::reminders.saved'));
    }

    private function getInstructorCourseIds(): \Illuminate\Support\Collection
    {
        return $this->instructor
            ? Course::where('instructor_id', $this->instructor->id)->pluck('id')
            : collect();
    }

    private function isStudentEnrolledInCourse(Student $student, $courseIds): bool
    {
        return Enrollment::where('user_id', $student->user_id)
            ->whereIn('course_id', $courseIds)
            ->exists();
    }

    private function getOrCreateUserForStudent($student)
    {
        if ($student->user_id) {
            return $student->user_id;
        }

        $user = \App\Models\User::where('phone', $student->phone)->first();
        if (! $user) {
            $user = \App\Models\User::create([
                'name' => $student->name,
                'phone' => $student->phone,
                'email' => $student->phone.'@edu.com',
                'password' => bcrypt(\Illuminate\Support\Str::random(12)),
                'role' => 'student',
                'tenant_id' => $student->tenant_id,
            ]);
        }

        $student->update(['user_id' => $user->id]);

        return $user->id;
    }
}
