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

        // Handle Profile Photo Upload using trait
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
        $studentCount = Student::where('tenant_id', $this->tenant->id)->count();
        if ($studentCount === 1) {
            return redirect()->route('center.dashboard')->with('success', __('center::messages.first_student_onboarding'));
        }

        return redirect()->route('center.students.index', ['tenant' => $this->tenant->domain])->with('success', __('center::messages.msg_081'));
    }


    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $student = Student::where('tenant_id', $this->tenant->id)
            ->with(['grade.stage', 'user', 'tenant'])
            ->findOrFail($id);
            
        $this->authorize('view', $student);
            
        $data = $this->studentService->getProfileData($student);

        return view('center::students.show', array_merge(['student' => $student], $data));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $student = Student::where('tenant_id', $this->tenant->id)->findOrFail($id);
        $this->authorize('update', $student);
        
        $stages = \App\Models\Stage::getCached();
        
        return view('center::students.edit', compact('student', 'stages'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, $id): RedirectResponse
    {
        $student = Student::where('tenant_id', $this->tenant->id)->findOrFail($id);
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
        $student = Student::where('tenant_id', $this->tenant->id)->findOrFail($id);
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
        $student = Student::where('tenant_id', $this->tenant->id)->findOrFail($id);
        $this->authorize('update', $student);

        $totalDebt = Sale::where('student_id', $student->id)->sum(\Illuminate\Support\Facades\DB::raw('total_amount - paid_amount'));

        if ($totalDebt <= 0) {
            return redirect()->back()->with('info', __('center::messages.no_outstanding_debts'));
        }

        $success = $whatsappService->sendDebtReminder($this->tenant, $student, $totalDebt);

        if ($success) {
            return redirect()->back()->with('success', __('center::messages.debt_reminder_sent'));
        } else {
            return redirect()->back()->with('warning', __('center::messages.debt_reminder_failed'));
        }
    }

    /**
     * View student financial statement (Ledger).
     */
    public function statement($id)
    {
        $student = Student::findOrFail($id);
        $this->authorize('view', $student);
        $tenantId = $this->tenant->id;

        // Fetch Sales (Invoices) - Debits (Money student owes)
        $sales = Sale::where('student_id', $student->id)
            ->with('items.item')
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

        // Fetch Payments - Credits (Money student paid)
        $payments = Payment::whereHas('sale', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->with(['receiver'])
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

        // Fetch Refunds - Debits (Money returned to student, reversing payment)
        $refunds = Refund::whereHas('sale', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->with('processor')
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
        $student = Student::where('tenant_id', $this->tenant->id)->findOrFail($id);
        $this->authorize('delete', $student);

        // Delete profile photo
        if ($student->profile_photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($student->profile_photo);
        }

        $this->studentService->deleteStudent($student, auth()->user());

        return redirect()->route('center.students.index', ['tenant' => $this->tenant->domain])->with('success', __('center::messages.msg_084'));
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

        // Fetch a few real grades for this tenant to use as realistic examples
        $grades = \App\Models\Grade::where('tenant_id', $this->tenant->id)->limit(3)->get();
        
        $data = [];
        $data[] = ['name', 'email', 'phone', 'grade_level']; // headers
        
        if ($grades->isEmpty()) {
            $data[] = ['Ahmed Ali', 'ahmed1@example.com', '01012345678', 'Primary 1'];
            $data[] = ['Sara Khaled', 'sara2@example.com', '01023456789', 'Primary 2'];
        } else {
            $data[] = ['Ahmed Ali', 'ahmed1@example.com', '01012345678', $grades->first()->name];
            if ($grades->count() > 1) {
                $data[] = ['Sara Khaled', 'sara2@example.com', '01023456789', $grades->skip(1)->first()->name];
            }
        }

        $csv = chr(0xEF) . chr(0xBB) . chr(0xBF); // UTF-8 BOM
        
        $fp = fopen('php://temp', 'r+');
        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }
        rewind($fp);
        $csv .= stream_get_contents($fp);
        fclose($fp);

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="students-template.csv"');
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
     * Handle the CSV import.
     */
    public function import(Request $request)
    {
        // Authorization: ensure user can create students
        $this->authorize('create', Student::class);
        
        if (!$this->tenant->hasFeature('max_students')) {
            return redirect()->back()->with('error', __('center::messages.msg_085'));
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120', // Up to 5MB
        ]);

        $path = $request->file('file')->store('temp/imports');
        
        \App\Jobs\ImportStudentsJob::dispatchSync($path, $this->tenant->id, auth()->id());

        return redirect()->route('center.students.index', ['tenant' => $this->tenant->domain])
            ->with('success', __('center::messages.msg_086'));
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
        $student = Student::where('tenant_id', $this->tenant->id)->findOrFail($id);
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
            \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\CustomStudentMail(
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
        $student = Student::where('tenant_id', $this->tenant->id)->findOrFail($id);
        return view('center::students.id_card', compact('student'));
    }
}
