<?php

namespace Tests\Feature\Api;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiRoleTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $admin;

    protected $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create(['domain' => 'role-test', 'name' => 'Role Tenant']);
        app()->instance('tenant', $this->tenant);

        // Center Admin
        $this->admin = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'center_admin',
        ]);

        // Student
        $this->student = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'student',
        ]);
    }

    #[Test]
    public function center_admin_can_access_centers_api()
    {
        $token = $this->admin->createToken('admin-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->getJson('/api/v1/centers');

        // It might redirect if the controller logic forces it,
        // but we want to see if our Role logic in Controller works.
        // The current CenterController redirects students.

        $response->assertStatus(200);
    }

    #[Test]
    public function student_cannot_access_centers_api()
    {
        $token = $this->student->createToken('student-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->getJson('/api/v1/centers');

        // The controller currently redirects students to campus.index
        // In an API context, this usually means a 302 or if handled correctly a 403.
        // If we send Accept: application/json, Laravel might handle it.

        $response->assertStatus(403);
    }
}
