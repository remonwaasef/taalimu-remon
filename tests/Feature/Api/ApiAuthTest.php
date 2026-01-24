<?php

namespace Tests\Feature\Api;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
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
    public function it_returns_401_unauthorized_if_no_token_provided()
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_returns_user_data_if_valid_token_provided()
    {
        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user');

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $this->user->id,
                     'email' => $this->user->email,
                 ]);
    }
}
