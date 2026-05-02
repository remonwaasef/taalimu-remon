<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Course;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Commission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\NotifPaymentConfirmedMail;
use App\Traits\HasLocaleResolution;


class FinanceService
{
    use HasLocaleResolution;
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

                // Notifications
                if ($student) {
                    $this->notifyPayment(app('tenant'), $student, $data['paid_amount'], $totalAmount - $data['paid_amount'], $data['payment_method'] ?? 'cash');
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

            // Notifications
            $this->notifyPayment(app('tenant'), $sale->student, $amount, $sale->total_amount - $newPaidAmount, $method ?? $sale->payment_method);

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

    /**
     * Trigger both WhatsApp and Email notifications for a payment.
     */
    public function notifyPayment($tenant, $student, $amount, $balance, $method = 'cash')
    {
        // WhatsApp Notification
        $this->whatsappService->sendPaymentNotification($tenant, $student, $amount, $balance);
        
        // Email Notification
        $this->sendPaymentEmailNotification($tenant, $student, $amount, $balance, $method);
    }

    /**
     * Send payment confirmation email notification.
     */
    public function sendPaymentEmailNotification($tenant, $student, $amount, $balance, $method = 'cash')
    {
        try {
            $tenantSettings = $tenant->settings['email_templates'] ?? [];
            
            // Determine real email
            $realEmail = null;
            $studentEmail = $student->email ?? ($student->user ? $student->user->email : null);
            
            // Skip auto-generated emails
            if ($studentEmail && !preg_match('/^std\d+\..+@taalimu\.com$/', $studentEmail)) {
                $realEmail = $studentEmail;
            }

            $hasParentEmail = !empty($student->parent_email);

            if (!empty($tenantSettings['notif_payment_confirmed_enabled']) && ($realEmail || $hasParentEmail)) {
                $locale = $this->getTargetLocale($tenant, $student);
                
                $subjectKey = "notif_payment_confirmed_subject_{$locale}";
                $bodyKey = "notif_payment_confirmed_body_{$locale}";
                
                $subject = $tenantSettings[$subjectKey] ?? $tenantSettings['notif_payment_confirmed_subject'] ?? 'تأكيد استلام دفعة';
                $body = $tenantSettings[$bodyKey] ?? $tenantSettings['notif_payment_confirmed_body'] ?? '';
                
                $variables = [
                    'student_name' => $student->name, // Standardized key
                    'اسم_الطالب' => $student->name,
                    'اسم_المركز' => $tenant->name,
                    'المبلغ_المدفوع' => $amount . ' ' . ($tenant->settings['currency'] ?? 'ج.م'),
                    'تاريخ_الدفع' => now()->format('Y-m-d'),
                    'المتبقي' => max(0, $balance) . ' ' . ($tenant->settings['currency'] ?? 'ج.م'),
                    'payment_method' => $method,
                    'طريقة_الدفع' => $method,
                ];

                if ($realEmail) {
                    Mail::to($realEmail)->queue(new NotifPaymentConfirmedMail(
                        $subject, $body, $variables, $tenant->name, $student->name
                    ));
                }
                
                if ($hasParentEmail) {
                    Mail::to($student->parent_email)->queue(new NotifPaymentConfirmedMail(
                        $subject, $body, $variables, $tenant->name, $student->name
                    ));
                }
            }
        } catch (\Exception $e) {
            Log::error('FinanceService payment confirmation email failed: ' . $e->getMessage());
        }
    }
}
