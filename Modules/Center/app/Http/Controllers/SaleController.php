<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\FinanceService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payment;

class SaleController extends Controller
{
    protected $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
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
            'student_id' => 'required|exists:students,id',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:courses,id',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'paid_amount' => 'required|numeric|min:0',
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

        return back()->with('success', 'Payment added and recorded in ledger.');
    }

    public function getStudentSummary($id)
    {
        $tenant = app('tenant');
        $student = Student::where('tenant_id', $tenant->id)->findOrFail($id);
        
        // Active Enrollments
        $courses = DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->where('enrollments.user_id', $student->user_id)
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

        // Student User status (for subscription/active check)
        $userStatus = $student->user?->status ?? 'unknown';

        return response()->json([
            'success' => true,
            'student' => [
                'name' => $student->name,
                'phone' => $student->phone,
                'status' => $userStatus,
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

        $pdf = Pdf::loadView('center::sales.receipt', compact('payment', 'tenant'))
            ->setPaper('a5', 'portrait');

        return $pdf->download('receipt-' . $payment->id . '.pdf');
    }
}
