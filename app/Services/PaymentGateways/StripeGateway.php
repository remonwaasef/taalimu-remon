<?php

namespace App\Services\PaymentGateways;

use App\Interfaces\PaymentGatewayInterface;
use App\Models\Tenant;
use App\Models\Package;
use Illuminate\Support\Str;

class StripeGateway implements PaymentGatewayInterface
{
    public function createCheckoutSession(Tenant $tenant, Package $package, string $billingCycle, array $options = []): string
    {
        $priceId = $package->stripe_price_id;
        
        // Check if price ID looks like a real Stripe price (starts with 'price_1')
        $isRealPriceId = $priceId && str_starts_with($priceId, 'price_1');
        
        // Handle Demo Mode: explicit demo flag, missing keys, or placeholder price IDs
        if (config('services.stripe.demo_mode') || empty(config('services.stripe.secret')) || !$isRealPriceId) {
            \Log::info("Stripe using Demo Mode", [
                'reason' => !$isRealPriceId ? "Price ID '{$priceId}' is not a real Stripe price" : 'Demo mode enabled',
                'package' => $package->slug,
            ]);
            return $this->handleDemoRedirect($tenant, $package, $billingCycle, $options);
        }

        try {
            $tenant->createOrGetStripeCustomer([
                'name' => $tenant->name,
                'email' => $tenant->email,
            ]);

            $checkoutOptions = [
                'success_url' => ($options['success_url'] ?? route('payment.success')) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $options['cancel_url'] ?? route('payment.cancel'),
            ];

            // Regional Pricing Support
            if (!empty($options['line_items'])) {
                $checkoutOptions['line_items'] = $options['line_items'];
                return $tenant->checkout(null, $checkoutOptions)->url;
            }

            return $tenant->newSubscription('default', $priceId)
                ->checkout($checkoutOptions)
                ->url;
        } catch (\Exception $e) {
            \Log::warning("Stripe checkout failed, falling back to Demo Mode: " . $e->getMessage());
            return $this->handleDemoRedirect($tenant, $package, $billingCycle, $options);
        }
    }

    protected function handleDemoRedirect($tenant, $package, $billingCycle, $options)
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
        ]);

        return route('payment.demo');
    }

    public function handleCallback(array $payload): array
    {
        // Stripe actual verification happens in PaymentController@success 
        // using \Stripe\Checkout\Session::retrieve($sessionId)
        return ['success' => true];
    }

    public function getName(): string
    {
        return 'stripe';
    }
}
