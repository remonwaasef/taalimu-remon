<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Course;
use Modules\Center\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;

class QRAttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $instructor;
    protected $student;
    protected $course;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Tenant
        $this->tenant = Tenant::create([
            'name' => 'Test Center',
            'domain' => 'test',
            'database' => 'test_db'
        ]);

        // Setup Instructor
        $this->instructor = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'instructor'
        ]);

        // Setup Student
        $this->student = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'student'
        ]);
        
        // Create Student Profile
        $this->student->student()->create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Student'
        ]);

        // Create Instructor Profile
        $instructorProfile = $this->instructor->instructor()->create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Instructor'
        ]);

        // Setup Course
        $this->course = Course::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Math 101',
            'instructor_id' => $instructorProfile->id,
            'price' => 100
        ]);
    }

    public function test_instructor_can_generate_qr_code()
    {
        $response = $this->actingAs($this->instructor)
            ->get(route('center.attendance.qr', [
                'tenant' => $this->tenant->domain,
                'course' => $this->course->id
            ]));

        $response->assertStatus(200);
        $response->assertViewIs('center::attendance.qr');
        $response->assertSee('Scan to Attend');
    }

    public function test_student_can_mark_attendance_with_valid_signature()
    {
        // Generate valid signed URL
        $url = URL::temporarySignedRoute(
            'center.attendance.mark',
            now()->addSeconds(30),
            [
                'tenant' => $this->tenant->domain,
                'course' => $this->course->id,
                'session' => 'test-session'
            ]
        );

        $response = $this->actingAs($this->student)
            ->get($url);

        $response->assertStatus(200);
        $response->assertViewIs('center::attendance.success');
        
        $this->assertDatabaseHas('attendances', [
            'student_id' => $this->student->student->id,
            'course_id' => $this->course->id,
            'status' => 'present'
        ]);
    }

    public function test_attendance_link_expires()
    {
        // Generate expired signed URL
        $url = URL::temporarySignedRoute(
            'center.attendance.mark',
            now()->subSeconds(1), // Expired
            [
                'tenant' => $this->tenant->domain,
                'course' => $this->course->id,
                'session' => 'test-session'
            ]
        );

        $response = $this->actingAs($this->student)
            ->get($url);

        $response->assertStatus(403);
    }
}
