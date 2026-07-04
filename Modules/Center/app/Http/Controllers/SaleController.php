<?php

namespace Modules\Center\Http\Controllers;

use App\Models\Course;
use App\Models\Sale;
use App\Models\Student;
use App\Services\FinanceService;
use App\Services\RefundService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Center\Http\Controllers\CenterBaseController as Controller;
use Modules\Center\Http\Requests\StoreSaleRequest;

class SaleController extends Controller
{
    protected $financeService;

    protected $arabicReshaper;

    public function __construct(FinanceService $financeService, \App\Services\ArabicReshaper $arabicReshaper)
    {
        parent::__construct();
        $this->financeService = $financeService;
        $this->arabicReshaper = $arabicReshaper;
    }

    public function index()
    {
        $this->authorize('viewAny', Sale::class);
        $tenant = $this->tenant;
        $sales = Sale::where('tenant_id', $tenant->id)
            ->with('student')
            ->latest()
            ->paginate(10);

        return view('center::sales.index', compact('sales', 'tenant'));
    }

    public function overdue()
    {
        $this->authorize('viewAny', Sale::class);
        $tenant = $this->tenant;

        // Fetch students who have at least one sale with a remaining balance
        $studentsQuery = Student::where('tenant_id', $tenant->id)
            ->whereHas('sales', function ($query) {
                $query->whereRaw('paid_amount < total_amount');
            })
            ->with(['sales' => function ($query) {
                $query->whereRaw('paid_amount < total_amount');
            }]);

        $students = $studentsQuery->paginate(15);

        $students->getCollection()->transform(function ($student) {
            $student->total_debt = $student->sales->sum(function ($sale) {
                return $sale->total_amount - $sale->paid_amount;
            });

            return $student;
        });

        return view('center::sales.overdue', compact('students', 'tenant'));
    }

    public function account()
    {
        $this->authorize('viewAny', Sale::class);
        $tenant = $this->tenant;

        $students = Student::where('tenant_id', $tenant->id)
            ->with(['user', 'sales', 'enrollments.course'])
            ->paginate(15);

        return view('center::sales.account', compact('tenant', 'students'));
    }

    public function markPaid(Request $request)
    {
        $this->authorize('create', Sale::class);
        $tenant = $this->tenant;

        $request->validate([
            'student_id' => [
                'required',
                Rule::exists('students', 'id')->where('tenant_id', $tenant->id),
            ],
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $student = Student::with(['sales' => function ($query) {
            $query->whereRaw('paid_amount < total_amount')->orderBy('created_at', 'asc');
        }])->findOrFail($request->student_id);

        // Calculate balance directly from unpaid sales to be perfectly accurate
        $totalDebt = $student->sales->sum(function ($sale) {
            return $sale->total_amount - $sale->paid_amount;
        });

        if ($request->amount > $totalDebt) {
            return back()->with('error', __('center::messages.msg_071').' (المبلغ أكبر من المديونية)'); // Using existing error or custom message
        }

        try {
            $this->financeService->distributePayment(
                $student->sales,
                (float) $request->amount,
                $request->notes ?? 'تحصيل سريع للمستحقات'
            );

            return redirect()->back()->with('success', __('center::messages.msg_074'));
        } catch (\Exception $e) {
            \Log::error('markPaid failed: '.$e->getMessage());

            return back()->with('error', __('center::messages.error_unexpected') ?? 'حدث خطأ غير متوقع.');
        }
    }

    public function lookupStudents(Request $request)
    {
        $this->authorize('viewAny', Sale::class);
        $tenant = $this->tenant;
        $query = $request->get('q');

        if (empty($query)) {
            return response()->json([]);
        }

        $results = Student::where('tenant_id', $tenant->id)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%")
                    ->orWhere('code', 'like', "%{$query}%");
            })
            ->with(['grade.stage'])
            ->limit(10) // Limit for performance and UX
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'name' => $s->name,
                    'phone' => $s->phone,
                    'code' => $s->code,
                    'grade' => $s->grade_level_name,
                    'initial' => mb_substr($s->name, 0, 1, 'UTF-8'),
                ];
            });

        return response()->json($results);
    }

    public function create()
    {
        $this->authorize('create', Sale::class);
        $tenant = $this->tenant;
        $students = collect([]); // Don't load all students, rely on Select2 AJAX
        $courses = Course::where('tenant_id', $tenant->id)->get();

        return view('center::sales.create', compact('students', 'courses', 'tenant'));
    }

    public function store(StoreSaleRequest $request)
    {
        $this->authorize('create', Sale::class);

        try {
            $sale = $this->financeService->createSale($request->validated());

            return response()->json(['success' => true, 'sale_id' => $sale->id]);
        } catch (\Exception $e) {
            \Log::error('Sale creation failed: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => __('center::messages.error_unexpected') ?? 'حدث خطأ غير متوقع أثناء المعالجة'], 500);
        }
    }

    public function show($id)
    {
        $tenant = $this->tenant;
        $sale = Sale::where('tenant_id', $tenant->id)
            ->with(['student', 'items.item', 'payments.receiver', 'refunds.processor'])
            ->findOrFail($id);

        $this->authorize('view', $sale);

        return view('center::sales.show', compact('sale', 'tenant'));
    }

    public function addPayment(Request $request, $id)
    {
        $tenant = $this->tenant;
        $sale = Sale::where('tenant_id', $tenant->id)->findOrFail($id);
        $this->authorize('update', $sale);

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $this->financeService->addPayment($sale, $request->amount, $request->payment_method, $request->notes);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Payment added successfully']);
            }

            return back()->with('success', __('center::messages.msg_074'));
        } catch (\Exception $e) {
            \Log::error('addPayment failed: '.$e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => __('center::messages.error_unexpected') ?? 'حدث خطأ غير متوقع'], 500);
            }

            return back()->with('error', __('center::messages.error_unexpected') ?? 'حدث خطأ غير متوقع.');
        }
    }

    public function getStudentSummary($id, \App\Services\Student\StudentLedgerService $ledgerService)
    {
        $tenant = $this->tenant;
        $student = Student::with(['grade.stage'])->where('tenant_id', $tenant->id)->findOrFail($id);

        $this->authorize('view', $student);

        return response()->json($ledgerService->buildStudentSummary($student, $tenant));
    }

    public function downloadStatement($id)
    {
        $this->authorize('viewAny', Sale::class);

        $tenant = $this->tenant;
        $student = Student::where('tenant_id', $tenant->id)->findOrFail($id);

        $sales = Sale::where('student_id', $student->id)
            ->with(['items.item', 'payments'])
            ->orderBy('created_at', 'asc')
            ->get();

        $totalDebt = $sales->sum(function ($s) {
            return $s->total_amount - $s->paid_amount;
        });

        // Reshape Arabic
        $studentName = $this->arabicReshaper->reshape($student->name);
        $tenantName = $this->arabicReshaper->reshape($tenant->name);

        $pdf = Pdf::loadView('center::sales.statement', compact('student', 'sales', 'totalDebt', 'tenant', 'studentName', 'tenantName'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('statement-'.$student->id.'.pdf');
    }

    public function downloadReceipt($paymentId)
    {
        $tenant = $this->tenant;
        $payment = \App\Models\Payment::where('tenant_id', $tenant->id)
            ->with(['sale.student', 'sale.items.item', 'receiver'])
            ->findOrFail($paymentId);

        $this->authorize('view', $payment->sale);

        // Reshape Arabic strings for PDF
        $tenant->name = $this->arabicReshaper->reshape($tenant->name);
        if ($payment->sale->student) {
            $payment->sale->student->name = $this->arabicReshaper->reshape($payment->sale->student->name);
        }

        foreach ($payment->sale->items as $item) {
            if ($item->item) {
                // We use a temporary property to store reshaped title for the PDF
                $item->reshaped_title = $this->arabicReshaper->reshape($item->item->title ?: ($item->item->name ?: 'Item'));
            }
        }

        $pdf = Pdf::loadView('center::sales.receipt', compact('payment', 'tenant'))
            ->setPaper('a5', 'portrait');

        return $pdf->download('receipt-'.$payment->id.'.pdf');
    }

    /**
     * Process a refund for the sale.
     */
    public function refund(Request $request, $id, RefundService $refundService)
    {
        $tenant = $this->tenant;
        $sale = Sale::where('tenant_id', $tenant->id)->findOrFail($id);
        $this->authorize('update', $sale);

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:'.$sale->paid_amount,
            'reason' => 'nullable|string',
            'refund_method' => 'required|string',
            'unenroll_student' => 'boolean',
        ]);

        try {
            $refundService->processRefund($sale, $request->all());

            return redirect()->back()->with('success', __('center::messages.refund_success') ?? 'تمت عملية الاسترداد بنجاح وتحديث السجلات.');
        } catch (\Exception $e) {
            \Log::error('Refund failed: '.$e->getMessage());

            return redirect()->back()->with('error', __('center::messages.error_unexpected') ?? 'خطأ في عملية الاسترداد. يرجى المحاولة لاحقاً.');
        }
    }

    /**
     * Initiate online checkout for an invoice (Mock implementation).
     */
    public function checkout($id)
    {
        $tenant = $this->tenant;
        $sale = Sale::where('tenant_id', $tenant->id)->findOrFail($id);
        $this->authorize('view', $sale);

        // Ensure invoice is not fully paid
        if ($sale->status === 'paid') {
            return redirect()->back()->with('info', 'هذه الفاتورة مدفوعة بالكامل.');
        }

        return view('center::sales.checkout', compact('sale', 'tenant'));
    }

    /**
     * Handle successful mock online payment.
     */
    public function checkoutSuccess(Request $request, $id)
    {
        $tenant = $this->tenant;
        $sale = Sale::where('tenant_id', $tenant->id)->findOrFail($id);
        $this->authorize('update', $sale);

        if ($sale->status === 'paid') {
            return redirect()->route('center.sales.show', $sale->id)->with('success', 'الفاتورة مدفوعة بالفعل.');
        }

        try {
            // Amount to pay (Remaining)
            $amountToPay = $sale->total_amount - $sale->paid_amount;

            // Add payment via FinanceService
            $this->financeService->addPayment($sale, $amountToPay, 'online', 'دفعة إلكترونية مسددة عبر بوابة الدفع');

            return redirect()->route('center.sales.show', $sale->id)
                ->with('success', __('center::messages.online_payment_success') ?? 'تم الدفع الإلكتروني بنجاح!');
        } catch (\Exception $e) {
            \Log::error('checkoutSuccess failed: '.$e->getMessage());

            return redirect()->route('center.sales.show', $sale->id)
                ->with('error', __('center::messages.error_unexpected') ?? 'حدث خطأ أثناء معالجة الدفع الإلكتروني.');
        }
    }
}
