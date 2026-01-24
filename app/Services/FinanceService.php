<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Course;
use App\Models\Student;
use App\Models\Payment;
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

            // 1. Fetch actual prices from DB to prevent manipulation
            $courseIds = collect($data['items'])->pluck('id')->toArray();
            $courses = Course::whereIn('id', $courseIds)->get()->keyBy('id');

            $totalAmount = 0;
            $itemsToCreate = [];

            foreach ($data['items'] as $item) {
                $course = $courses->get($item['id']);
                if (!$course) {
                    throw new \Exception("Course not found: " . $item['id']);
                }

                $price = $course->price;
                $totalAmount += $price;

                $itemsToCreate[] = [
                    'item_type' => Course::class,
                    'item_id' => $course->id,
                    'price' => $price,
                    'quantity' => 1,
                ];
            }

            // 2. Determine Initial Status
            $status = $this->determineStatus($totalAmount, $data['paid_amount']);

            // 3. Create Sale
            $sale = Sale::create([
                'tenant_id' => $tenantId,
                'student_id' => $data['student_id'],
                'total_amount' => $totalAmount,
                'paid_amount' => $data['paid_amount'],
                'status' => $status,
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'] ?? null,
            ]);

            // 4. Create Sale Items
            $student = Student::find($data['student_id']);
            foreach ($itemsToCreate as $itemData) {
                $itemData['sale_id'] = $sale->id;
                SaleItem::create($itemData);

                if ($itemData['item_type'] === Course::class && $student) {
                    $course = Course::find($itemData['item_id']);
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
