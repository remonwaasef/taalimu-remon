<?php

namespace App\Services;

use App\Models\Package;
use App\Models\SiteSetting;
use App\Models\Subscription;
use App\Models\SubscriptionLog;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * خدمة مشتركة لمعالجة نتائج الدفع بعد تأكيد أي بوابة.
 * تُوحّد منطق إنشاء/تحديث الاشتراكات ومعالجة الكوبونات
 * لتجنب تكرار الكود بين بوابات الدفع المختلفة.
 */
class PaymentProcessingService
{
    /**
     * حساب مدة الاشتراك بالأيام حسب دورة الفوترة.
     */
    public function calculateSubscriptionDays(string $billingCycle, ?Package $package = null): int
    {
        return match ($billingCycle) {
            'monthly' => 30,
            'term' => (int) SiteSetting::get('term_duration_days', 150),
            'yearly' => 365,
            default => $package?->duration_in_days ?? 30,
        };
    }

    /**
     * إنشاء أو تحديث اشتراك للمستأجر بعد نجاح الدفع.
     *
     * Idempotent: إذا كانت نفس معاملة البوابة مطبقة مسبقاً (SubscriptionLog
     * بنفس gateway+transaction_id) تُتجاهل العملية بالكامل. قفل الصف
     * (lockForUpdate) يمنع تنفيذين متوازيين من تمديد الاشتراك مرتين،
     * و ends_at يُمدَّد من نهاية الاشتراك الحالي وليس من الآن().
     */
    public function activateSubscription(
        Tenant $tenant,
        string $gateway,
        string $transactionId,
        ?Package $package,
        string $billingCycle,
        float $basePrice,
        float $totalAmount,
        array $extraData = []
    ): void {
        DB::transaction(function () use ($tenant, $gateway, $transactionId, $package, $billingCycle, $basePrice, $totalAmount, $extraData) {
            $days = $this->calculateSubscriptionDays($billingCycle, $package);

            // PAY-4: Replay guard — the same gateway transaction must not be
            // applied twice (webhook retries, redirect refreshes, double callbacks).
            if ($transactionId !== '') {
                $alreadyApplied = SubscriptionLog::query()
                    ->where('tenant_id', $tenant->id)
                    ->where('gateway', $gateway)
                    ->where('transaction_id', $transactionId)
                    ->exists();

                if ($alreadyApplied) {
                    Log::info('activateSubscription skipped: transaction already applied', [
                        'tenant_id' => $tenant->id,
                        'gateway' => $gateway,
                        'transaction_id' => $transactionId,
                    ]);

                    return;
                }
            }

            $existingSub = Subscription::query()
                ->where('tenant_id', $tenant->id)
                ->where('name', 'default')
                ->lockForUpdate()
                ->first();

            $operationType = $existingSub ? 'upgrade' : 'subscription';

            // Extend from the current period end (never reset to now()).
            $startsAt = ($existingSub && $existingSub->ends_at && $existingSub->ends_at->isFuture())
                ? $existingSub->ends_at->copy()
                : now();
            $endsAt = $startsAt->copy()->addDays($days);

            $subscriptionData = [
                'package_id' => $package?->id,
                'gateway' => $gateway,
                'stripe_id' => "sub_{$gateway}_".($transactionId ?: Str::random(10)),
                'stripe_status' => 'active',
                'stripe_price' => "price_{$gateway}_".($package->slug ?? 'unknown'),
                'quantity' => 1,
                'billing_cycle' => $billingCycle,
                'base_price' => $basePrice,
                'total_amount' => $totalAmount,
                'discount_amount' => $extraData['discount_amount'] ?? 0,
                'status' => 'active',
                'ends_at' => $endsAt,
            ];

            // إضافة حقول خاصة بالبوابة (مثل paypal_id, paypal_status)
            $subscriptionData = array_merge($subscriptionData, $extraData);

            $tenant->subscriptions()->updateOrCreate(
                ['name' => 'default'],
                $subscriptionData
            );

            // تسجيل العملية في السجل
            SubscriptionLog::logOperation(
                $tenant->id,
                $operationType,
                $package->slug ?? 'unknown',
                $package->name ?? __('services.custom_plan'),
                $billingCycle,
                $gateway,
                $totalAmount,
                $transactionId,
                $startsAt,
                $endsAt
            );
        });
    }

    /**
     * تسجيل دخول المستخدم المدير وإرسال إشعار Telegram.
     */
    public function loginAdminAndNotify(Tenant $tenant, TelegramService $telegram, string $paymentLabel): ?User
    {
        $user = User::where('tenant_id', $tenant->id)
            ->whereIn('role', ['center_admin', 'instructor'])
            ->first();

        $telegram->sendRegistrationAlert($tenant, $user, $paymentLabel);

        if (! Auth::check() && $user) {
            Auth::login($user, true);
        }

        return $user;
    }

    /**
     * معالجة استخدام الكوبون بعد الدفع الناجح.
     */
    public function processCoupon(?int $couponId, Tenant $tenant, float $discountAmount): void
    {
        if (! $couponId) {
            return;
        }

        $coupon = \App\Models\Coupon::find($couponId);
        if ($coupon) {
            $coupon->incrementUsage();
            try {
                app(TelegramService::class)->sendCouponAlert($tenant, $coupon, $discountAmount);
            } catch (\Throwable $e) {
                Log::warning('Failed to send coupon alert: '.$e->getMessage());
            }
        }
    }

    /**
     * استعادة سياق الدفع من رقم أمر Paymob المغطى بـ HMAC (لـ Paymob عندما ينتهي الـ session).
     * SEC-PAY-1: الـ merchant_order_id غير موقّع في redirect HMAC (انظر
     * PaymobGateway::verifyRedirectHmac) ويمكن للمهاجم تغييره، لذلك يجب أن تكون
     * الاستعادة من خريطة online_checkouts المفتاحية بـ paymob_order_id — نفس
     * المصدر الموثوق الذي يعتمده الـ webhook.
     */
    public function restoreContextFromPaymobOrder(?string $paymobOrderId): ?array
    {
        if (! $paymobOrderId) {
            return null;
        }

        $checkout = \App\Models\OnlineCheckout::where('paymob_order_id', $paymobOrderId)
            ->first();

        if (! $checkout) {
            Log::warning("Payment context NOT restored (no checkout row) for paymob order id: {$paymobOrderId}");

            return null;
        }

        Log::info("Payment context restored from verified checkout row for paymob order id: {$paymobOrderId}");

        return [
            'tenant_id' => $checkout->tenant_id,
            'plan_slug' => $checkout->package_slug,
            'billing_cycle' => $checkout->billing_cycle,
            'is_change' => (bool) $checkout->is_change,
            'total_amount' => $checkout->amount_cents / 100,
        ];
    }
}
