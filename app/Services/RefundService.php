<?php

namespace App\Services;

use App\Models\Commission;
use App\Models\Enrollment;
use App\Models\Refund;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class RefundService
{
    /**
     * Process a refund for a sale.
     */
    public function processRefund(Sale $sale, array $data)
    {
        return DB::transaction(function () use ($sale, $data) {
            $tenantId = \Modules\Tenancy\Services\TenantResolver::get()->id;

            // Lock the sale record to prevent double-spend race conditions
            $lockedSale = Sale::lockForUpdate()->find($sale->id);
            $refundAmount = $data['amount'];

            if ($lockedSale->paid_amount < $refundAmount) {
                throw new \Exception('Insufficient paid amount for this refund.');
            }

            // 1. Create Refund Record
            $refund = Refund::create([
                'tenant_id' => $tenantId,
                'sale_id' => $lockedSale->id,
                'amount' => $refundAmount,
                'reason' => $data['reason'] ?? 'استرداد مبلغ',
                'refund_method' => $data['refund_method'] ?? 'cash',
                'processed_by' => auth()->id(),
            ]);

            // 2. Update Sale Status/Paid Amount AND Total Amount
            // FIX: Reduce total_amount alongside paid_amount so the student
            // doesn't owe money (debt) for a service they returned.
            $newPaidAmount = $lockedSale->paid_amount - $refundAmount;
            $newTotalAmount = max(0, $lockedSale->total_amount - $refundAmount);

            $lockedSale->update([
                'paid_amount' => $newPaidAmount,
                'total_amount' => $newTotalAmount,
            ]);
            $lockedSale->updateStatus();

            // 3. Reverse Commissions
            $commissions = Commission::where('sale_id', $sale->id)
                ->where('status', 'earned')
                ->get();

            // Calculate the percentage of the refund relative to the original paid amount
            $originalPaid = $lockedSale->paid_amount + $refundAmount;
            $refundPercentage = $originalPaid > 0 ? ($refundAmount / $originalPaid) : 1;

            foreach ($commissions as $commission) {
                if ($refundPercentage >= 0.99) {
                    $commission->delete();
                } else {
                    $newCommAmount = max(0, $commission->amount * (1 - $refundPercentage));
                    $commission->update(['amount' => $newCommAmount]);
                }
            }

            // 4. Handle Enrollments (Optional Unenrollment)
            if ($data['unenroll_student'] ?? false) {
                // FIX: Use user_id instead of student_id since Enrollment uses user_id
                $userId = $sale->student->user_id;

                if ($userId) {
                    $sale->items->each(function ($item) use ($userId) {
                        if ($item->item_type === \App\Models\Course::class) {
                            Enrollment::where('user_id', $userId)
                                ->where('course_id', $item->item_id)
                                ->delete();
                        }
                    });
                }
            }

            return $refund;
        });
    }
}
