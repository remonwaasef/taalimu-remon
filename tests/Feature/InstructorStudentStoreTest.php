<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Package;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Covers the Instructor student-store flow after it was refactored to delegate
 * creation and enrolment to the shared StudentService instead of duplicating the
 * logic inline.
 */
class InstructorStudentStoreTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $instructorUser;

    protected $instructor;

    protected $course;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'domain' => 'qa',
            'name' => 'QA Center',
            'type' => 'instructor',
            'onboarding_status' => 'completed',
        ]);
        app()->instance('tenant', $this->tenant);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        config(['session.domain' => '.localhost']);
        \Illuminate\Support\Facades\URL::forceRootUrl('http://qa.localhost');
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($this->tenant->id);

        $package = Package::create([
            'name' => 'Pro',
            'slug' => 'pro',
            'stripe_price_id' => 'price_pro',
            'price' => 99,
            'duration_in_days' => 30,
        ]);

        Subscription::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'main',
            'stripe_id' => 'sub_test',
            'stripe_status' => 'active',
            'stripe_price' => 'price_pro',
            'ends_at' => now()->addDays(30),
            'status' => 'active',
        ]);

        $this->instructorUser = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'instructor',
            'must_change_password' => false,
            'email_verified_at' => now(),
        ]);
        $role = \App\Models\Role::firstOrCreate(['name' => 'instructor', 'guard_name' => 'web', 'tenant_id' => $this->tenant->id]);
        $this->instructorUser->assignRole($role);

        $this->instructor = Instructor::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->instructorUser->id,
            'name' => $this->instructorUser->name,
            'email' => $this->instructorUser->email,
            'phone' => $this->instructorUser->phone,
            'status' => 'active',
        ]);

        $this->course = Course::create([
            'tenant_id' => $this->tenant->id,
            'instructor_id' => $this->instructor->id,
            'title' => 'Math Group',
            'price' => 150,
            'status' => 'published',
        ]);
    }

    /** @test */
    public function it_creates_and_enrolls_a_new_student_via_the_shared_service()
    {
        $this->actingAs($this->instructorUser);

        $response = $this->post(route('instructor.students.store', ['tenant' => $this->tenant->domain]), [
            'name' => 'طالب جديد',
            'phone' => '01000000000',
            'course_ids' => [$this->course->id],
        ]);

        $response->assertRedirect(route('instructor.students.list', ['tenant' => $this->tenant->domain]));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('students', [
            'name' => 'طالب جديد',
            'phone' => '01000000000',
            'tenant_id' => $this->tenant->id,
        ]);

        $student = Student::where('phone', '01000000000')->first();
        $this->assertNotNull($student);

        // An unpaid sale for the course should have been created via FinanceService.
        $this->assertDatabaseHas('sales', [
            'student_id' => $student->id,
            'tenant_id' => $this->tenant->id,
        ]);
    }

    /** @test */
    public function it_rejects_enrolment_into_a_course_the_instructor_does_not_own()
    {
        $otherInstructor = Instructor::create([
            'tenant_id' => $this->tenant->id,
            'user_id' => User::factory()->create(['tenant_id' => $this->tenant->id])->id,
            'name' => 'Other',
            'status' => 'active',
        ]);
        $foreignCourse = Course::create([
            'tenant_id' => $this->tenant->id,
            'instructor_id' => $otherInstructor->id,
            'title' => 'Foreign Group',
            'price' => 100,
            'status' => 'published',
        ]);

        $this->actingAs($this->instructorUser);

        $response = $this->post(route('instructor.students.store', ['tenant' => $this->tenant->domain]), [
            'name' => 'مخالف',
            'phone' => '01099999999',
            'course_ids' => [$foreignCourse->id],
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        // No student should have been created for the foreign course attempt.
        $this->assertDatabaseMissing('students', ['phone' => '01099999999']);
    }
}
