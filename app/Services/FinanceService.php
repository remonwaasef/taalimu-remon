<?php

namespace App\Services;

use App\Models\Commission;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Student;
use App\Services\Student\StudentNotificationService;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    protected $courseService;

    protected $whatsappService;

    protected $studentNotificationService;

    public function __construct(
        CourseService $courseService,
        WhatsAppService $whatsappService,
        StudentNotificationService $studentNotificationService
    ) {
        $this->courseService = $courseService;
        $this->whatsappService = $whatsappService;
        $this->studentNotificationService = $studentNotificationService;
    }

    public function createSale(array $data, bool $useTransaction = true)
    {
        $closure = function () use ($data) {
            $tenantId = \Modules\Tenancy\Services\TenantResolver::get()->id;
            $student = Student::with('user')->find($data['student_id']);

            $courseIds = collect($data['items'])->pluck('id')->toArray();
            $courses = Course::with('instructor')->whereIn('id', $courseIds)
                ->where('tenant_id', $tenantId)
                ->get()
                ->keyBy('id');

            $subtotalAmount = 0;
            $itemsToCreate = [];

            foreach ($data['items'] as $item) {
                $course = $courses->get($item['id']);
                if (! $course) {
                    throw new \Exception('Course not found: '.$item['id']);
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

            $courseIdsToCheck = [];
            foreach ($data['items'] as $item) {
                if (($item['type'] ?? Course::class) === Course::class) {
                    $courseIdsToCheck[] = $item['id'];
                }
            }
            if (! empty($courseIdsToCheck)) {
                $enrolledCourseIds = Enrollment::where('user_id', $student->user_id)
                    ->whereIn('course_id', $courseIdsToCheck)
                    ->pluck('course_id')
                    ->toArray();

                if (! empty($enrolledCourseIds)) {
                    $firstEnrolledCourse = $courses->get($enrolledCourseIds[0]);
                    throw new \Exception('الطالب مسجل بالفعل في: '.($firstEnrolledCourse ? $firstEnrolledCourse->title : ''));
                }
            }

            $status = $this->determineStatus($totalAmount, $data['paid_amount']);

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

            $now = now();
            $saleItemsData = [];
            $enrollmentsData = [];

            foreach ($itemsToCreate as $itemData) {
                $itemData['sale_id'] = $sale->id;
                $itemData['created_at'] = $now;
                $itemData['updated_at'] = $now;
                $saleItemsData[] = $itemData;

                if ($itemData['item_type'] === Course::class && $student && $student->user_id) {
                    $course = $courses->get($itemData['item_id']);
                    if ($course) {
                        $enrollmentsData[] = [
                            'tenant_id' => $sale->tenant_id,
                            'user_id' => $student->user_id,
                            'course_id' => $course->id,
                            'enrolled_at' => $now,
                            'status' => 'active',
                            'progress' => 0,
                            'remaining_sessions' => $course->sessions_count ?? 0,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
            }

            if (! empty($saleItemsData)) {
                SaleItem::insert($saleItemsData);
            }
            if (! empty($enrollmentsData)) {
                Enrollment::insert($enrollmentsData);
            }

            $insertedSaleItems = SaleItem::where('sale_id', $sale->id)
                ->where('item_type', Course::class)
                ->with('course.instructor')
                ->get()
                ->keyBy('item_id');

            $commissionsData = [];
            foreach ($itemsToCreate as $itemData) {
                if ($itemData['item_type'] === Course::class) {
                    $course = $courses->get($itemData['item_id']);
                    if ($course && $course->instructor && $course->instructor->commission_rate > 0) {
                        $rate = $course->instructor->commission_rate;
                        $type = $course->instructor->commission_type ?? 'percentage';
                        $amount = ($type === 'percentage') ? ($itemData['price'] * $rate) / 100 : $rate;

                        $saleItem = $insertedSaleItems->get($itemData['item_id']);

                        if ($saleItem) {
                            $commissionsData[] = [
                                'tenant_id' => $tenantId,
                                'instructor_id' => $course->instructor_id,
                                'sale_id' => $sale->id,
                                'sale_item_id' => $saleItem->id,
                                'amount' => $amount,
                                'rate' => $rate,
                                'status' => 'earned',
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                        }
                    }
                }
            }

            if (! empty($commissionsData)) {
                Commission::insert($commissionsData);
            }

            $enrolledCourseIds = [];
            foreach ($itemsToCreate as $itemData) {
                if ($itemData['item_type'] === Course::class) {
                    $enrolledCourseIds[] = $itemData['item_id'];
                }
            }
            if (! empty($enrolledCourseIds) && $student) {
                DB::afterCommit(function () use ($student, $enrolledCourseIds) {
                    $this->studentNotificationService->sendGroupEnrollmentEmails($student, $enrolledCourseIds);
                });
            }

            if ($data['paid_amount'] > 0) {
                Payment::create([
                    'tenant_id' => $tenantId,
                    'sale_id' => $sale->id,
                    'amount' => $data['paid_amount'],
                    'payment_method' => $data['payment_method'],
                    'received_by' => auth()->id(),
                    'paid_at' => now(),
                    'notes' => 'دفعة أولى عند إنشاء الفاتورة',
                ]);

                if ($student) {
                    $tenant = \Modules\Tenancy\Services\TenantResolver::get();
                    $callback = function () use ($tenant, $student, $data, $totalAmount) {
                        $this->notifyPayment($tenant, $student, $data['paid_amount'], $totalAmount - $data['paid_amount'], $data['payment_method'] ?? 'cash');
                    };
                    if ($useTransaction) {
                        DB::afterCommit($callback);
                    } else {
                        $callback();
                    }
                }
            }

            return $sale;
        };

        return $useTransaction ? DB::transaction($closure) : $closure();
    }

    public function addPayment(Sale $sale, $amount, $method = null, $notes = null)
    {
        return DB::transaction(function () use ($sale, $amount, $method, $notes) {
            $sale = Sale::lockForUpdate()->findOrFail($sale->id);

            $newPaidAmount = $sale->paid_amount + $amount;
            $status = $this->determineStatus($sale->total_amount, $newPaidAmount);

            $sale->update([
                'paid_amount' => $newPaidAmount,
                'status' => $status,
            ]);

            Payment::create([
                'tenant_id' => $sale->tenant_id,
                'sale_id' => $sale->id,
                'amount' => $amount,
                'payment_method' => $method ?? $sale->payment_method,
                'received_by' => auth()->id(),
                'paid_at' => now(),
                'notes' => $notes ?? 'سداد دفعة مالية',
            ]);

            $sale->loadMissing('student.user');

            $tenant = \Modules\Tenancy\Services\TenantResolver::get();
            $student = $sale->student;
            $totalAmount = $sale->total_amount;
            DB::afterCommit(function () use ($tenant, $student, $amount, $totalAmount, $newPaidAmount, $method, $sale) {
                $this->notifyPayment($tenant, $student, $amount, $totalAmount - $newPaidAmount, $method ?? $sale->payment_method);
            });

            return $sale;
        });
    }

    protected function determineStatus($total, $paid)
    {
        if ($paid >= $total) {
            return 'paid';
        } elseif ($paid > 0) {
            return 'partial';
        }

        return 'pending';
    }

    public function notifyPayment($tenant, $student, $amount, $balance, $method = 'cash')
    {
        \App\Jobs\SendWhatsAppPaymentNotification::dispatch($tenant, $student, $amount, $balance)->onQueue('whatsapp');
        $this->studentNotificationService->sendPaymentConfirmationEmail($tenant, $student, $amount, $balance, $method);
    }

    public function distributePayment($unpaidSales, float $amount, ?string $notes = null): void
    {
        $amountToDistribute = $amount;

        foreach ($unpaidSales as $sale) {
            if ($amountToDistribute <= 0) {
                break;
            }

            $remainingOnSale = $sale->total_amount - $sale->paid_amount;
            $payAmount = min($remainingOnSale, $amountToDistribute);

            $this->addPayment($sale, $payAmount, 'cash', $notes);

            $amountToDistribute -= $payAmount;
        }
    }
}
