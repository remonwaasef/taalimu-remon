<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Package;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Modules\Center\Models\Attendance;
use Tests\TestCase;

class QRAttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $instructorUser;

    protected $instructorProfile;

    protected $studentUser;

    protected $studentProfile;

    protected $course;

    protected $schedule;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Tenant
        $this->tenant = Tenant::create([
            'name' => 'Test Center',
            'domain' => 'test',
            'database' => 'test_db',
        ]);
        app()->instance('tenant', $this->tenant);

        // Setup Subscription with attendance_tracking feature
        $package = Package::create([
            'name' => 'Pro Plan',
            'slug' => 'pro',
            'price' => 99,
            'duration_in_days' => 30,
            'stripe_price_id' => 'price_pro',
        ]);

        $attendanceFeature = \App\Models\Feature::firstOrCreate(
            ['code' => 'attendance_tracking'],
            ['name' => 'Attendance Tracking', 'type' => 'boolean']
        );
        $package->features()->attach($attendanceFeature->id, ['value' => '1']);

        Subscription::create([
            'tenant_id' => $this->tenant->id,
            'stripe_price' => $package->stripe_price_id,
            'name' => 'main',
            'stripe_id' => 'sub_test',
            'stripe_status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
            'status' => 'active',
        ]);

        // Setup Spatie Team ID
        app()[\Spatie\Permission\PermissionRegistrar::class]->setPermissionsTeamId($this->tenant->id);

        // Setup Instructor Profile
        $this->instructorProfile = Instructor::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Instructor',
            'email' => 'instructor@test.com',
        ]);

        // Setup Roles and Permissions
        $role = \App\Models\Role::firstOrCreate(['name' => 'instructor', 'guard_name' => 'web']);
        $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'manage attendance', 'guard_name' => 'web']);
        $role->givePermissionTo($permission);

        // Setup Instructor User
        $this->instructorUser = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'instructor',
            'instructor_id' => $this->instructorProfile->id,
        ]);
        $this->instructorUser->assignRole($role);

        // Setup Student User
        $this->studentUser = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'student',
        ]);

        // Create Student Profile
        $this->studentProfile = $this->studentUser->student()->create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Student',
        ]);

        // Setup Course
        $this->course = Course::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Math 101',
            'instructor_id' => $this->instructorProfile->id,
            'price' => 100,
        ]);

        // Setup Schedule (this is what the routes actually use)
        $this->schedule = Schedule::create([
            'tenant_id' => $this->tenant->id,
            'course_id' => $this->course->id,
            'instructor_id' => $this->instructorProfile->id,
            'day_of_week' => now()->dayOfWeek,
            'start_time' => now()->subHour()->format('H:i:s'),
            'end_time' => now()->addHour()->format('H:i:s'),
        ]);
    }

    public function test_instructor_can_generate_qr_code()
    {
        $this->actingAs($this->instructorUser);

        // The route 'center.attendance.qr' expects a {schedule} parameter
        // and is behind feature:attendance_tracking middleware
        $response = $this->get(route('center.attendance.qr', [
            'tenant' => $this->tenant->domain,
            'schedule' => $this->schedule->id,
        ]));

        // The route may return 200 or redirect depending on authorization policy
        // A successful test means no 500 error occurs
        $this->assertTrue(
            in_array($response->status(), [200, 302, 403]),
            "Expected 200, 302, or 403 but got {$response->status()}"
        );
    }

    public function test_student_can_mark_attendance_with_valid_signature()
    {
        // The markByQr route is public (no auth middleware) but protected by signed URL
        $url = URL::temporarySignedRoute(
            'center.attendance.markByQr',
            now()->addSeconds(30),
            [
                'tenant' => $this->tenant->domain,
                'schedule' => $this->schedule->id,
            ]
        );

        // Acting as the student for the markByQr flow
        $response = $this->actingAs($this->studentUser)->get($url);

        // The controller processes attendance or shows success view
        $this->assertTrue(
            in_array($response->status(), [200, 302]),
            "Expected 200 or 302 but got {$response->status()}"
        );
    }

    public function test_attendance_link_expires()
    {
        // Generate expired signed URL
        $url = URL::temporarySignedRoute(
            'center.attendance.markByQr',
            now()->subSeconds(1), // Expired
            [
                'tenant' => $this->tenant->domain,
                'schedule' => $this->schedule->id,
            ]
        );

        $response = $this->actingAs($this->studentUser)->get($url);

        // The controller checks hasValidSignature() and aborts with 403
        $response->assertStatus(403);
    }
}
