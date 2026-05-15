<?php

namespace App\Services\PaymentGateways;

use App\Interfaces\PaymentGatewayInterface;
use App\Models\Tenant;
use App\Models\Package;
use Illuminate\Support\Str;

class MockGateway implements PaymentGatewayInterface
{
    use HandlesDemoPayments;

    /**
     * Simply redirects to the demo success page to simulate a successful payment.
     */
    public function createCheckoutSession(Tenant $tenant, Package $package, string $billingCycle, array $options = []): string
    {
        return $this->handleDemoRedirect($tenant, $package, $billingCycle, $options);
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
