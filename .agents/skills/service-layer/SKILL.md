---
name: Service Layer Pattern
description: Skill for creating professional Service Classes following the Taalimu service layer architecture with dependency injection, transactions, error handling, and tenant awareness.
---

# Service Layer Pattern — مهارة كتابة Service Class

## متى تُستخدم هذه المهارة؟
عند إنشاء Service Class جديد أو نقل Business Logic من Controller إلى Service.

---

## القاعدة الذهبية

> **Controllers تستقبل وتُرجع فقط. Services تُنفذ.**
>
> Controller = Validation + Delegation + Response
> Service = Business Logic + Database Operations + External API Calls

---

## قبل الإنشاء — تحقق من الخدمات الموجودة

| Service | الوظيفة |
|---|---|
| `FinanceService` | المبيعات، الفواتير، حسابات الطلاب المالية |
| `AttendanceService` | تسجيل الحضور، تنبيهات الأهالي |
| `SubscriptionService` | إدارة باقات SaaS، فحص الميزات والحدود |
| `CourseService` | إنشاء وإدارة الكورسات |
| `StudentService` | إنشاء وإدارة الطلاب |
| `QuizService` | إدارة الاختبارات والأسئلة |
| `WhatsAppService` | إرسال رسائل WhatsApp |
| `TelegramService` | إرسال تقارير عبر Telegram |
| `SettingsService` | إعدادات المستأجر |
| `TenantRegistrationService` | تسجيل مستأجر جديد |
| `PayoutService` | حساب عمولات المدرسين ومدفوعاتهم |
| `PaymentProcessingService` | معالجة المدفوعات |
| `RefundService` | إدارة المرتجعات |
| `ScheduleConflictService` | كشف تعارضات الجدول |
| `CertificateService` | إنشاء الشهادات |
| `OperationIssueService` | تسجيل أخطاء الإنتاج |
| `GdprService` | الامتثال لقوانين حماية البيانات |

**إذا كانت الوظيفة موجودة في Service قائم → أضف method جديد عليه بدل إنشاء Service جديد.**

---

## قالب Service Class

```php
<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NewFeatureService
{
    /**
     * Create a new resource within a tenant context.
     *
     * @param  Tenant  $tenant  The tenant owning this resource
     * @param  array   $data    Validated input data
     * @return \App\Models\NewModel
     *
     * @throws \App\Exceptions\SubscriptionLimitException
     * @throws \RuntimeException
     */
    public function create(Tenant $tenant, array $data): \App\Models\NewModel
    {
        // 1. Guard clauses — التحقق المبكر
        if (! $tenant->hasFeature('feature_code')) {
            throw new \App\Exceptions\SubscriptionLimitException(
                __('messages.feature_limit_reached')
            );
        }

        // 2. Business logic with transaction
        return DB::transaction(function () use ($tenant, $data) {
            $model = \App\Models\NewModel::create([
                'tenant_id' => $tenant->id,
                'name' => $data['name'],
                // ... باقي الحقول
            ]);

            // 3. Side effects (notifications, cache invalidation, logging)
            $this->notifyRelevantParties($model);
            $this->clearRelatedCache($tenant);

            return $model;
        });
    }

    /**
     * Update an existing resource.
     */
    public function update(\App\Models\NewModel $model, array $data): \App\Models\NewModel
    {
        return DB::transaction(function () use ($model, $data) {
            $model->update($data);
            $this->clearRelatedCache($model->tenant);

            return $model->fresh();
        });
    }

    /**
     * Delete a resource with proper cleanup.
     */
    public function delete(\App\Models\NewModel $model): bool
    {
        return DB::transaction(function () use ($model) {
            // Clean up related records
            $model->relatedItems()->delete();
            $result = $model->delete();

            $this->clearRelatedCache($model->tenant);

            return $result;
        });
    }

    /**
     * Send notifications to relevant parties.
     */
    private function notifyRelevantParties(\App\Models\NewModel $model): void
    {
        try {
            // Queue notification to avoid blocking the request
            dispatch(new \App\Jobs\SendNotificationJob($model));
        } catch (\Throwable $e) {
            // Log but don't fail the main operation
            Log::warning("Notification failed for model {$model->id}: " . $e->getMessage());
        }
    }

    /**
     * Clear cached data for this tenant.
     */
    private function clearRelatedCache(Tenant $tenant): void
    {
        \Illuminate\Support\Facades\Cache::forget("tenant_{$tenant->id}_feature_data");
    }
}
```

---

## كيفية استخدام الـ Service في Controller

```php
<?php

namespace Modules\Center\Http\Controllers;

use App\Services\NewFeatureService;
use Modules\Center\Http\Requests\StoreNewFeatureRequest;

class NewFeatureController extends CenterBaseController
{
    /**
     * Store a new resource.
     */
    public function store(StoreNewFeatureRequest $request)
    {
        $tenant = current_tenant();
        $model = app(NewFeatureService::class)->create($tenant, $request->validated());

        return redirect()
            ->route('center.new-feature.show', $model)
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Update the specified resource.
     */
    public function update(StoreNewFeatureRequest $request, \App\Models\NewModel $model)
    {
        $this->authorize('update', $model);

        app(NewFeatureService::class)->update($model, $request->validated());

        return redirect()
            ->route('center.new-feature.show', $model)
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(\App\Models\NewModel $model)
    {
        $this->authorize('delete', $model);

        app(NewFeatureService::class)->delete($model);

        return redirect()
            ->route('center.new-feature.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
```

---

## أنماط متقدمة — من Services الموجودة

### نمط FinanceService — عملية مالية معقدة:
```php
// عملية بيع مع عدة عمليات
public function createSale(Tenant $tenant, array $data): Sale
{
    return DB::transaction(function () use ($tenant, $data) {
        // 1. إنشاء البيع
        $sale = Sale::create([...]);

        // 2. إنشاء بنود البيع
        foreach ($data['items'] as $item) {
            SaleItem::create([...]);
        }

        // 3. تحديث رصيد الطالب
        $this->updateStudentBalance($sale->student_id);

        // 4. حساب عمولة المدرس
        app(PayoutService::class)->calculateCommission($sale);

        return $sale;
    });
}
```

### نمط AttendanceService — عملية مع إشعار خارجي:
```php
public function markAttendance(array $data): Attendance
{
    $attendance = DB::transaction(function () use ($data) {
        return Attendance::updateOrCreate(
            ['student_id' => $data['student_id'], 'schedule_id' => $data['schedule_id'], 'date' => $data['date']],
            ['status' => $data['status'], 'late_minutes' => $data['late_minutes'] ?? null]
        );
    });

    // إشعار خارجي — خارج الـ transaction لأنه قابل للفشل
    try {
        app(WhatsAppService::class)->sendAttendanceAlert($attendance);
    } catch (\Throwable $e) {
        Log::warning("WhatsApp alert failed: " . $e->getMessage());
    }

    return $attendance;
}
```

---

## قائمة التحقق

- [ ] الـ Service في `app/Services/` (وليس في الـ Module)
- [ ] Constructor Injection للـ Dependencies
- [ ] `DB::transaction()` للعمليات متعددة الجداول
- [ ] Guard clauses في بداية كل method
- [ ] Error handling للعمليات الخارجية (APIs, notifications)
- [ ] Cache invalidation بعد التعديل
- [ ] Tenant-aware — يتضمن `tenant_id` في كل query
- [ ] DocBlocks واضحة مع `@param`, `@return`, `@throws`
- [ ] تم تحديث `docs/14_SERVICES.md`
