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
        
        $query = Sale::where('tenant_id', $tenant->id);
        
        $totalReceived = (clone $query)->sum('paid_amount');
        $totalDue = (clone $query)->selectRaw('SUM(total_amount - paid_amount) as due')->value('due') ?? 0;
        
        $sales = $query->with('student')->latest()->paginate(10);
        
        return view('center::sales.index', compact('sales', 'tenant', 'totalReceived', 'totalDue'));
    }

    public function account()
    {
        $this->authorize('viewAny', Sale::class);
        $tenant = app('tenant');
        $students = Student::where('tenant_id', $tenant->id)->get();
        return view('center::sales.account', compact('students', 'tenant'));
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
            ->with(['student', 'items.item'])
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
        $student = Student::where('tenant_id', $tenant->id)->findOrFail($id);
        
        // Active Enrollments
        $courses = DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->where('enrollments.user_id', $student->user_id)
            ->where('enrollments.tenant_id', $tenant->id)
            ->select('courses.title', 'enrollments.enrolled_at', 'enrollments.status')
            ->get();

        // Financial Summary
        $sales = Sale::where('student_id', $student->id)
            ->select('id', 'total_amount', 'paid_amount', 'status', 'created_at')
            ->get()
            ->map(function($sale) {
                $sale->remaining = $sale->total_amount - $sale->paid_amount;
                return $sale;
            });

        $totalDebt = $sales->sum('remaining');
        $unpaidSales = $sales->where('remaining', '>', 0)->values();

        // Student status (for clerical check)
        $studentStatus = $student->status ?? 'unknown';

        return response()->json([
            'success' => true,
            'student' => [
                'name' => $student->name,
                'phone' => $student->phone,
                'status' => $studentStatus,
            ],
            'courses' => $courses,
            'total_debt' => number_format($totalDebt, 2),
            'unpaid_invoices' => $unpaidSales,
        ]);
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

