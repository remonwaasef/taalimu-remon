<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * SEC-AUTH-1 regression: onboarding endpoints mutate tenant-wide records
 * (settings, instructor accounts, courses, students, sales), so only tenant
 * administrators may call them — never students, parents or staff.
 */
class OnboardingAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'session.domain' => '.localhost',
            'app.tenant_domain' => 'localhost',
        ]);
        \Illuminate\Support\Facades\URL::forceRootUrl('http://test.localhost');

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    private function tenantWithUser(string $role): array
    {
        $tenant = Tenant::create([
            'domain' => 'test',
            'name' => 'Test Center',
            'onboarding_status' => 'pending',
        ]);
        app()->instance('tenant', $tenant);

        $user = User::create([
            'name' => 'User '.$role,
            'email' => 'u-'.$role.'@test.com',
            'phone' => '0100000'.$role,
            'password' => Hash::make('password'),
            'role' => $role,
            'tenant_id' => $tenant->id,
            'must_change_password' => false,
            'google2fa_enabled' => false,
        ]);

        return [$tenant, $user];
    }

    public function test_student_cannot_submit_onboarding()
    {
        [$tenant, $student] = $this->tenantWithUser('student');

        $this->actingAs($student)
            ->withSession(['tenant_id' => $tenant->id])
            ->post(route('center.onboarding.submit', ['tenant' => $tenant->domain]), [
                'step' => 'step_1',
                'currency' => 'EGP',
                'education_system' => 'egypt',
                'locale' => 'ar',
            ])
            ->assertStatus(403);

        $this->assertDatabaseMissing('site_settings', ['tenant_id' => $tenant->id]);
    }

    public function test_instructor_cannot_run_fix_invoices()
    {
        [$tenant, $instructor] = $this->tenantWithUser('instructor');

        $this->actingAs($instructor)
            ->withSession(['tenant_id' => $tenant->id])
            ->get(route('center.onboarding.fix-invoices', ['tenant' => $tenant->domain]))
            ->assertStatus(403);
    }

    public function test_center_admin_can_open_onboarding()
    {
        [$tenant, $admin] = $this->tenantWithUser('center_admin');

        $this->actingAs($admin)
            ->withSession(['tenant_id' => $tenant->id])
            ->get(route('center.onboarding.show', ['tenant' => $tenant->domain]))
            ->assertOk();
    }
}