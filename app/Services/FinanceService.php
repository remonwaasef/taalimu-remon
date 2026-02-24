<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Course;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Commission;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    protected $courseService;
    protected $whatsappService;

    public function __construct(CourseService $courseService, WhatsAppService $whatsappService)
    {
        $this->courseService = $courseService;
        $this->whatsappService = $whatsappService;
    }
    /**
     * Process a new sale.
     *
     * @param array $data
     * @return Sale
     */
    public function createSale(array $data)
    {
        return DB::transaction(function () use ($data) {
            $tenantId = app('tenant')->id;

            // 1. Fetch actual prices from DB — scoped to current tenant to prevent cross-tenant manipulation
            $courseIds = collect($data['items'])->pluck('id')->toArray();
            $courses = Course::with('instructor')->whereIn('id', $courseIds)
                ->where('tenant_id', $tenantId)
                ->get()
                ->keyBy('id');

            $subtotalAmount = 0;
            $itemsToCreate = [];

            foreach ($data['items'] as $item) {
                $course = $courses->get($item['id']);
                if (!$course) {
                    throw new \Exception("Course not found: " . $item['id']);
                }

                $price = $course->price;
                $subtotalAmount += $price;

                $itemsToCreate[] = [
                    'item_type' => Course::class,
                    'item_id' => $course->id,
                    'price' => $price,
                    'quantity' => 1,
                ];
            }

            $discountAmount = $data['discount_amount'] ?? 0;
            $taxAmount = $data['tax_amount'] ?? 0;
            $totalAmount = $subtotalAmount - $discountAmount + $taxAmount;

            // 2. Determine Initial Status
            $status = $this->determineStatus($totalAmount, $data['paid_amount']);

            // 3. Create Sale
            $sale = Sale::create([
                'tenant_id' => $tenantId,
                'student_id' => $data['student_id'],
                'subtotal_amount' => $subtotalAmount,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $data['paid_amount'],
                'status' => $status,
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'] ?? null,
            ]);

            // 4. Create Sale Items — reuse already loaded courses
            $student = Student::where('id', $data['student_id'])
                ->where('tenant_id', $tenantId)
                ->firstOrFail();
            foreach ($itemsToCreate as $itemData) {
                $itemData['sale_id'] = $sale->id;
                $saleItem = SaleItem::create($itemData);

                // Commission Logic
                if ($itemData['item_type'] === Course::class) {
                    $course = $courses->get($itemData['item_id']);
                    if ($course && $course->instructor && $course->instructor->commission_rate > 0) {
                        $rate = $course->instructor->commission_rate;
                        $type = $course->instructor->commission_type ?? 'percentage';
                        $amount = ($type === 'percentage') ? ($itemData['price'] * $rate) / 100 : $rate;

                        Commission::create([
                            'tenant_id' => $tenantId,
                            'instructor_id' => $course->instructor_id,
                            'sale_id' => $sale->id,
                            'sale_item_id' => $saleItem->id,
                            'amount' => $amount,
                            'rate' => $rate,
                            'status' => 'earned', // Auto-earn on sale
                        ]);
                    }
                }

                if ($itemData['item_type'] === Course::class && $student) {
                    $course = $courses->get($itemData['item_id']); // Use cached collection instead of N+1
                    if ($course) {
                        try {
                            $enrollment = $this->courseService->enrollStudent($course, $student);
                            
                            // Add sessions to balance
                            if ($course->sessions_count > 0) {
                                $enrollment->update([
                                    'remaining_sessions' => $enrollment->remaining_sessions + $course->sessions_count
                                ]);
                            }
                        } catch (\Exception $e) {
                            // Already enrolled or other minor error, log it or ignore
                            \Illuminate\Support\Facades\Log::warning("Auto-enrollment failed: " . $e->getMessage());
                        }
                    }
                }
            }

            // 5. Create First Payment Record in Ledger
            if ($data['paid_amount'] > 0) {
                Payment::create([
                    'tenant_id' => $tenantId,
                    'sale_id' => $sale->id,
                    'amount' => $data['paid_amount'],
                    'payment_method' => $data['payment_method'],
                    'received_by' => auth()->id(),
                    'paid_at' => now(),
                    'notes' => $data['notes'] ?? 'الدفعة الأولى عند البيع',
                ]);

                // WhatsApp Notification
                $student = Student::find($data['student_id']);
                if ($student) {
                    $this->whatsappService->sendPaymentNotification(app('tenant'), $student, $data['paid_amount'], $totalAmount - $data['paid_amount']);
                }
            }

            return $sale;
        });
    }

    /**
     * Add a payment to an existing sale.
     *
     * @param Sale $sale
     * @param float $amount
     * @param string|null $method
     * @param string|null $notes
     * @return Sale
     */
    public function addPayment(Sale $sale, $amount, $method = null, $notes = null)
    {
        return DB::transaction(function () use ($sale, $amount, $method, $notes) {
            $newPaidAmount = $sale->paid_amount + $amount;
            $status = $this->determineStatus($sale->total_amount, $newPaidAmount);

            $sale->update([
                'paid_amount' => $newPaidAmount,
                'status' => $status,
            ]);

            // Create Payment Record
            Payment::create([
                'tenant_id' => $sale->tenant_id,
                'sale_id' => $sale->id,
                'amount' => $amount,
                'payment_method' => $method ?? $sale->payment_method,
                'received_by' => auth()->id(),
                'paid_at' => now(),
                'notes' => $notes ?? 'إضافة دفعة لاحقة',
            ]);

            // WhatsApp Notification
            $this->whatsappService->sendPaymentNotification(app('tenant'), $sale->student, $amount, $sale->total_amount - $newPaidAmount);

            return $sale;
        });
    }

    /**
     * Determine payment status based on amounts.
     *
     * @param float $total
     * @param float $paid
     * @return string
     */
    protected function determineStatus($total, $paid)
    {
        if ($paid >= $total) {
            return 'paid';
        } elseif ($paid > 0) {
            return 'partial';
        }
        return 'pending';
    }
}
