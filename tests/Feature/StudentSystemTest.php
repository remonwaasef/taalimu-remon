<?php

namespace Tests\Feature;

use App\Models\Grade;
use App\Models\Package;
use App\Models\Stage;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Tenant & Bind to container
        $this->tenant = Tenant::create(['domain' => 'qa', 'name' => 'QA Center', 'onboarding_status' => 'completed']);
        app()->instance('tenant', $this->tenant);

        // Clear Spatie Cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Fix Session Domain for Subdomain Auth & Force Root URL
        config(['session.domain' => '.localhost']);
        \Illuminate\Support\Facades\URL::forceRootUrl('http://qa.localhost');

        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($this->tenant->id);

        // Setup Permissions
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'view students', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'create students', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'edit students', 'guard_name' => 'web']);

        $package = Package::create([
            'name' => 'Pro',
            'slug' => 'pro',
            'stripe_price_id' => 'price_pro',
            'price' => 99,
            'duration_in_days' => 30,
        ]);

        $feature = \App\Models\Feature::firstOrCreate(
            ['code' => 'max_students'],
            ['name' => 'Max Students', 'type' => 'limit']
        );

        $package->features()->attach($feature->id, ['value' => 100]);

        Subscription::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'main',
            'stripe_id' => 'sub_test',
            'stripe_status' => 'active',
            'stripe_price' => 'price_pro',
            'ends_at' => now()->addDays(30),
            'status' => 'active',
        ]);

        // 2. Setup Admin
        $this->admin = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'center_admin',
            'must_change_password' => false,
        ]);
        $this->admin->givePermissionTo(['view students', 'create students', 'edit students']);

        // Fix: Create and assign Role for Policy checks
        $role = \App\Models\Role::firstOrCreate(['name' => 'center_admin', 'guard_name' => 'web', 'tenant_id' => $this->tenant->id]);
        $this->admin->assignRole($role);

        // 3. Setup Grade
        $stage = Stage::create(['name' => 'Elementary', 'tenant_id' => $this->tenant->id]);
        Grade::create(['name' => 'Level 1', 'tenant_id' => $this->tenant->id, 'stage_id' => $stage->id]);
    }

    /** @test */
    public function it_can_create_a_student_through_the_full_stack()
    {
        $this->actingAs($this->admin);
        $grade = Grade::first();

        $studentData = [
            'name' => 'اختبار الطالب',
            'email' => 'student@qa.com',
            'phone' => '01000000000',
            'gender' => 'male',
            'grade_id' => $grade->id,
            'password' => 'password123',
        ];

        $response = $this->post(route('center.students.store', ['tenant' => $this->tenant->domain]), $studentData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify Database
        $this->assertDatabaseHas('users', [
            'email' => 'student@qa.com',
            'tenant_id' => $this->tenant->id,
        ]);

        $this->assertDatabaseHas('students', [
            'email' => 'student@qa.com',
            'name' => 'اختبار الطالب',
            'grade_id' => $grade->id,
        ]);
    }

    /** @test */
    public function it_can_update_a_student_through_the_full_stack()
    {
        $this->actingAs($this->admin);
        $grade = Grade::first();

        $student = Student::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => User::factory()->create(['tenant_id' => $this->tenant->id])->id,
            'name' => 'Original Name',
            'email' => 'original@qa.com',
            'phone' => '01111111111',
            'gender' => 'female',
            'grade_id' => $grade->id,
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@qa.com', // Changing email
            'phone' => '01222222222',
            'gender' => 'female',
            'grade_id' => $grade->id,
        ];

        $response = $this->put(route('center.students.update', [
            'tenant' => $this->tenant->domain,
            'student' => $student->id,
        ]), $updateData);

        $response->assertRedirect();

        // Verify Database
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Updated Name',
            'email' => 'updated@qa.com',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $student->user_id,
            'email' => 'updated@qa.com',
        ]);
    }
}
