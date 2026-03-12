<?php

namespace App\Interfaces;

use App\Models\Tenant;
use App\Models\Package;

interface PaymentGatewayInterface
{
    /**
     * Create a checkout session/order and return the redirect URL.
     * 
     * @param Tenant $tenant
     * @param Package $package
     * @param string $billingCycle
     * @param array $options
     * @return string
     */
    public function createCheckoutSession(Tenant $tenant, Package $package, string $billingCycle, array $options = []): string;

    /**
     * Handle the response/webhook after a payment attempt.
     * 
     * @param array $payload
     * @return array ['success' => bool, 'transaction_id' => string, 'details' => array]
     */
    public function handleCallback(array $payload): array;

    /**
     * Get the gateway name.
     * 
     * @return string
     */
    public function getName(): string;
}
