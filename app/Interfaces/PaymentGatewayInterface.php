<?php

namespace App\Interfaces;

use App\Models\Package;
use App\Models\Tenant;

interface PaymentGatewayInterface
{
    /**
     * Create a checkout session/order and return the redirect URL.
     */
    public function createCheckoutSession(Tenant $tenant, Package $package, string $billingCycle, array $options = []): string;

    /**
     * Handle the response/webhook after a payment attempt.
     *
     * @return array ['success' => bool, 'transaction_id' => string, 'details' => array]
     */
    public function handleCallback(array $payload): array;

    /**
     * Get the gateway name.
     */
    public function getName(): string;
}
