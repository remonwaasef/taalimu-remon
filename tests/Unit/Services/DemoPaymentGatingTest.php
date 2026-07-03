<?php

namespace Tests\Unit\Services;

use App\Services\PaymentFactory;
use App\Services\PaymentGateways\MockGateway;
use Tests\TestCase;

/**
 * Demo/mock payments activate a real subscription without charging, so they must
 * be reachable only when explicitly enabled. These tests lock that gate down.
 */
class DemoPaymentGatingTest extends TestCase
{
    public function test_demo_payments_disallowed_in_production_without_flag()
    {
        app()['env'] = 'production';
        config()->set('services.stripe.demo_mode', false);

        $this->assertFalse(MockGateway::demoPaymentsAllowed());
    }

    public function test_demo_payments_allowed_when_flag_enabled()
    {
        app()['env'] = 'production';
        config()->set('services.stripe.demo_mode', true);

        $this->assertTrue(MockGateway::demoPaymentsAllowed());
    }

    public function test_demo_payments_allowed_in_local_environment()
    {
        app()['env'] = 'local';
        config()->set('services.stripe.demo_mode', false);

        $this->assertTrue(MockGateway::demoPaymentsAllowed());
    }

    public function test_factory_rejects_mock_gateway_in_production()
    {
        app()['env'] = 'production';
        config()->set('services.stripe.demo_mode', false);

        $this->expectException(\Exception::class);
        PaymentFactory::make('test');
    }

    public function test_factory_allows_mock_gateway_when_demo_enabled()
    {
        app()['env'] = 'production';
        config()->set('services.stripe.demo_mode', true);

        $this->assertInstanceOf(MockGateway::class, PaymentFactory::make('test'));
    }
}
