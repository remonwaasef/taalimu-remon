<?php

namespace App\Services\PaymentGateways;

use App\Interfaces\PaymentGatewayInterface;
use App\Models\Tenant;
use App\Models\Package;
use Illuminate\Support\Str;

class MockGateway implements PaymentGatewayInterface
{
    /**
     * Simply redirects to the demo success page to simulate a successful payment.
     */
    public function createCheckoutSession(Tenant $tenant, Package $package, string $billingCycle, array $options = []): string
    {
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
            'registration_hmac' => hash_hmac('sha256', $tenant->id . '|' . (auth()->id() ?? 'guest'), config('app.key')),
            'is_subscription_change' => !empty($options['is_upgrade']),
            'is_mock_payment' => true,
        ]);

        return route('payment.demo');
    }

    public function handleCallback(array $payload): array
    {
        return ['success' => true, 'transaction_id' => 'mock_' . Str::random(10)];
    }

    public function getName(): string
    {
        return 'test';
    }
}
