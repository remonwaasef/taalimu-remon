<?php

namespace App\Services;

use App\Interfaces\PaymentGatewayInterface;
use Exception;

class PaymentFactory
{
    /**
     * Get the payment gateway driver.
     *
     * @throws Exception
     */
    public static function make(string $gateway): PaymentGatewayInterface
    {
        switch (strtolower($gateway)) {
            case 'paypal':
                return new \App\Services\PaymentGateways\PayPalGateway(app(PayPalService::class));
            case 'paymob':
                return new \App\Services\PaymentGateways\PaymobGateway;
            case 'test':
            case 'mock':
                // The mock gateway activates subscriptions without payment. The gateway
                // name comes from user input, so it must be rejected outright unless
                // demo payments are explicitly allowed in this environment.
                if (! \App\Services\PaymentGateways\MockGateway::demoPaymentsAllowed()) {
                    throw new Exception("Payment gateway [{$gateway}] is not supported.");
                }

                return new \App\Services\PaymentGateways\MockGateway;
            default:
                throw new Exception("Payment gateway [{$gateway}] is not supported.");
        }
    }
}
