<?php

namespace App\Services;

use App\Models\Commission;
use App\Models\Expense;
use App\Models\Instructor;
use App\Models\Payout;
use Illuminate\Support\Facades\DB;

class PayoutService
{
    /**
     * Process a payout for an instructor.
     */
    public function processPayout(Instructor $instructor, array $data)
    {
        return DB::transaction(function () use ($instructor, $data) {
            $tenantId = \Modules\Tenancy\Services\TenantResolver::get()->id;
            $amount = $data['amount'];

            // 1. Create Payout Record
            $payout = Payout::create([
                'tenant_id' => $tenantId,
                'instructor_id' => $instructor->id,
                'amount' => $amount,
                'payment_method' => $data['payment_method'] ?? 'cash',
                'transaction_reference' => $data['transaction_reference'] ?? null,
                'payout_date' => $data['payout_date'] ?? now(),
                'processed_by' => auth()->id(),
                'notes' => 'صرف مستحقات المدرس',
            ]);

            // 2. Mark Commissions as Paid
            // We find earned commissions for this instructor and mark them as paid
            // until the payout amount is covered (greedy settlement)
            $remainingToSettle = $amount;
            $commissions = Commission::where('instructor_id', $instructor->id)
                ->where('status', 'earned')
                ->orderBy('created_at', 'asc')
                ->get();

            $paidIds = [];
            foreach ($commissions as $commission) {
                if ($remainingToSettle <= 0) {
                    break;
                }

                if ($commission->amount <= $remainingToSettle) {
                    $paidIds[] = $commission->id;
                    $remainingToSettle -= $commission->amount;
                }
            }

            if (! empty($paidIds)) {
                Commission::whereIn('id', $paidIds)->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }

            // 3. Create Expense Record
            Expense::create([
                'tenant_id' => $tenantId,
                'category' => 'salaries',
                'amount' => $amount,
                'description' => 'صرف مستحقات للمدرس '.$instructor->name.' - رقم الصرفية #'.$payout->id,
                'date' => $data['payout_date'] ?? now(),
                'payment_method' => $data['payment_method'] ?? 'cash',
                'created_by' => auth()->id(),
            ]);

            return $payout;
        });
    }
}
