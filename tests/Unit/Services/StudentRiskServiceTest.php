<?php

namespace Tests\Unit\Services;

use App\Models\Course;
use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Stage;
use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use App\Services\StudentRiskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Center\Models\Attendance;
use Tests\TestCase;

class StudentRiskServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $riskService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->riskService = new StudentRiskService();
    }

    private function createStudentWithGrade(Tenant $tenant): Student
    {
        app()->instance('tenant', $tenant);

        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        $stage = Stage::create([
            'tenant_id' => $tenant->id,
            'name' => 'Stage 1',
            'order' => 1,
        ]);
        $grade = Grade::create([
            'tenant_id' => $tenant->id,
            'stage_id' => $stage->id,
            'name' => 'Grade 1',
            'order' => 1,
        ]);

        return Student::factory()->create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'grade_id' => $grade->id,
            'status' => 'active',
        ]);
    }

    private function createCourseWithSchedule(Tenant $tenant): array
    {
        $instructor = \App\Models\Instructor::create([
            'tenant_id' => $tenant->id,
            'name' => 'Test Instructor',
            'email' => 'instructor_' . $tenant->id . '@test.com',
            'phone' => '01234567890',
            'status' => 'active',
        ]);

        $course = Course::create([
            'tenant_id' => $tenant->id,
            'instructor_id' => $instructor->id,
            'title' => 'Test Course',
            'status' => 'active',
            'price' => 500,
        ]);

        $schedule = Schedule::create([
            'tenant_id' => $tenant->id,
            'course_id' => $course->id,
            'instructor_id' => $instructor->id,
            'day_of_week' => 'Sunday',
            'start_time' => '10:00',
            'end_time' => '12:00',
        ]);

        return ['course' => $course, 'schedule' => $schedule];
    }

    public function test_calculate_risk_returns_low_for_new_student()
    {
        $tenant = $this->createTenant(['domain' => 'risk-test-1']);
        $student = $this->createStudentWithGrade($tenant);

        $risk = $this->riskService->calculateRisk($student);

        $this->assertArrayHasKey('score', $risk);
        $this->assertArrayHasKey('level', $risk);
        $this->assertArrayHasKey('reasons', $risk);
        $this->assertIsInt($risk['score']);
        $this->assertContains($risk['level'], ['low', 'medium', 'high', 'critical']);
    }

    public function test_calculate_risk_detects_high_absence_rate()
    {
        $tenant = $this->createTenant(['domain' => 'risk-test-2']);
        $student = $this->createStudentWithGrade($tenant);
        ['course' => $course, 'schedule' => $schedule] = $this->createCourseWithSchedule($tenant);

        for ($i = 0; $i < 10; $i++) {
            Attendance::create([
                'tenant_id' => $tenant->id,
                'student_id' => $student->id,
                'course_id' => $course->id,
                'schedule_id' => $schedule->id,
                'session_date' => now()->subDays($i),
                'status' => 'absent',
            ]);
        }

        $risk = $this->riskService->calculateRisk($student);

        $this->assertGreaterThanOrEqual(20, $risk['score']);
        $this->assertContains($risk['level'], ['medium', 'high', 'critical']);
    }

    public function test_calculate_risk_detects_consecutive_absences()
    {
        $tenant = $this->createTenant(['domain' => 'risk-test-3']);
        $student = $this->createStudentWithGrade($tenant);
        ['course' => $course, 'schedule' => $schedule] = $this->createCourseWithSchedule($tenant);

        for ($i = 0; $i < 5; $i++) {
            Attendance::create([
                'tenant_id' => $tenant->id,
                'student_id' => $student->id,
                'course_id' => $course->id,
                'schedule_id' => $schedule->id,
                'session_date' => now()->subDays($i),
                'status' => 'absent',
            ]);
        }

        $risk = $this->riskService->calculateRisk($student);

        $this->assertGreaterThanOrEqual(15, $risk['score']);
        $this->assertTrue(
            !empty(array_filter($risk['reasons'], fn ($r) => str_contains($r, 'غياب متتالي')))
        );
    }

    public function test_get_at_risk_students_returns_correct_students()
    {
        $tenant = $this->createTenant(['domain' => 'risk-test-4']);
        app()->instance('tenant', $tenant);

        $stage = Stage::create([
            'tenant_id' => $tenant->id,
            'name' => 'Stage 1',
            'order' => 1,
        ]);
        $grade = Grade::create([
            'tenant_id' => $tenant->id,
            'stage_id' => $stage->id,
            'name' => 'Grade 1',
            'order' => 1,
        ]);

        $user1 = User::factory()->create(['tenant_id' => $tenant->id]);
        $student1 = Student::factory()->create([
            'tenant_id' => $tenant->id,
            'user_id' => $user1->id,
            'grade_id' => $grade->id,
            'status' => 'active',
            'risk_level' => 'high',
            'risk_score' => 75,
        ]);

        $user2 = User::factory()->create(['tenant_id' => $tenant->id]);
        $student2 = Student::factory()->create([
            'tenant_id' => $tenant->id,
            'user_id' => $user2->id,
            'grade_id' => $grade->id,
            'status' => 'active',
            'risk_level' => 'low',
            'risk_score' => 10,
        ]);

        $atRisk = $this->riskService->getAtRiskStudents($tenant->id, 'medium');

        $this->assertIsArray($atRisk);
        $this->assertCount(1, $atRisk);
        $this->assertEquals($student1->id, $atRisk[0]['id']);
    }
}
