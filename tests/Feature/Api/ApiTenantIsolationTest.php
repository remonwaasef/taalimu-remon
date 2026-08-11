<?php

namespace Tests\Feature\Api;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create(['domain' => 'api-test', 'name' => 'API Tenant']);
        app()->instance('tenant', $this->tenant);

        $this->user = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'center_admin',
        ]);
    }

    /** @test */
    public function it_returns_400_if_tenant_domain_header_is_missing()
    {
        $response = $this->getJson('/api/tenant/info');

        $response->assertStatus(400)
            ->assertJson(['success' => false]);
    }

    /** @test */
    public function it_returns_400_if_tenant_domain_header_is_blank_or_oversized()
    {
        $response = $this->withHeaders(['X-Tenant-Domain' => '   '])
            ->getJson('/api/tenant/info');

        $response->assertStatus(400);

        $response = $this->withHeaders(['X-Tenant-Domain' => str_repeat('a', 256)])
            ->getJson('/api/tenant/info');

        $response->assertStatus(400);
    }

    /** @test */
    public function it_returns_404_for_unknown_domain()
    {
        $response = $this->withHeaders(['X-Tenant-Domain' => 'unknown-domain'])
            ->getJson('/api/tenant/info');

        $response->assertStatus(404)
            ->assertJson(['success' => false]);
    }

    /** @test */
    public function it_returns_404_for_inactive_tenant()
    {
        $inactive = Tenant::create(['domain' => 'api-inactive', 'name' => 'Inactive']);
        $inactive->status = 'inactive';
        $inactive->save();

        $response = $this->withHeaders(['X-Tenant-Domain' => $inactive->domain])
            ->getJson('/api/tenant/info');

        $response->assertStatus(404)
            ->assertJson(['success' => false]);
    }

    /** @test */
    public function it_normalizes_the_tenant_domain_header()
    {
        $response = $this->withHeaders(['X-Tenant-Domain' => '  API-Test  '])
            ->getJson('/api/tenant/info');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $this->tenant->id,
                    'domain' => 'api-test',
                ],
            ]);
    }

    /** @test */
    public function it_ignores_a_www_prefix_in_the_tenant_domain_header()
    {
        $response = $this->withHeaders(['X-Tenant-Domain' => 'www.api-test'])
            ->getJson('/api/tenant/info');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => ['domain' => 'api-test'],
            ]);
    }

    /** @test */
    public function it_rejects_cross_tenant_tokens()
    {
        $otherTenant = Tenant::create(['domain' => 'api-other', 'name' => 'Other Tenant']);

        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'X-Tenant-Domain' => $otherTenant->domain,
        ])->getJson('/api/v1/campuses');

        $response->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized cross-tenant access.']);
    }

    /** @test */
    public function it_allows_same_tenant_tokens()
    {
        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'X-Tenant-Domain' => $this->tenant->domain,
        ])->getJson('/api/tenant/info');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => ['id' => $this->tenant->id],
            ]);
    }
}
