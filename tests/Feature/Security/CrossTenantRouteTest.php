<?php

namespace Tests\Feature\Security;

use App\Models\Grade;
use App\Models\Stage;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrossTenantRouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that Tenant A cannot access Tenant B's students by ID.
     * Middleware blocks with 403.
     */
    public function test_tenant_a_cannot_access_tenant_b_students()
    {
        $tenantA = $this->createTenant(['domain' => 'cross-iso-a']);
        app()->instance('tenant', $tenantA);
        $stageA = Stage::create(['tenant_id' => $tenantA->id, 'name' => 'S1', 'order' => 1]);
        $gradeA = Grade::create(['tenant_id' => $tenantA->id, 'stage_id' => $stageA->id, 'name' => 'G1', 'order' => 1]);
        $userA = User::factory()->create(['tenant_id' => $tenantA->id, 'role' => 'center_admin']);
        $studentA = Student::factory()->create([
            'tenant_id' => $tenantA->id,
            'user_id' => User::factory()->create(['tenant_id' => $tenantA->id, 'role' => 'student'])->id,
            'grade_id' => $gradeA->id,
        ]);

        $tenantB = $this->createTenant(['domain' => 'cross-iso-b']);
        $userB = User::factory()->create(['tenant_id' => $tenantB->id, 'role' => 'center_admin']);

        $this->actingAs($userB);
        app()->instance('tenant', $tenantB);

        $response = $this->get(route('center.students.show', [
            'student' => $studentA->id,
            'tenant' => $tenantB->domain,
        ]));

        $response->assertStatus(403);
    }

    /**
     * Test that Tenant A cannot access Tenant B's instructors by ID.
     */
    public function test_tenant_a_cannot_access_tenant_b_instructors()
    {
        $tenantA = $this->createTenant(['domain' => 'cross-iso-c']);
        app()->instance('tenant', $tenantA);
        $instructorUser = User::factory()->create(['tenant_id' => $tenantA->id, 'role' => 'instructor']);
        $instructorA = \App\Models\Instructor::create([
            'tenant_id' => $tenantA->id,
            'user_id' => $instructorUser->id,
            'name' => 'Instructor A',
        ]);

        $tenantB = $this->createTenant(['domain' => 'cross-iso-d']);
        $userB = User::factory()->create(['tenant_id' => $tenantB->id, 'role' => 'center_admin']);

        $this->actingAs($userB);
        app()->instance('tenant', $tenantB);

        $response = $this->get(route('center.instructors.show', [
            'instructor' => $instructorA->id,
            'tenant' => $tenantB->domain,
        ]));

        $response->assertStatus(403);
    }

    /**
     * Test that Tenant A cannot access Tenant B's courses by ID.
     */
    public function test_tenant_a_cannot_access_tenant_b_courses()
    {
        $tenantA = $this->createTenant(['domain' => 'cross-iso-e']);
        app()->instance('tenant', $tenantA);
        $instructorUser = User::factory()->create(['tenant_id' => $tenantA->id, 'role' => 'instructor']);
        $instructorA = \App\Models\Instructor::create([
            'tenant_id' => $tenantA->id,
            'user_id' => $instructorUser->id,
            'name' => 'Instructor A',
        ]);
        $courseA = \App\Models\Course::create([
            'tenant_id' => $tenantA->id,
            'instructor_id' => $instructorA->id,
            'title' => 'Course A',
            'price' => 100,
        ]);

        $tenantB = $this->createTenant(['domain' => 'cross-iso-f']);
        $userB = User::factory()->create(['tenant_id' => $tenantB->id, 'role' => 'center_admin']);

        $this->actingAs($userB);
        app()->instance('tenant', $tenantB);

        $response = $this->get(route('center.courses.show', [
            'course' => $courseA->id,
            'tenant' => $tenantB->domain,
        ]));

        $response->assertStatus(403);
    }
}
