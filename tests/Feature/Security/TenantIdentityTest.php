<?php

namespace Tests\Feature\Security;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantIdentityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_access_another_tenants_dashboard()
    {
        $tenantA = $this->createTenant(['domain' => 'tenant-a']);
        $tenantB = $this->createTenant(['domain' => 'tenant-b']);

        $userA = User::factory()->create([
            'email' => 'admin@tenant-a.com',
            'tenant_id' => $tenantA->id,
            'role' => 'center_admin',
        ]);

        // Request tenant B's area while authenticated as a user of tenant A
        app()->instance('tenant', $tenantB);

        $this->actingAs($userA);

        $response = $this->get(route('center.dashboard', ['tenant' => $tenantB->domain]));

        $response->assertForbidden();
    }

    public function test_user_can_access_own_tenants_dashboard()
    {
        $tenant = $this->createTenant(['domain' => 'tenant-c']);

        $user = User::factory()->create([
            'email' => 'admin@tenant-c.com',
            'tenant_id' => $tenant->id,
            'role' => 'center_admin',
        ]);

        app()->instance('tenant', $tenant);

        $this->actingAs($user);

        $response = $this->get(route('center.dashboard', ['tenant' => $tenant->domain]));

        $response->assertStatus(200);
    }

    public function test_global_admin_cannot_access_tenant_area()
    {
        $tenant = $this->createTenant(['domain' => 'tenant-d']);

        $globalAdmin = User::factory()->create([
            'email' => 'super@taalimu.com',
            'tenant_id' => null,
            'role' => 'super_admin',
        ]);

        app()->instance('tenant', $tenant);

        $this->actingAs($globalAdmin);

        $response = $this->get(route('center.dashboard', ['tenant' => $tenant->domain]));

        $response->assertForbidden();
    }

    public function test_global_admin_can_still_use_admin_panel_on_tenant_host()
    {
        $tenant = $this->createTenant(['domain' => 'tenant-e']);

        $globalAdmin = User::factory()->create([
            'email' => 'super@taalimu.com',
            'tenant_id' => null,
            'role' => 'super_admin',
        ]);

        app()->instance('tenant', $tenant);

        $this->actingAs($globalAdmin, 'admin');

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_stale_session_bound_to_another_tenant_is_rejected()
    {
        $tenantA = $this->createTenant(['domain' => 'tenant-f']);
        $tenantB = $this->createTenant(['domain' => 'tenant-g']);

        $userA = User::factory()->create([
            'email' => 'admin@tenant-f.com',
            'tenant_id' => $tenantA->id,
            'role' => 'center_admin',
        ]);

        app()->instance('tenant', $tenantB);

        $this->actingAs($userA);
        // Simulate a session created under tenant A visiting tenant B
        session(['tenant_id' => $tenantA->id]);

        $response = $this->get(route('center.dashboard', ['tenant' => $tenantB->domain]));

        $response->assertForbidden();
    }
}
