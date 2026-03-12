<?php

namespace App\Services;

use App\Interfaces\PaymentGatewayInterface;
use App\Services\PaymentGateways\StripeGateway;
use App\Services\PaymentGateways\PayPalGateway;
use Exception;

class PaymentFactory
{
    /**
     * Get the payment gateway driver.
     * 
     * @param string $gateway
     * @return PaymentGatewayInterface
     * @throws Exception
     */
    public static function make(string $gateway): PaymentGatewayInterface
    {
        switch (strtolower($gateway)) {
            case 'stripe':
                return new \App\Services\PaymentGateways\StripeGateway();
            case 'paypal':
                return new \App\Services\PaymentGateways\PayPalGateway(app(PayPalService::class));
            case 'test':
            case 'mock':
                return new \App\Services\PaymentGateways\MockGateway();
            default:
                throw new Exception("Payment gateway [{$gateway}] is not supported.");
        }
    }
}
