<?php

namespace Tests;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Tests render full pages whose layouts use @vite. Disable Vite so the
        // suite doesn't depend on a compiled public/build/manifest.json (which
        // isn't produced in CI unless the frontend is built).
        $this->withoutVite();
    }

    /**
     * Create a test tenant with sensible defaults.
     * Centralizes tenant setup to avoid repeating onboarding_status in every test.
     *
     * @param  array  $attributes  Override any default attributes
     */
    protected function createTenant(array $attributes = []): Tenant
    {
        $defaults = [
            'domain' => null,
            'name' => 'Test Center',
            'onboarding_status' => 'completed',
        ];

        return Tenant::create(array_merge($defaults, $attributes));
    }
}
