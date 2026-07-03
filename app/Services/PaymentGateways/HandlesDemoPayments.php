<?php

namespace App\Services\PaymentGateways;

use App\Models\Package;
use App\Models\Tenant;

trait HandlesDemoPayments
{
    /**
     * Demo payments activate a real subscription without charging anyone,
     * so they must never be reachable in production unless explicitly
     * enabled via STRIPE_DEMO_MODE.
     */
    public static function demoPaymentsAllowed(): bool
    {
        return (bool) config('services.stripe.demo_mode')
            || app()->environment('local', 'testing');
    }

    /**
     * Store mock payment data in session and redirect to the demo payment page.
     */
    protected function handleDemoRedirect(Tenant $tenant, Package $package, string $billingCycle, array $options = []): string
    {
        if (! static::demoPaymentsAllowed()) {
            throw new \RuntimeException('Demo payments are disabled in this environment.');
        }

        $cyclePrice = $billingCycle === 'yearly' ? $package->yearly_price : $package->price;

        session([
            'tenant_id' => $tenant->id,
            'selected_plan' => $package->slug,
            'billing_cycle' => $billingCycle,
            'base_price' => $cyclePrice,
            'total_amount' => $options['total_amount'] ?? $cyclePrice,
            'applied_coupon_id' => $options['coupon_id'] ?? null,
            'applied_coupon_code' => $options['coupon_code'] ?? null,
            'discount_amount' => $options['discount_amount'] ?? 0,
            'registration_hmac' => hash_hmac('sha256', $tenant->id.'|'.(auth()->id() ?? 'guest'), config('app.key')),
            'is_subscription_change' => ! empty($options['is_upgrade']),
            'is_mock_payment' => true,
        ]);

        return route('payment.demo');
    }
}
