<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_register_center_without_subdomain_input_and_auto_generates_it()
    {
        $this->seed(\Database\Seeders\PackageSeeder::class);
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        $payload = [
            'account_type' => 'center',
            'center_name' => 'Demo Center',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '01213065203',
            'password' => 'Str0ngP@ssw0rd!1',
            'password_confirmation' => 'Str0ngP@ssw0rd!1',
            'plan' => 'basic',
            'billing_cycle' => 'monthly',
            'payment_gateway' => 'test'
        ];

        $response = $this->withSession([
            'phone_verified' => true,
            'phone_verified_number' => $payload['phone'],
        ])->call('POST', '/register', $payload);

        $this->assertEquals(302, $response->status());

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_it_handles_duplicate_subdomains_by_appending_counter()
    {
        $this->seed(\Database\Seeders\PackageSeeder::class);
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        Tenant::create([
            'name' => 'Demo Center',
            'email' => 'admin1@demo.com',
            'domain' => 'demo-center',
            'database_name' => 'edu_central',
            'status' => 'active'
        ]);

        $payload = [
            'account_type' => 'center',
            'center_name' => 'Demo Center',
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '01213065205',
            'password' => 'Str0ngP@ssw0rd!2',
            'password_confirmation' => 'Str0ngP@ssw0rd!2',
            'plan' => 'basic',
            'billing_cycle' => 'monthly',
            'payment_gateway' => 'test'
        ];

        $response = $this->withSession([
            'phone_verified' => true,
            'phone_verified_number' => $payload['phone'],
        ])->call('POST', '/register', $payload);

        $this->assertEquals(302, $response->status());

        $this->assertDatabaseHas('tenants', ['domain' => 'demo-center-1']);
    }
}
