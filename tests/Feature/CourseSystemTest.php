<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $admin;
    protected $instructor;
    protected $classroom;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Tenant & Bind to container
        $this->tenant = Tenant::create(['domain' => 'qa-course', 'name' => 'QA Academy']);
        app()->instance('tenant', $this->tenant);

        // Clear Spatie Cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        app()[\Spatie\Permission\PermissionRegistrar::class]->setPermissionsTeamId($this->tenant->id);

        // Setup Permissions
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'view courses', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'create courses', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'edit courses', 'guard_name' => 'web']);
        
        // Setup Role
        $role = \App\Models\Role::firstOrCreate(['name' => 'center_admin', 'guard_name' => 'web']);
        
        $package = \App\Models\Package::create([
            'name' => 'Elite',
            'slug' => 'elite',
            'stripe_price_id' => 'price_elite',
            'price' => 500,
            'duration_in_days' => 365,
        ]);

        $feature = \App\Models\Feature::firstOrCreate([
            'code' => 'max_courses',
        ], [
            'name' => 'Max Courses',
            'type' => 'limit',
        ]);

        $package->features()->attach($feature->id, ['value' => 50]);

        Subscription::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'main',
            'stripe_id' => 'sub_test',
            'stripe_status' => 'active',
            'stripe_price' => 'price_elite',
            'ends_at' => now()->addDays(365),
            'status' => 'active',
        ]);

        // 2. Setup Admin
        $this->admin = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'center_admin',
            'must_change_password' => false,
        ]);
        $this->admin->assignRole($role);
        $this->admin->givePermissionTo(['view courses', 'create courses', 'edit courses']);

        // 3. Setup Dependencies
        $this->instructor = Instructor::create([
            'name' => 'Dr. Smith',
            'email' => 'smith@qa.com',
            'tenant_id' => $this->tenant->id,
        ]);

        $this->classroom = Classroom::create([
            'name' => 'Lab A',
            'capacity' => 30,
            'tenant_id' => $this->tenant->id,
        ]);
    }

    /** @test */
    public function it_can_create_a_course_with_schedules_through_the_full_stack()
    {
        $this->actingAs($this->admin);

        $courseData = [
            'title' => 'Advanced Laravel Physics',
            'description' => 'A very deep course about PHP and Gravity.',
            'instructor_id' => $this->instructor->id,
            'price' => 200,
            'status' => 'published',
            'schedules' => [
                [
                    'day_of_week' => 'sunday',
                    'start_time' => '10:00',
                    'end_time' => '12:00',
                    'classroom_id' => $this->classroom->id,
                    'max_students' => 25
                ],
                [
                    'day_of_week' => 'tuesday',
                    'start_time' => '14:00',
                    'end_time' => '16:00',
                    'classroom_id' => $this->classroom->id,
                    'max_students' => 25
                ]
            ]
        ];

        $response = $this->post(route('center.courses.store', ['tenant' => $this->tenant->domain]), $courseData);

        $response->assertRedirect();

        // Verify Database Course
        $this->assertDatabaseHas('courses', [
            'title' => 'Advanced Laravel Physics',
            'instructor_id' => $this->instructor->id,
            'tenant_id' => $this->tenant->id,
        ]);

        $course = Course::where('title', 'Advanced Laravel Physics')->first();

        // Verify Database Schedules
        $this->assertDatabaseCount('schedules', 2);
        $this->assertDatabaseHas('schedules', [
            'course_id' => $course->id,
            'day_of_week' => 0, // Sunday is 0 in our mapping
            'start_time' => '10:00',
            'classroom_id' => $this->classroom->id,
        ]);
    }
}
