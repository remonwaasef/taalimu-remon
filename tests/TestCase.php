<?php

namespace Tests;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Create a test tenant with sensible defaults.
     * Centralizes tenant setup to avoid repeating onboarding_status in every test.
     *
     * @param  array  $attributes  Override any default attributes
     */
    protected function createTenant(array $attributes = []): Tenant
    {
        $defaults = [
            'domain' => 'test',
            'name' => 'Test Center',
            'onboarding_status' => 'completed',
        ];

        return Tenant::create(array_merge($defaults, $attributes));
    }
}
