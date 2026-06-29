<?php

namespace Modules\Center\Http\Controllers;

use Modules\Center\Http\Controllers\CenterBaseController as Controller;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Payment;
use App\Models\Refund;
use Illuminate\Http\Response;
use App\Services\StudentService;
use App\Queries\StudentQuery;
use App\Http\Requests\Center\StoreStudentRequest;
use App\Http\Requests\Center\UpdateStudentRequest;
use App\DTOs\StudentData;
use App\Traits\HandlesFileUploads;
use Illuminate\Support\Facades\Cache;

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
        if (!empty($with)) {
            $query->with($with);
        }
        return $query->findOrFail($id);
    }
    public function index(Request $request)
    {
        $this->authorize('viewAny', Student::class);
        
        $query = Student::query();
        $query = $this->studentQuery->apply($query, $request->all());

        $students = $query->with(['grade.stage', 'enrollments.course', 'sales'])->latest()->paginate(10);
        
        // Calculate financial data for each student for filtering
        $students->getCollection()->transform(function($student) {
            $totalDue = $student->enrollments->sum(function($enrollment) {
                return $enrollment->course->price ?? 0;
            });
            $totalPaid = $student->sales->sum('paid_amount');
            $student->total_balance = $totalDue - $totalPaid;
            $student->financial_status = $student->total_balance > 0 ? 'debt' : 'paid';
            return $student;
        });

        $stages = \App\Models\Stage::getCached();
        $courses = \App\Models\Course::where('tenant_id', $this->tenant->id)->orderBy('title')->get();

        return view('center::students.index', compact('students', 'stages', 'courses'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Student::class);
        
        $stages = \App\Models\Stage::getCached();
        $courses = \App\Models\Course::where('tenant_id', $this->tenant->id)->orderBy('title')->get();
        
        return view('center::students.create', compact('stages', 'courses'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $this->authorize('create', Student::class);

        if (!$this->tenant->hasFeature('max_students')) {
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
            \Log::error('Student registration failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', __('center::messages.registration_failed') ?? 'حدث خطأ أثناء التسجيل: ' . $e->getMessage());
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
        $student = $this->findStudentOrFail($id);
        $this->authorize('update', $student);
        
        $stages = \App\Models\Stage::getCached();
        
        return view('center::students.edit', compact('student', 'stages'));
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
     * Send debt reminder via WhatsApp.
     */
    public function remindDebt($id, \App\Services\WhatsAppService $whatsappService)
    {
        $student = $this->findStudentOrFail($id);
        $this->authorize('update', $student);

        $totalDebt = Sale::where('student_id', $student->id)->sum(\Illuminate\Support\Facades\DB::raw('total_amount - paid_amount'));

        if ($totalDebt <= 0) {
            return redirect()->back()->with('info', __('center::messages.no_outstanding_debts'));
        }

        \App\Jobs\SendDebtReminderJob::dispatch($this->tenant, $student, $totalDebt);

        return redirect()->back()->with('success', __('center::messages.debt_reminder_sent'));
    }

    /**
     * View student financial statement (Ledger).
     */
    public function statement($id)
    {
        $student = $this->findStudentOrFail($id);
        $this->authorize('view', $student);
        $tenantId = $this->tenant->id;

        // Fetch recent Sales (Invoices) - Debits (Money student owes)
        $sales = Sale::where('student_id', $student->id)
            ->with('items.item')
            ->latest()
            ->limit(500)
            ->get()
            ->map(function ($s) {
                return [
                    'date' => $s->created_at,
                    'type' => 'invoice',
                    'amount' => $s->total_amount,
                    'description' => 'فاتورة مبيعات #' . $s->id,
                    'is_credit' => false,
                    'ref_id' => $s->id,
                ];
            });

        // Fetch recent Payments - Credits (Money student paid)
        $payments = Payment::whereHas('sale', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->with(['receiver'])
            ->latest()
            ->limit(500)
            ->get()
            ->map(function ($p) {
                return [
                    'date' => $p->paid_at ?? $p->created_at,
                    'type' => 'payment',
                    'amount' => $p->amount,
                    'description' => 'دفعة نقدية - الاستلام بواسطة: ' . ($p->receiver->name ?? 'طالب') . ' - فاتورة #' . $p->sale_id,
                    'is_credit' => true,
                    'ref_id' => $p->id,
                ];
            });

        // Fetch recent Refunds - Debits (Money returned to student, reversing payment)
        $refunds = Refund::whereHas('sale', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->with('processor')
            ->latest()
            ->limit(500)
            ->get()
            ->map(function ($r) {
                return [
                    'date' => $r->created_at,
                    'type' => 'refund',
                    'amount' => $r->amount, // Amount returned
                    'description' => 'استرداد مالي (Refund) - فاتورة #' . $r->sale_id . ($r->reason ? ' - ' . $r->reason : ''),
                    'is_credit' => false, // Reduces their credit, essentially increasing debt effectively
                    'ref_id' => $r->id,
                ];
            });

        // Merge and sort
        $ledger = $sales->concat($payments)->concat($refunds)->sortBy('date')->values();

        // Calculate running balance (Debt)
        $balance = 0; // Debt amount
        $ledger = $ledger->map(function ($transaction) use (&$balance) {
            if ($transaction['is_credit']) {
                $balance -= $transaction['amount']; // Payment decreases debt
            } else {
                $balance += $transaction['amount']; // Invoice or Refund increases debt
            }
            $transaction['balance'] = $balance;
            return $transaction;
        });

        $tenant = $this->tenant;

        // Current real debt
        $totalDebt = Sale::where('student_id', $student->id)->sum(\DB::raw('total_amount - paid_amount'));

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
                'restore_url' => route('center.students.restore', ['tenant' => $this->tenant->domain, 'id' => $id])
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
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
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
    public function downloadTemplate()
    {
        $this->authorize('create', Student::class);

        // Fetch real grades for this tenant
        $grades = \App\Models\Grade::where('tenant_id', $this->tenant->id)->limit(3)->get();
        
        $sampleData = [];
        if ($grades->isEmpty()) {
            $sampleData[] = ['Ahmed Ali', 'ahmed1@example.com', '01012345678', 'Primary 1'];
            $sampleData[] = ['Sara Khaled', 'sara2@example.com', '01023456789', 'Primary 2'];
        } else {
            $sampleData[] = ['Ahmed Ali', 'ahmed1@example.com', '01012345678', $grades->first()->name];
            if ($grades->count() > 1) {
                $sampleData[] = ['Sara Khaled', 'sara2@example.com', '01023456789', $grades->skip(1)->first()->name];
            }
        }

        // Generate HTML table that Excel reads natively with full Arabic support
        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $html .= '<head><meta charset="UTF-8">';
        $html .= '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
        $html .= '<x:Name>Students</x:Name>';
        $html .= '<x:WorksheetOptions><x:DisplayRightToLeft/><x:DisplayGridlines/></x:WorksheetOptions>';
        $html .= '</x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
        $html .= '<style>td{mso-number-format:\@;padding:5px;border:1px solid #ccc;font-family:Arial,sans-serif;font-size:12pt;} th{background:#4CAF50;color:#fff;padding:8px;border:1px solid #388E3C;font-family:Arial,sans-serif;font-size:12pt;font-weight:bold;}</style>';
        $html .= '</head><body>';
        $html .= '<table>';
        
        // Header row
        $html .= '<tr>';
        foreach (['name', 'email', 'phone', 'grade_level'] as $header) {
            $html .= '<th>' . e($header) . '</th>';
        }
        $html .= '</tr>';
        
        // Data rows
        foreach ($sampleData as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>' . e($cell) . '</td>';
            }
            $html .= '</tr>';
        }
        
        $html .= '</table></body></html>';

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
        
        if (!$this->tenant->hasFeature('max_students')) {
            return redirect()->back()->with('error', __('center::messages.msg_085'));
        }

        $method = $request->input('import_method', 'file');

        if ($method === 'paste') {
            // Paste from Excel method
            $request->validate([
                'paste_data' => 'required|string|min:5',
            ]);

            $pasteData = $request->input('paste_data');
            $lines = array_filter(explode("\n", $pasteData), fn($line) => trim($line) !== '');

            if (empty($lines)) {
                return redirect()->back()->withErrors(['paste_data' => 'لا توجد بيانات صالحة للاستيراد.']);
            }

            // Convert pasted data to CSV file
            $csvPath = 'temp/imports/' . uniqid('paste_') . '.csv';
            $fullCsvPath = storage_path('app/' . $csvPath);

            if (!is_dir(dirname($fullCsvPath))) {
                mkdir(dirname($fullCsvPath), 0755, true);
            }

            $fp = fopen($fullCsvPath, 'w');
            // Write header
            fputcsv($fp, ['name', 'email', 'phone', 'grade_level']);

            foreach ($lines as $line) {
                $line = trim($line);
                // Split by tab (Excel clipboard default) or comma
                $cols = str_contains($line, "\t") ? explode("\t", $line) : str_getcsv($line);
                $cols = array_map('trim', $cols);

                // Skip header rows
                if (isset($cols[0]) && strtolower($cols[0]) === 'name') continue;

                if (count($cols) >= 2 && !empty($cols[0]) && !empty($cols[1])) {
                    fputcsv($fp, [
                        $cols[0] ?? '',        // name
                        $cols[1] ?? '',        // email
                        $cols[2] ?? '',        // phone
                        $cols[3] ?? '',        // grade_level
                    ]);
                }
            }
            fclose($fp);

            $path = $csvPath;

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
                ->with('success', __('center::messages.msg_086') . ' - جاري المعالجة في الخلفية');
        } catch (\Exception $e) {
            \Log::error('Student import failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage());
        }
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

        if (!$guardian) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'guardian' => $guardian
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

        $email = $student->email ?: ($student->user ? $student->user->email : null);

        if (!$email) {
            return redirect()->back()->with('error', __('center::messages.no_student_email'));
        }

        try {
            \Illuminate\Support\Facades\Mail::to($email)->queue(new \App\Mail\CustomStudentMail(
                $student, 
                $request->subject, 
                $request->message,
                $this->tenant->name
            ));
            
            return redirect()->back()->with('success', __('center::messages.email_sent_success'));
        } catch (\Exception $e) {
            \Log::error("Failed to send email to student {$student->id}: " . $e->getMessage());
            return redirect()->back()->with('error', __('center::messages.email_send_error') . ': ' . $e->getMessage());
        }
    }

    public function idCard($id)
    {
        $student = $this->findStudentOrFail($id);
        return view('center::students.id_card', compact('student'));
    }
}
