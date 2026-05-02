<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationUXTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_register_center_without_subdomain_input_and_auto_generates_it()
    {
        $this->seed(\Database\Seeders\PackageSeeder::class);

        $response = $this->post(route('register.submit'), [
            'center_name' => 'Demo Center',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Str0ngP@ssw0rd!1', // Meets complexity
            'password_confirmation' => 'Str0ngP@ssw0rd!1',
            'plan' => 'basic',
            'billing_cycle' => 'monthly',
        ]);

        $response->assertRedirect(route('registration.success'));

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);

        $this->assertDatabaseHas('tenants', [
            'name' => 'Demo Center',
            'domain' => 'demo-center', // Auto-generated slug
        ]);
    }

    /** @test */
    public function it_handles_duplicate_subdomains_by_appending_counter()
    {
        $this->seed(\Database\Seeders\PackageSeeder::class);

        // Create first tenant
        Tenant::create([
            'name' => 'Demo Center',
            'domain' => 'demo-center',
            'database_name' => 'edu_central',
            'status' => 'active'
        ]);

        // Try registering again with same name
        $response = $this->post(route('register.submit'), [
            'center_name' => 'Demo Center',
            'name' => 'Jane Doe',
            'email' => 'jane@example.com', // Different email
            'password' => 'Str0ngP@ssw0rd!2',
            'password_confirmation' => 'Str0ngP@ssw0rd!2',
            'plan' => 'basic',
            'billing_cycle' => 'monthly',
        ]);

        $response->assertRedirect(route('registration.success'));

        $this->assertDatabaseHas('tenants', [
            'domain' => 'demo-center-1', // Auto-generated slug with counter
        ]);
    }
}
