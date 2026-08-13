<?php

namespace App\Services\Student;

use App\Models\Payment;
use App\Models\Refund;
use App\Models\Sale;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Builds the financial statement (ledger) for a student: invoices,
 * payments and refunds merged chronologically with a running balance.
 */
class StudentLedgerService
{
    /**
     * @return array{ledger: Collection, totalDebt: float}
     */
    public function buildLedger(Student $student): array
    {
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
                    'description' => 'فاتورة مبيعات #'.$s->id,
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
                    'description' => 'دفعة نقدية - الاستلام بواسطة: '.($p->receiver->name ?? 'طالب').' - فاتورة #'.$p->sale_id,
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
                    'description' => 'استرداد مالي (Refund) - فاتورة #'.$r->sale_id.($r->reason ? ' - '.$r->reason : ''),
                    'is_credit' => false, // Reduces their credit, essentially increasing debt effectively
                    'ref_id' => $r->id,
                ];
            });

        // Merge and sort, then compute the running balance (debt)
        $ledger = $sales->concat($payments)->concat($refunds)->sortBy('date')->values();

        $balance = 0;
        $ledger = $ledger->map(function ($transaction) use (&$balance) {
            if ($transaction['is_credit']) {
                $balance -= $transaction['amount']; // Payment decreases debt
            } else {
                $balance += $transaction['amount']; // Invoice or Refund increases debt
            }
            $transaction['balance'] = $balance;

            return $transaction;
        });

        return [
            'ledger' => $ledger,
            'totalDebt' => (float) $this->totalDebt($student),
        ];
    }

    public function totalDebt(Student $student)
    {
        return Sale::where('student_id', $student->id)->sum(DB::raw('total_amount - paid_amount'));
    }

    /**
     * Full JSON payload for the sales-screen student summary panel:
     * profile, enrollments, financial ledger, stats and recent attendance.
     */
    public function buildStudentSummary(Student $student, $tenant): array
    {
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

        $payments = Payment::whereHas('sale', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })->where('tenant_id', $tenant->id)->get();

        $refunds = Refund::whereHas('sale', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })->where('tenant_id', $tenant->id)->get();

        // Combine into Ledger (Transactions Timeline)
        $ledger = collect();

        foreach ($sales as $sale) {
            $ledger->push([
                'id' => $sale->id,
                'date' => $sale->created_at->format('Y-m-d H:i'),
                'type' => 'invoice',
                'amount' => (float) $sale->total_amount,
                'status' => $sale->status,
                'remaining' => (float) ($sale->total_amount - $sale->paid_amount),
                'description' => 'فاتورة مبيعات #'.$sale->id,
            ]);
        }

        foreach ($payments as $payment) {
            $ledger->push([
                'id' => $payment->id,
                'date' => ($payment->paid_at ?? $payment->created_at)->format('Y-m-d H:i'),
                'type' => 'payment',
                'amount' => (float) $payment->amount,
                'method' => $payment->payment_method,
                'description' => 'سداد دفعة مالية'.($payment->payment_method ? " ({$payment->payment_method})" : ''),
                'sale_id' => $payment->sale_id,
            ]);
        }

        foreach ($refunds as $refund) {
            $ledger->push([
                'id' => $refund->id,
                'date' => $refund->created_at->format('Y-m-d H:i'),
                'type' => 'refund',
                'amount' => (float) $refund->amount,
                'description' => 'عملية استرداد (Refund)',
                'sale_id' => $refund->sale_id,
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
            ->with('course')
            ->orderBy('session_date', 'desc')
            ->get();

        $totalSessions = $attendance->count();
        $presentSessions = $attendance->where('status', 'present')->count();
        $attendanceRate = $totalSessions > 0 ? round(($presentSessions / $totalSessions) * 100) : 0;
        $recentAttendance = $attendance->take(5)->map(function ($att) {
            return [
                'date' => $att->session_date->format('Y-m-d'),
                'status' => $att->status,
                'course' => $att->course?->title ?? 'N/A',
            ];
        });

        return [
            'success' => true,
            'student' => [
                'name' => $student->name,
                'phone' => $student->phone,
                'status' => $student->status,
                'grade' => $student->grade_level_name,
                'id' => $student->id,
            ],
            'courses' => $courses,
            'stats' => [
                'total_debt' => number_format($totalDebt, 2),
                'total_paid' => number_format($totalPaid, 2),
                'attendance_rate' => $attendanceRate,
                'course_count' => $courses->count(),
            ],
            'ledger' => $ledger,
            'unpaid_invoices' => $sales->where('paid_amount', '<', 'total_amount')->map(function ($s) {
                $s->remaining = $s->total_amount - $s->paid_amount;

                return $s;
            })->values(),
            'recent_attendance' => $recentAttendance,
        ];
    }
}
