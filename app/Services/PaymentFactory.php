<?php

namespace App\Services;

use App\Interfaces\PaymentGatewayInterface;
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
            case 'paypal':
                return new \App\Services\PaymentGateways\PayPalGateway(app(PayPalService::class));
            case 'paymob':
                return new \App\Services\PaymentGateways\PaymobGateway();
            case 'test':
            case 'mock':
                return new \App\Services\PaymentGateways\MockGateway();
            default:
                throw new Exception("Payment gateway [{$gateway}] is not supported.");
        }
    }
}
