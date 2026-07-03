<?php

namespace App\Services\PaymentGateways;

use App\Interfaces\PaymentGatewayInterface;
use App\Models\Package;
use App\Models\Tenant;
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
        // PayPal support for USD only
        $currency = 'USD';

        // Base USD price from regional prices (defaulting to package defaults)
        $baseUsdPrice = $package->regional_prices['default']['amount'] ?? 49;
        if ($billingCycle === 'yearly') {
            $baseUsdPrice = $package->regional_prices['default']['yearly_price'] ?? ($baseUsdPrice * 10);
        } elseif ($billingCycle === 'term') {
            $baseUsdPrice = $package->regional_prices['default']['term_price'] ?? ($baseUsdPrice * 4);
        }

        // Apply proportional discount if provided in options
        $amount = $baseUsdPrice;
        if (isset($options['base_price']) && $options['base_price'] > 0 && isset($options['discount_amount'])) {
            $discountRatio = $options['discount_amount'] / $options['base_price'];
            $amount = $baseUsdPrice * (1 - $discountRatio);
        }

        // Ensure amount is at least 0 and formatted for PayPal (2 decimal places)
        $amount = number_format(max(0, $amount), 2, '.', '');

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
        if (! $orderId) {
            return ['success' => false];
        }

        $details = $this->paypal->captureOrder($orderId);

        return [
            'success' => ($details && $details['status'] === 'COMPLETED'),
            'transaction_id' => $orderId,
            'details' => $details,
        ];
    }

    public function getName(): string
    {
        return 'paypal';
    }
}
