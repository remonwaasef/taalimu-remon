<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
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
        $this->studentService = $studentService;
        $this->studentQuery = $studentQuery;
    }
    public function index(Request $request)
    {
        $this->authorize('viewAny', Student::class);
        
        $query = Student::query();
        $query = $this->studentQuery->apply($query, $request->all());

        $students = $query->with('grade.stage')->latest()->paginate(10);
        
        $stages = \App\Models\Stage::getCached();

        return view('center::students.index', compact('students', 'stages'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Student::class);
        
        $stages = \App\Models\Stage::getCached();
        
        return view('center::students.create', compact('stages'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $this->authorize('create', Student::class);

        if (!app('tenant')->hasFeature('max_students')) {
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

        return redirect()->route('center.students.index', ['tenant' => app('tenant')->domain])->with('success', __('center::messages.msg_081'));
    }


    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $student = Student::where('tenant_id', app('tenant')->id)
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
        $student = Student::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('update', $student);
        
        $stages = \App\Models\Stage::getCached();
        
        return view('center::students.edit', compact('student', 'stages'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, $id): RedirectResponse
    {
        $student = Student::where('tenant_id', app('tenant')->id)->findOrFail($id);
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

        return redirect()->route('center.students.index', ['tenant' => app('tenant')->domain])->with('success', __('center::messages.msg_082'));
    }

    public function resetPassword($id): RedirectResponse
    {
        $student = Student::where('tenant_id', app('tenant')->id)->findOrFail($id);
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
        $student = Student::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('update', $student);

        $totalDebt = Sale::where('student_id', $student->id)->sum(\Illuminate\Support\Facades\DB::raw('total_amount - paid_amount'));

        if ($totalDebt <= 0) {
            return redirect()->back()->with('info', 'الطالب ليس عليه أي مديونيات متأخرة.');
        }

        $success = $whatsappService->sendDebtReminder(app('tenant'), $student, $totalDebt);

        if ($success) {
            return redirect()->back()->with('success', 'تم إرسال تذكير السداد عبر الواتساب بنجاح.');
        } else {
            return redirect()->back()->with('warning', 'تعذر إرسال التذكير. تأكد من إعداد خدمة الواتساب للمركز وأن للطالب رقم هاتف صحيح.');
        }
    }

    /**
     * View student financial statement (Ledger).
     */
    public function statement($id)
    {
        $student = Student::findOrFail($id);
        $this->authorize('view', $student);
        $tenantId = app('tenant')->id;

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
        foreach ($ledger as $key => $transaction) {
            if ($transaction['is_credit']) {
                $balance -= $transaction['amount']; // Payment decreases debt
            } else {
                $balance += $transaction['amount']; // Invoice or Refund increases debt
            }
            $ledger[$key]['balance'] = $balance;
        }

        $tenant = app('tenant');

        // Current real debt
        $totalDebt = Sale::where('student_id', $student->id)->sum(\DB::raw('total_amount - paid_amount'));

        return view('center::students.statement', compact('student', 'ledger', 'tenant', 'totalDebt'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $student = Student::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('delete', $student);

        // Delete profile photo
        if ($student->profile_photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($student->profile_photo);
        }

        $this->studentService->deleteStudent($student, auth()->user());

        return redirect()->route('center.students.index', ['tenant' => app('tenant')->domain])->with('success', __('center::messages.msg_084'));
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
        
        if (!app('tenant')->hasFeature('max_students')) {
            return redirect()->back()->with('error', __('center::messages.msg_085'));
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120', // Up to 5MB
        ]);

        $path = $request->file('file')->store('temp/imports');
        
        \App\Jobs\ImportStudentsJob::dispatch($path, app('tenant')->id, auth()->id());

        return redirect()->route('center.students.index', ['tenant' => app('tenant')->domain])
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

        $guardian = \App\Models\Guardian::where('tenant_id', app('tenant')->id)
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
}
