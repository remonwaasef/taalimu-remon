<?php

namespace App\Services\PaymentGateways;

use App\Interfaces\PaymentGatewayInterface;
use App\Models\Package;
use App\Models\Tenant;

class StripeGateway implements PaymentGatewayInterface
{
    use HandlesDemoPayments;

    public function createCheckoutSession(Tenant $tenant, Package $package, string $billingCycle, array $options = []): string
    {
        $priceId = $package->stripe_price_id;

        // Check if price ID looks like a real Stripe price (starts with 'price_1')
        $isRealPriceId = $priceId && str_starts_with($priceId, 'price_1');

        $isConfigured = ! empty(config('services.stripe.secret')) && $isRealPriceId;

        // Demo Mode is only reachable when explicitly enabled or outside production.
        // A misconfigured production Stripe must fail closed, never grant a free subscription.
        if (! $isConfigured || config('services.stripe.demo_mode')) {
            if (! static::demoPaymentsAllowed()) {
                \Log::error('Stripe misconfigured in production', [
                    'reason' => ! $isRealPriceId ? "Price ID '{$priceId}' is not a real Stripe price" : 'Missing Stripe secret',
                    'package' => $package->slug,
                ]);

                throw new \RuntimeException('Stripe payment gateway is not configured.');
            }

            \Log::info('Stripe using Demo Mode', [
                'reason' => ! $isRealPriceId ? "Price ID '{$priceId}' is not a real Stripe price" : 'Demo mode enabled',
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
                'success_url' => ($options['success_url'] ?? route('payment.success')).'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $options['cancel_url'] ?? route('payment.cancel'),
            ];

            // Regional Pricing Support
            if (! empty($options['line_items'])) {
                $checkoutOptions['line_items'] = $options['line_items'];

                return $tenant->checkout(null, $checkoutOptions)->url;
            }

            return $tenant->newSubscription('default', $priceId)
                ->checkout($checkoutOptions)
                ->url;
        } catch (\Exception $e) {
            // Fail closed: a Stripe outage or bad request must surface as an error,
            // not silently activate the subscription via the demo flow.
            if (! static::demoPaymentsAllowed()) {
                \Log::error('Stripe checkout failed: '.$e->getMessage());

                throw $e;
            }

            \Log::warning('Stripe checkout failed, falling back to Demo Mode: '.$e->getMessage());

            return $this->handleDemoRedirect($tenant, $package, $billingCycle, $options);
        }
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
