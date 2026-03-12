<?php

namespace App\Services\PaymentGateways;

use App\Interfaces\PaymentGatewayInterface;
use App\Models\Tenant;
use App\Models\Package;
use App\Services\PayPalService;

class PayPalGateway implements PaymentGatewayInterface
{
    protected $paypal;

    public function __construct(PayPalService $paypal)
    {
        $this->paypal = $paypal;
    }

    public function createCheckoutSession(Tenant $tenant, Package $package, string $billingCycle, array $options = []): string
    {
        // PayPal support for USD only (Simplified)
        $currency = 'USD';
        $amount = $package->regional_prices['default']['amount'] ?? 49;

        if ($billingCycle === 'yearly') {
            $amount = $package->regional_prices['default']['yearly_price'] ?? ($amount * 2);
        }

        $resp = $this->paypal->createOrder(
            $amount, 
            $currency, 
            route('payment.paypal.success'), 
            $options['cancel_url'] ?? route('payment.cancel')
        );

        if ($resp && isset($resp['links'])) {
            $approveLink = collect($resp['links'])->where('rel', 'approve')->first()['href'];

            // Note: Registration and Subscription logic will handle the local DB state
            // based on session data in the controller for now to remain compatible.
            session([
                'paypal_order_id' => $resp['id'],
                'selected_plan' => $package->slug,
                'billing_cycle' => $billingCycle,
                'tenant_id' => $tenant->id,
            ]);

            return $approveLink;
        }

        throw new \Exception('Failed to create PayPal order.');
    }

    public function handleCallback(array $payload): array
    {
        $orderId = $payload['token'] ?? null;
        if (!$orderId) return ['success' => false];

        $details = $this->paypal->captureOrder($orderId);
        return [
            'success' => ($details && $details['status'] === 'COMPLETED'),
            'transaction_id' => $orderId,
            'details' => $details
        ];
    }

    public function getName(): string
    {
        return 'paypal';
    }
}
