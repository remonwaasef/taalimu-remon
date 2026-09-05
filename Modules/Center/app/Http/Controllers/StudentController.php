<?php

namespace Modules\Center\Http\Controllers;

use App\DTOs\StudentData;
use App\Http\Requests\Center\StoreStudentRequest;
use App\Http\Requests\Center\UpdateStudentRequest;
use App\Models\Student;
use App\Models\Course;
use App\Models\Stage;
use App\Queries\StudentQuery;
use App\Services\StudentService;
use App\Traits\HandlesFileUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Center\Http\Controllers\CenterBaseController as Controller;

class StudentController extends Controller
{
    use HandlesFileUploads;

    protected $studentService;

    protected $studentQuery;

    public function __construct(StudentService $studentService, StudentQuery $studentQuery)
    {
        parent::__construct();
        $this->studentService = $studentService;
        $this->studentQuery = $studentQuery;
    }

    /**
     * Find a student scoped to the current tenant, or fail.
     */
    private function findStudentOrFail($id, array $with = [], bool $withTrashed = false)
    {
        $query = Student::forTenant($this->tenant->id);
        if ($withTrashed) {
            $query->withTrashed();
        }
        if (! empty($with)) {
            $query->with($with);
        }

        return $query->findOrFail($id);
    }

    /**
     * Lightweight JSON search used by async student pickers (TomSelect).
     * Replaces loading the entire student list into enrollment modals,
     * which did not scale beyond a few hundred students.
     */
    public function search(Request $request)
    {
        $this->authorize('viewAny', Student::class);

        $validated = $request->validate([
            'q' => 'nullable|string|max:100',
            'exclude_course_id' => 'nullable|integer',
        ]);

        $students = app(\App\Services\SearchService::class)->searchStudents(
            tenant: $this->tenant,
            term: (string) ($validated['q'] ?? ''),
            limit: 20,
            excludeCourseId: isset($validated['exclude_course_id']) ? (int) $validated['exclude_course_id'] : null
        );

        return response()->json(
            $students->map(fn ($s) => [
                'id' => $s->id,
                'text' => $s->name.($s->phone ? ' ('.$s->phone.')' : ''),
            ])
        );
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Student::class);

        $query = Student::query();
        $query = $this->studentQuery->apply($query, $request->all());

        $students = $query->with(['grade.stage', 'enrollments.course'])
            ->withSum('sales', 'paid_amount')
            ->latest()
            ->paginate(10);

        // Calculate financial data for each student for filtering
        $students->getCollection()->transform(function ($student) {
            $totalDue = $student->enrollments->sum(function ($enrollment) {
                return $enrollment->course->price ?? 0;
            });
            $totalPaid = $student->sales_sum_paid_amount ?? 0;
            $student->total_balance = $totalDue - $totalPaid;
            $student->financial_status = $student->total_balance > 0 ? 'debt' : 'paid';

            return $student;
        });

        $stages = Stage::getCached();
        $courses = Course::orderBy('title')->get();

        return view('center::students.index', compact('students', 'stages', 'courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Student::class);

        $stages = Stage::getCached();
        $courses = Course::orderBy('title')->get();

        return view('center::students.create', compact('stages', 'courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $this->authorize('create', Student::class);

        if (! $this->tenant->hasFeature('max_students')) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => __('center::students.max_limit_reached')], 403);
            }

            return redirect()->back()->with('error', __('center::students.max_limit_reached'));
        }

        $data = $request->validated();

        try {
            $data['profile_photo'] = $this->handleFileUpload(
                $request,
                'profile_photo',
                null,
                'students/photos'
            );

            $result = $this->studentService->registerStudent(StudentData::fromArray($data), auth()->user());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('center::messages.msg_081'),
                    'student' => [
                        'id' => $result['student']->id,
                        'name' => $result['student']->name,
                        'phone' => $result['student']->phone,
                    ],
                ]);
            }

            // Store info in session to display to the user
            session()->flash('generated_password', $result['generated_password']);
            session()->flash('student_name', $result['student']->name);
            session()->flash('student_phone', $result['student']->phone);
            session()->flash('student_email', $result['student']->email);

            // Smart Onboarding Routing: If this is the first student, guide them back to the dashboard
            $studentCount = Student::forTenant($this->tenant->id)->count();
            if ($studentCount === 1) {
                return redirect()->route('center.dashboard')->with('success', __('center::messages.first_student_onboarding'));
            }

            return redirect()->route('center.students.index', ['tenant' => $this->tenant->domain])->with('success', __('center::messages.msg_081'));
        } catch (\Exception $e) {
            \Log::error('Student registration failed: '.$e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => __('center::messages.registration_failed')], 500);
            }

            return redirect()->back()->withInput()->with('error', __('center::messages.registration_failed'));
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $student = $this->findStudentOrFail($id, ['grade.stage', 'user', 'tenant']);

        $this->authorize('view', $student);

        $data = $this->studentService->getProfileData($student);

        return view('center::students.show', array_merge(['student' => $student], $data));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $student = $this->findStudentOrFail($id, ['enrollments.course.instructor', 'grade.stage']);
        $this->authorize('update', $student);

        $stages = Stage::getCached();
        $courses = Course::forTenant($this->tenant->id)->where('status', 'active')->get();

        return view('center::students.edit', compact('student', 'stages', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, $id): RedirectResponse
    {
        $student = $this->findStudentOrFail($id);
        $this->authorize('update', $student);

        // Pass user_id to exclude to the request validator
        // $request->merge(['user_id_to_exclude' => $student->user_id]);

        $data = $request->validated();

        // Handle Profile Photo Upload using trait
        $data['profile_photo'] = $this->handleFileUpload(
            $request,
            'profile_photo',
            $student->profile_photo,
            'students/photos'
        );

        $this->studentService->updateStudent($student, StudentData::fromArray($data), auth()->user());

        return redirect()->route('center.students.index', ['tenant' => $this->tenant->domain])->with('success', __('center::messages.msg_082'));
    }

    public function resetPassword($id): RedirectResponse
    {
        $student = $this->findStudentOrFail($id);
        $this->authorize('update', $student);

        $newPassword = $this->studentService->resetPassword($student->user);

        return redirect()->back()->with('success', __('center::messages.msg_083'))
            ->with('generated_password', $newPassword)
            ->with('student_name', $student->name);
    }

    /**
     * Toggle student status (Active/Frozen).
     */
    public function toggleStatus($id)
    {
        $student = $this->findStudentOrFail($id);
        $this->authorize('update', $student);

        $student->update(['status' => $student->status === 'active' ? 'frozen' : 'active']);

        return back()->with('success', __('center::messages.msg_082'));
    }

    /**
     * Send debt reminder via WhatsApp.
     */
    public function remindDebt($id, \App\Services\WhatsAppService $whatsappService)
    {
        $student = $this->findStudentOrFail($id);
        $this->authorize('update', $student);

        $totalDebt = app(\App\Services\Student\StudentLedgerService::class)->totalDebt($student);

        if ($totalDebt <= 0) {
            return redirect()->back()->with('info', __('center::messages.no_outstanding_debts'));
        }

        \App\Jobs\SendDebtReminderJob::dispatch($this->tenant, $student, $totalDebt);

        return redirect()->back()->with('success', __('center::messages.debt_reminder_sent'));
    }

    /**
     * View student financial statement (Ledger).
     */
    public function statement($id, \App\Services\Student\StudentLedgerService $ledgerService)
    {
        $student = $this->findStudentOrFail($id);
        $this->authorize('view', $student);

        ['ledger' => $ledger, 'totalDebt' => $totalDebt] = $ledgerService->buildLedger($student);
        $tenant = $this->tenant;

        return view('center::students.statement', compact('student', 'ledger', 'tenant', 'totalDebt'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $student = $this->findStudentOrFail($id);
        $this->authorize('delete', $student);

        // Delete profile photo
        $this->deleteFile($student->profile_photo, 'public');

        $this->studentService->deleteStudent($student, auth()->user());

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('center::messages.msg_084'),
                'restore_url' => route('center.students.restore', ['tenant' => $this->tenant->domain, 'id' => $id]),
            ]);
        }

        return redirect()->route('center.students.index', ['tenant' => $this->tenant->domain])->with('success', __('center::messages.msg_084'));
    }

    public function restore($id)
    {
        $student = $this->findStudentOrFail($id, [], true);
        $this->authorize('update', $student);

        $student->restore();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => __('center::messages.msg_082')]);
        }

        return redirect()->back()->with('success', __('center::messages.msg_082'));
    }

    public function export()
    {
        $this->authorize('viewAny', Student::class);

        return response()->streamDownload(function () {
            $students = $this->studentService->getExportData();
            $csvHeader = ['ID', 'Name', 'Email', 'Phone', 'Grade Level', 'School', 'Section', 'Status'];
            $handle = fopen('php://output', 'w');

            // Add BOM for Excel compatibility with Arabic
            fwrite($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, $csvHeader);
            foreach ($students as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 'students_export.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Download dynamic import template with real grades.
     */
    public function downloadTemplate(\App\Services\Student\StudentImportService $importService)
    {
        $this->authorize('create', Student::class);

        $html = $importService->buildTemplateHtml($this->tenant->id);

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="students-template.xls"');
    }

    /**
     * Show the import form.
     */
    public function importForm()
    {
        $this->authorize('create', Student::class);

        return view('center::students.import');
    }

    /**
     * Handle the file import (CSV or Paste from Excel).
     */
    public function import(Request $request)
    {
        // Authorization: ensure user can create students
        $this->authorize('create', Student::class);

        if (! $this->tenant->hasFeature('max_students')) {
            return redirect()->back()->with('error', __('center::messages.msg_085'));
        }

        $method = $request->input('import_method', 'file');

        if ($method === 'paste') {
            // Paste from Excel method
            $request->validate([
                'paste_data' => 'required|string|min:5',
            ]);

            $path = app(\App\Services\Student\StudentImportService::class)
                ->convertPasteToCsv($request->input('paste_data'));

            if ($path === null) {
                return redirect()->back()->withErrors(['paste_data' => 'لا توجد بيانات صالحة للاستيراد.']);
            }
        } else {
            // File upload method (CSV only)
            $request->validate([
                'file' => 'required|file|mimes:csv,txt|max:5120',
            ]);

            $path = $request->file('file')->store('temp/imports');
        }

        try {
            \App\Jobs\ImportStudentsJob::dispatch($path, $this->tenant->id, auth()->id());

            return redirect()->route('center.students.index', ['tenant' => $this->tenant->domain])
                ->with('success', __('center::messages.msg_086').' - جاري المعالجة في الخلفية');
        } catch (\Exception $e) {
            \Log::error('Student import failed: '.$e->getMessage());

            return redirect()->back()->with('error', __('center::messages.error_unexpected'));
        }
    }

    /**
     * Check if a student phone number exists.
     */
    public function checkPhone(Request $request)
    {
        $phone = $request->query('phone');

        if (empty($phone)) {
            return response()->json(['status' => 'available']);
        }

        $student = Student::where('tenant_id', $this->tenant->id)
            ->where('phone', $phone)
            ->first();

        if ($student) {
            return response()->json([
                'status' => 'exists',
                'name'   => $student->name,
            ]);
        }

        return response()->json(['status' => 'available']);
    }

    /**
     * Look up a guardian by phone number.
     */
    public function lookupGuardian(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $guardian = \App\Models\Guardian::where('tenant_id', $this->tenant->id)
            ->where('phone', $request->phone)
            ->first();

        if (! $guardian) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'guardian' => $guardian,
        ]);
    }

    /**
     * Send an email to the student
     */
    public function sendEmail(Request $request, $id)
    {
        $student = $this->findStudentOrFail($id);
        $this->authorize('update', $student);

        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            $sent = $this->studentService->sendCustomEmail(
                $student,
                $request->subject,
                $request->message,
                $this->tenant->name
            );

            if (! $sent) {
                return redirect()->back()->with('error', __('center::messages.no_student_email'));
            }

            return redirect()->back()->with('success', __('center::messages.email_sent_success'));
        } catch (\Exception $e) {
            \Log::error("Failed to send email to student {$student->id}: ".$e->getMessage());

            return redirect()->back()->with('error', __('center::messages.email_send_error'));
        }
    }

    public function idCard($id)
    {
        $student = $this->findStudentOrFail($id);
        $this->authorize('view', $student);

        return view('center::students.id_card', compact('student'));
    }

    /**
     * Bulk change status for selected students.
     */
    public function bulkStatus(Request $request)
    {
        $this->authorize('update', Student::class);

        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'status' => 'required|in:active,frozen',
        ]);

        $studentIds = $request->student_ids;
        $status = $request->status;

        Student::where('tenant_id', $this->tenant->id)
            ->whereIn('id', $studentIds)
            ->update(['status' => $status]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => count($studentIds).' طالب تم تحديث حالته بنجاح',
            ]);
        }

        return redirect()->back()->with('success', count($studentIds).' طالب تم تحديث حالته بنجاح');
    }

    /**
     * Bulk soft delete selected students.
     */
    public function bulkDelete(Request $request)
    {
        $this->authorize('delete', Student::class);

        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
        ]);

        $studentIds = $request->student_ids;

        Student::where('tenant_id', $this->tenant->id)
            ->whereIn('id', $studentIds)
            ->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => count($studentIds).' طالب تم حذفه بنجاح',
            ]);
        }

        return redirect()->back()->with('success', count($studentIds).' طالب تم حذفه بنجاح');
    }

    /**
     * Export selected students to CSV.
     */
    public function bulkExport(Request $request)
    {
        $this->authorize('viewAny', Student::class);

        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
        ]);

        $studentIds = $request->student_ids;

        return response()->streamDownload(function () use ($studentIds) {
            $students = Student::where('tenant_id', $this->tenant->id)
                ->whereIn('id', $studentIds)
                ->with('grade')
                ->get();

            $csvHeader = ['ID', 'الاسم', 'البريد الإلكتروني', 'الهاتف', 'المستوى الدراسي', 'المدرسة', 'الشعبة', 'الحالة'];
            $handle = fopen('php://output', 'w');

            fwrite($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, $csvHeader);
            foreach ($students as $student) {
                fputcsv($handle, [
                    $student->id,
                    $student->name,
                    $student->email ?? '',
                    $student->phone ?? '',
                    $student->grade->name ?? '',
                    $student->school_name ?? '',
                    $student->section_type ?? '',
                    $student->status,
                ]);
            }

            fclose($handle);
        }, 'students_selected_'.now()->format('Y-m-d').'.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
