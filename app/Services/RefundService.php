<?php

namespace App\Services;

use App\Models\Refund;
use App\Models\Sale;
use App\Models\Commission;
use App\Models\Enrollment;
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

            // 2. Update Sale Status/Paid Amount
            $newPaidAmount = $lockedSale->paid_amount - $refundAmount;
            $lockedSale->update([
                'paid_amount' => $newPaidAmount,
            ]);
            $lockedSale->updateStatus();

            // 3. Reverse Commissions
            // We find commissions linked to this sale that are NOT yet paid
            $commissions = Commission::where('sale_id', $sale->id)
                ->where('status', 'earned')
                ->get();

            foreach ($commissions as $commission) {
                // For simplicity: we void the commission if it's a full refund, 
                // or proportionally if we want to be advanced. 
                // Here, we'll just delete them if the sale is significantly reversed.
                $commission->delete(); 
            }

            // 4. Handle Enrollments (Optional)
            // If the user wants to un-enroll, they usually do it separately, 
            // but we can add a flag here.
            if ($data['unenroll_student'] ?? false) {
                $sale->items->each(function($item) use ($sale) {
                    if ($item->item_type === \App\Models\Course::class) {
                        Enrollment::where('student_id', $sale->student_id)
                            ->where('course_id', $item->item_id)
                            ->delete();
                    }
                });
            }

            return $refund;
        });
    }
}


