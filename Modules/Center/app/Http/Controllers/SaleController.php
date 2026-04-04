<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Services\FinanceService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payment;
use App\Models\Refund;
use App\Services\RefundService;

class SaleController extends Controller
{
    protected $financeService;
    protected $arabicReshaper;

    public function __construct(FinanceService $financeService, \App\Services\ArabicReshaper $arabicReshaper)
    {
        $this->financeService = $financeService;
        $this->arabicReshaper = $arabicReshaper;
    }
    public function index()
    {
        $this->authorize('viewAny', Sale::class);
        $tenant = app('tenant');
        $sales = Sale::where('tenant_id', $tenant->id)
            ->with('student')
            ->latest()
            ->paginate(10);
            
        return view('center::sales.index', compact('sales', 'tenant'));
    }

    public function overdue()
    {
        $this->authorize('viewAny', Sale::class);
        $tenant = app('tenant');
        
        // Fetch students who have at least one sale with a remaining balance
        $students = Student::where('tenant_id', $tenant->id)
            ->whereHas('sales', function($query) {
                $query->whereRaw('paid_amount < total_amount');
            })
            ->with(['sales' => function($query) {
                $query->whereRaw('paid_amount < total_amount');
            }])
            ->get()
            ->map(function($student) {
                $student->total_debt = $student->sales->sum(function($sale) {
                    return $sale->total_amount - $sale->paid_amount;
                });
                return $student;
            })
            ->sortByDesc('total_debt');

        return view('center::sales.overdue', compact('students', 'tenant'));
    }

    public function account()
    {
        $this->authorize('viewAny', Sale::class);
        $tenant = app('tenant');
        // We no longer load all students here to keep the page lightweight.
        // The search bar will use AJAX to find students.
        return view('center::sales.account', compact('tenant'));
    }

    public function lookupStudents(Request $request)
    {
        $this->authorize('viewAny', Sale::class);
        $tenant = app('tenant');
        $query = $request->get('q');

        if (empty($query)) {
            return response()->json([]);
        }

        $results = Student::where('tenant_id', $tenant->id)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%")
                  ->orWhere('code', 'like', "%{$query}%");
            })
            ->with(['grade.stage'])
            ->limit(10) // Limit for performance and UX
            ->get()
            ->map(function($s) {
                return [
                    'id' => $s->id,
                    'name' => $s->name,
                    'phone' => $s->phone,
                    'code' => $s->code,
                    'grade' => $s->grade_level_name,
                    'initial' => mb_substr($s->name, 0, 1, 'UTF-8')
                ];
            });

        return response()->json($results);
    }

    public function create()
    {
        $this->authorize('create', Sale::class);
        $tenant = app('tenant');
        $students = Student::where('tenant_id', $tenant->id)->get();
        $courses = Course::where('tenant_id', $tenant->id)->get();
        
        return view('center::sales.create', compact('students', 'courses', 'tenant'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Sale::class);
        $request->validate([
            'student_id' => [
                'required',
                Rule::exists('students', 'id')->where('tenant_id', app('tenant')->id)
            ],
            'items' => 'required|array|min:1',
            'items.*.id' => [
                'required', 
                Rule::exists('courses', 'id')->where('tenant_id', app('tenant')->id)
            ],
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'paid_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
        ]);

        try {
            $sale = $this->financeService->createSale($request->all());
            return response()->json(['success' => true, 'sale_id' => $sale->id]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $tenant = app('tenant');
        $sale = Sale::where('tenant_id', $tenant->id)
            ->with(['student', 'items.item', 'payments.receiver', 'refunds.processor'])
            ->findOrFail($id);

        $this->authorize('view', $sale);

        return view('center::sales.show', compact('sale', 'tenant'));
    }

    public function addPayment(Request $request, $id)
    {
        $tenant = app('tenant');
        $sale = Sale::where('tenant_id', $tenant->id)->findOrFail($id);
        $this->authorize('update', $sale);

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $this->financeService->addPayment($sale, $request->amount, $request->payment_method, $request->notes);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Payment added successfully']);
        }

        return back()->with('success', __('center::messages.msg_074'));
    }

    public function getStudentSummary($id)
    {
        $tenant = app('tenant');
        $student = Student::with(['grade.stage'])->where('tenant_id', $tenant->id)->findOrFail($id);
        
        // Active Enrollments
        $courses = DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->where('enrollments.user_id', $student->user_id)
            ->where('enrollments.tenant_id', $tenant->id)
            ->select('courses.title', 'enrollments.enrolled_at', 'enrollments.status')
            ->get();

        // Financial Summary: Fetch all Sales, Payments, and Refunds
        $sales = Sale::where('student_id', $student->id)
            ->where('tenant_id', $tenant->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $payments = Payment::whereHas('sale', function($q) use ($student) {
            $q->where('student_id', $student->id);
        })->where('tenant_id', $tenant->id)->get();

        $refunds = Refund::whereHas('sale', function($q) use ($student) {
            $q->where('student_id', $student->id);
        })->where('tenant_id', $tenant->id)->get();

        // Combine into Ledger (Transactions Timeline)
        $ledger = collect();

        foreach ($sales as $sale) {
            $ledger->push([
                'id' => $sale->id,
                'date' => $sale->created_at->format('Y-m-d H:i'),
                'type' => 'invoice',
                'amount' => (float)$sale->total_amount,
                'status' => $sale->status,
                'remaining' => (float)($sale->total_amount - $sale->paid_amount),
                'description' => 'فاتورة مبيعات #' . $sale->id
            ]);
        }

        foreach ($payments as $payment) {
            $ledger->push([
                'id' => $payment->id,
                'date' => ($payment->paid_at ?? $payment->created_at)->format('Y-m-d H:i'),
                'type' => 'payment',
                'amount' => (float)$payment->amount,
                'method' => $payment->payment_method,
                'description' => 'سداد دفعة مالية' . ($payment->payment_method ? " ({$payment->payment_method})" : ""),
                'sale_id' => $payment->sale_id
            ]);
        }

        foreach ($refunds as $refund) {
            $ledger->push([
                'id' => $refund->id,
                'date' => $refund->created_at->format('Y-m-d H:i'),
                'type' => 'refund',
                'amount' => (float)$refund->amount,
                'description' => 'عملية استرداد (Refund)',
                'sale_id' => $refund->sale_id
            ]);
        }

        $ledger = $ledger->sortByDesc('date')->values();

        // Stats calculation
        $totalBilled = $sales->sum('total_amount');
        $totalPaid = $payments->sum('amount');
        $totalRefunded = $refunds->sum('amount');
        $totalDebt = $totalBilled - $totalPaid + $totalRefunded;

        // Attendance Stats
        $attendance = \Modules\Center\Models\Attendance::where('student_id', $student->id)
            ->where('tenant_id', $tenant->id)
            ->orderBy('session_date', 'desc')
            ->get();
        
        $totalSessions = $attendance->count();
        $presentSessions = $attendance->where('status', 'present')->count();
        $attendanceRate = $totalSessions > 0 ? round(($presentSessions / $totalSessions) * 100) : 0;
        $recentAttendance = $attendance->take(5)->map(function($att) {
             return [
                 'date' => $att->session_date->format('Y-m-d'),
                 'status' => $att->status,
                 'course' => $att->course?->title ?? 'N/A'
             ];
        });

        return response()->json([
            'success' => true,
            'student' => [
                'name' => $student->name,
                'phone' => $student->phone,
                'status' => $student->status,
                'grade' => $student->grade_level_name,
                'id' => $student->id
            ],
            'courses' => $courses,
            'stats' => [
                'total_debt' => number_format($totalDebt, 2),
                'total_paid' => number_format($totalPaid, 2),
                'attendance_rate' => $attendanceRate,
                'course_count' => $courses->count(),
            ],
            'ledger' => $ledger,
            'unpaid_invoices' => $sales->where('paid_amount', '<', 'total_amount')->map(function($s) {
                $s->remaining = $s->total_amount - $s->paid_amount;
                return $s;
            })->values(),
            'recent_attendance' => $recentAttendance,
        ]);
    }

    public function downloadStatement($id)
    {
        $tenant = app('tenant');
        $student = Student::where('tenant_id', $tenant->id)->findOrFail($id);
        
        $sales = Sale::where('student_id', $student->id)
            ->with(['items.item', 'payments'])
            ->orderBy('created_at', 'asc')
            ->get();

        $totalDebt = $sales->sum(function($s) { return $s->total_amount - $s->paid_amount; });

        // Reshape Arabic
        $studentName = $this->arabicReshaper->reshape($student->name);
        $tenantName = $this->arabicReshaper->reshape($tenant->name);

        $pdf = Pdf::loadView('center::sales.statement', compact('student', 'sales', 'totalDebt', 'tenant', 'studentName', 'tenantName'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('statement-' . $student->id . '.pdf');
    }

    public function downloadReceipt($paymentId)
    {
        $tenant = app('tenant');
        $payment = \App\Models\Payment::where('tenant_id', $tenant->id)
            ->with(['sale.student', 'receiver'])
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

        return $pdf->download('receipt-' . $payment->id . '.pdf');
    }

    /**
     * Process a refund for the sale.
     */
    public function refund(Request $request, $id, RefundService $refundService)
    {
        $tenant = app('tenant');
        $sale = Sale::where('tenant_id', $tenant->id)->findOrFail($id);
        $this->authorize('update', $sale);

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $sale->paid_amount,
            'reason' => 'nullable|string',
            'refund_method' => 'required|string',
            'unenroll_student' => 'boolean',
        ]);

        try {
            $refundService->processRefund($sale, $request->all());
            return redirect()->back()->with('success', 'تمت عملية الاسترداد بنجاح وتحديث السجلات.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطأ في عملية الاسترداد: ' . $e->getMessage());
        }
    }

    /**
     * Initiate online checkout for an invoice (Mock implementation).
     */
    public function checkout($id)
    {
        $tenant = app('tenant');
        $sale = Sale::where('tenant_id', $tenant->id)->findOrFail($id);
        
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
        $tenant = app('tenant');
        $sale = Sale::where('tenant_id', $tenant->id)->findOrFail($id);

        if ($sale->status === 'paid') {
            return redirect()->route('center.sales.show', $sale->id)->with('success', 'الفاتورة مدفوعة بالفعل.');
        }

        // Amount to pay (Remaining)
        $amountToPay = $sale->total_amount - $sale->paid_amount;

        // Add payment via FinanceService
        $this->financeService->addPayment($sale, $amountToPay, 'online', 'دفعة إلكترونية مسددة عبر بوابة الدفع');

        return redirect()->route('center.sales.show', $sale->id)
            ->with('success', 'تم الدفع الإلكتروني بنجاح!');
    }
}

