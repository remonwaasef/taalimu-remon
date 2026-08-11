<?php

namespace Tests\Feature;

use App\Models\Guardian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentLoginTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = $this->createTenant(['domain' => 'parenttest']);
        app()->instance('tenant', $this->tenant);
    }

    public function test_parent_can_see_dedicated_login_page()
    {
        $response = $this->get(route('parent.login', ['tenant' => 'parenttest']));

        $response->assertStatus(200);
        $response->assertSee('بوابة ولي الأمر');
    }

    public function test_parent_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'parent@test.com',
            'password' => 'secret123',
            'tenant_id' => $this->tenant->id,
            'role' => 'parent',
        ]);

        Guardian::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $user->id,
            'name' => 'ولي أمر',
            'phone' => '01000000000',
        ]);

        $response = $this->post(route('parent.login.submit', ['tenant' => 'parenttest']), [
            'email' => 'parent@test.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('parent.index', ['tenant' => 'parenttest']));
        $this->assertSame($user->id, auth()->id());
        $this->assertSame($this->tenant->id, (int) session('tenant_id'));
    }

    public function test_non_parent_user_cannot_login_via_parent_page()
    {
        User::factory()->create([
            'email' => 'admin@test.com',
            'password' => 'secret123',
            'tenant_id' => $this->tenant->id,
            'role' => 'center_admin',
        ]);

        $response = $this->post(route('parent.login.submit', ['tenant' => 'parenttest']), [
            'email' => 'admin@test.com',
            'password' => 'secret123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertNull(auth()->user());
    }

    public function test_parent_from_another_tenant_cannot_login()
    {
        $otherTenant = $this->createTenant(['domain' => 'otherparent']);

        // Create the user under the OTHER tenant's binding — the BelongsToTenant
        // trait overrides tenant_id from the bound tenant on creation.
        app()->instance('tenant', $otherTenant);
        $user = User::factory()->create([
            'email' => 'parent-other@test.com',
            'password' => 'secret123',
            'tenant_id' => $otherTenant->id,
            'role' => 'parent',
        ]);

        Guardian::create([
            'tenant_id' => $otherTenant->id,
            'user_id' => $user->id,
            'name' => 'ولي أمر',
            'phone' => '01000000001',
        ]);
        app()->instance('tenant', $this->tenant);

        $response = $this->post(route('parent.login.submit', ['tenant' => 'parenttest']), [
            'email' => 'parent-other@test.com',
            'password' => 'secret123',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
