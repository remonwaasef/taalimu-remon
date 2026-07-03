<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * A user who can only *view* sales must not be able to create them. This locks
 * in the SaleController::store authorization added when the financial write
 * permissions were tightened (previously the route only required 'view sales').
 */
class SaleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $student;

    protected $course;

    protected function setUp(): void
    {
        parent::setUp();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        config(['session.domain' => '.localhost']);

        $this->tenant = Tenant::create(['domain' => 'test', 'name' => 'Test Center']);
        app()->instance('tenant', $this->tenant);
        \Illuminate\Support\Facades\URL::forceRootUrl('http://test.localhost');

        $feature = \App\Models\Feature::firstOrCreate(
            ['code' => 'financial_reports'],
            ['name' => 'Financial Reports', 'type' => 'boolean']
        );
        $package = \App\Models\Package::create(['name' => 'Pro', 'slug' => 'pro', 'stripe_price_id' => 'p_1', 'price' => 10, 'duration_in_days' => 30]);
        $package->features()->attach($feature, ['value' => 'true']);

        \App\Models\Subscription::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'main',
            'stripe_id' => 's_1',
            'stripe_status' => 'active',
            'stripe_price' => 'p_1',
            'ends_at' => now()->addYear(),
            'status' => 'active',
        ]);

        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($this->tenant->id);
        foreach (['view sales', 'create sales', 'edit sales', 'delete sales'] as $perm) {
            \Spatie\Permission\Models\Permission::create(['name' => $perm, 'guard_name' => 'web']);
        }

        $this->student = Student::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'status' => 'active',
        ]);
        $this->instructor = Instructor::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Prof. Smith',
            'email' => 'smith@test.com',
            'specialization' => 'Math',
        ]);
        $this->course = Course::create([
            'tenant_id' => $this->tenant->id,
            'instructor_id' => $this->instructor->id,
            'title' => 'Test Course',
            'price' => 100.00,
            'status' => 'published',
        ]);
    }

    private function userWithPermissions(array $permissions, string $roleName): User
    {
        $user = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => $roleName,
            'must_change_password' => false,
            'google2fa_enabled' => false,
        ]);
        // A non-admin role, so the policy falls through to permission checks.
        $role = \App\Models\Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web', 'tenant_id' => $this->tenant->id]);
        $user->assignRole($role);
        $user->givePermissionTo($permissions);

        return $user;
    }

    public function test_view_only_user_cannot_create_a_sale()
    {
        $viewer = $this->userWithPermissions(['view sales'], 'viewer');
        $this->actingAs($viewer);

        $response = $this->postJson(route('center.sales.store', ['tenant' => $this->tenant->domain]), [
            'student_id' => $this->student->id,
            'items' => [['id' => $this->course->id, 'price' => 100.00]],
            'payment_method' => 'cash',
            'paid_amount' => 0,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('sales', ['student_id' => $this->student->id]);
    }

    public function test_user_with_create_permission_can_create_a_sale()
    {
        $seller = $this->userWithPermissions(['view sales', 'create sales'], 'seller');
        $this->actingAs($seller);

        $response = $this->postJson(route('center.sales.store', ['tenant' => $this->tenant->domain]), [
            'student_id' => $this->student->id,
            'items' => [['id' => $this->course->id, 'price' => 100.00]],
            'payment_method' => 'cash',
            'paid_amount' => 0,
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseHas('sales', ['student_id' => $this->student->id, 'total_amount' => 100.00]);
    }
}
