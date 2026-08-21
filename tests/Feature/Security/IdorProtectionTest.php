<?php

namespace Tests\Feature\Security;

use App\Models\Grade;
use App\Models\Stage;
use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IdorProtectionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verify that TenantScope is applied as a global scope on Student model.
     */
    public function test_student_model_has_tenant_scope()
    {
        $tenant = $this->createTenant(['domain' => 'scope-check']);
        app()->instance('tenant', $tenant);

        $student = new Student();
        $scopes = $student->getGlobalScopes();
        $this->assertArrayHasKey(
            \App\Scopes\TenantScope::class,
            $scopes,
            'Student model must have TenantScope as a global scope'
        );
    }

    /**
     * Verify that getAtRiskStudents isolates by tenant.
     * This is the key IDOR protection for the risk engine.
     */
    public function test_risk_score_isolation_between_tenants()
    {
        $tenantA = $this->createTenant(['domain' => 'risk-iso-a']);
        app()->instance('tenant', $tenantA);
        $stageA = Stage::create(['tenant_id' => $tenantA->id, 'name' => 'S1', 'order' => 1]);
        $gradeA = Grade::create(['tenant_id' => $tenantA->id, 'stage_id' => $stageA->id, 'name' => 'G1', 'order' => 1]);
        $userA = User::factory()->create(['tenant_id' => $tenantA->id]);
        $studentA = Student::factory()->create([
            'tenant_id' => $tenantA->id,
            'user_id' => $userA->id,
            'grade_id' => $gradeA->id,
            'risk_level' => 'high',
            'risk_score' => 75,
        ]);

        $tenantB = $this->createTenant(['domain' => 'risk-iso-b']);
        $stageB = Stage::create(['tenant_id' => $tenantB->id, 'name' => 'S2', 'order' => 1]);
        $gradeB = Grade::create(['tenant_id' => $tenantB->id, 'stage_id' => $stageB->id, 'name' => 'G2', 'order' => 1]);
        $userB = User::factory()->create(['tenant_id' => $tenantB->id]);
        $studentB = Student::factory()->create([
            'tenant_id' => $tenantB->id,
            'user_id' => $userB->id,
            'grade_id' => $gradeB->id,
            'risk_level' => 'low',
            'risk_score' => 5,
        ]);

        $riskService = new \App\Services\StudentRiskService();

        $atRiskA = $riskService->getAtRiskStudents($tenantA->id, 'medium');
        $atRiskIds = array_column($atRiskA, 'id');
        $this->assertContains($studentA->id, $atRiskIds);
        $this->assertNotContains($studentB->id, $atRiskIds);

        $atRiskB = $riskService->getAtRiskStudents($tenantB->id, 'medium');
        $atRiskBIds = array_column($atRiskB, 'id');
        $this->assertNotContains($studentA->id, $atRiskBIds);
    }

    public function test_global_admin_cannot_access_tenant_area()
    {
        $tenant = $this->createTenant(['domain' => 'idor-global-admin']);

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
}
