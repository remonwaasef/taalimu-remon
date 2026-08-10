<?php

namespace App\Services;

use App\Models\Package;
use App\Models\SiteSetting;
use App\Models\SubscriptionLog;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
        $days = $this->calculateSubscriptionDays($billingCycle, $package);
        $existingSub = $tenant->subscriptions()->where('name', 'default')->first();
        $operationType = $existingSub ? 'upgrade' : 'subscription';

        $subscriptionData = [
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
            'ends_at' => now()->addDays($days),
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
            now(),
            now()->addDays($days)
        );
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
     * استعادة سياق الدفع من merchant_order_id (لـ Paymob عندما ينتهي الـ session).
     * PAY-2: القراءة من خريطة online_checkouts المربوطة برقم أمر Paymob الموقّع
     * (وليس من النص غير الموقّع نفسه).
     */
    public function restoreContextFromMerchantOrder(string $merchantOrderId): ?array
    {
        if (! str_starts_with($merchantOrderId, 'tx_')) {
            return null;
        }

        $checkout = \App\Models\OnlineCheckout::where('merchant_order_id', $merchantOrderId)
            ->first();

        if (! $checkout) {
            Log::warning("Payment context NOT restored (no checkout row) for merchant_order_id: {$merchantOrderId}");

            return null;
        }

        Log::info("Payment context restored from verified checkout row for merchant_order_id: {$merchantOrderId}");

        return [
            'tenant_id' => $checkout->tenant_id,
            'plan_slug' => $checkout->package_slug,
            'billing_cycle' => $checkout->billing_cycle,
            'is_change' => (bool) $checkout->is_change,
        ];
    }
}
