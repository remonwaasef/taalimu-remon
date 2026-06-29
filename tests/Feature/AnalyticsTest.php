<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Student;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Instructor;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $admin;
    protected $instructorProfile;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Tenant
        $this->tenant = Tenant::create(['domain' => 'test', 'name' => 'Test Center']);
        
        setPermissionsTeamId($this->tenant->id);
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
        
        // Setup Subscription
        $package = Package::create([
            'name' => 'Pro Plan',
            'slug' => 'pro',
            'price' => 99,
            'duration_in_days' => 30,
        ]);

        Subscription::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'default',
            'stripe_id' => 'sub_test_123',
            'stripe_status' => 'active',
            'package_id' => $package->id,
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
            'status' => 'active',
        ]);

        // Setup Admin User
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'tenant_id' => $this->tenant->id,
            'role' => 'center_admin',
        ]);
        setPermissionsTeamId($this->tenant->id);
        $this->admin->assignRole('center_admin');

        // Setup Instructor Profile (needed for course creation)
        $this->instructorProfile = Instructor::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Dr. Instructor',
            'email' => 'instructor@test.com',
        ]);
    }

    public function test_analytics_page_loads_and_displays_metrics()
    {
        $this->actingAs($this->admin);

        // 1. Create Data
        // Create Students
        $student1 = Student::create(['tenant_id' => $this->tenant->id, 'name' => 'Student A', 'email' => 'studentA@test.com']);
        $student2 = Student::create(['tenant_id' => $this->tenant->id, 'name' => 'Student B', 'email' => 'studentB@test.com']);
        
        // Create Users for students (needed for quiz attempts)
        $user1 = User::factory()->create(['email' => 'studentA@test.com', 'tenant_id' => $this->tenant->id]);
        $user2 = User::factory()->create(['email' => 'studentB@test.com', 'tenant_id' => $this->tenant->id]);

        // Create Course & Quiz
        $course = Course::create([
            'tenant_id' => $this->tenant->id,
            'instructor_id' => $this->instructorProfile->id,
            'title' => 'Math 101',
            'slug' => 'math-101',
            'price' => 100
        ]);
        
        $section = $course->sections()->create(['title' => 'Section 1', 'sort_order' => 1]);
        $lesson = Lesson::create(['section_id' => $section->id, 'title' => 'Quiz 1', 'type' => 'quiz', 'sort_order' => 1]);
        $quiz = Quiz::create(['lesson_id' => $lesson->id, 'title' => 'Math Quiz', 'passing_score' => 50, 'duration_minutes' => 10]);

        // Create Attempts
        QuizAttempt::create(['user_id' => $user1->id, 'quiz_id' => $quiz->id, 'score' => 80, 'passed' => true, 'completed_at' => now()]);
        QuizAttempt::create(['user_id' => $user2->id, 'quiz_id' => $quiz->id, 'score' => 40, 'passed' => false, 'completed_at' => now()]);

        // Create Sale to have student appear in Recent Sales
        \App\Models\Sale::create([
            'tenant_id' => $this->tenant->id,
            'student_id' => $student1->id,
            'total_amount' => 100,
            'paid_amount' => 100,
            'status' => 'paid',
            'payment_method' => 'cash'
        ]);

        // 2. Visit Analytics Page
        $response = $this->get(route('center.analytics.index', ['tenant' => $this->tenant->domain]));

        // 3. Assertions
        $response->assertStatus(200);
        
        // Check Metrics
        $response->assertSee($student1->name); // Student A should be in Recent Sales
        
        // Check Counts
        $response->assertViewHas('totalStudents', 2);
        $response->assertViewHas('totalCourses', 1);
        // avgQuizScore is not passed to view, it's inside coursePerformance
        // $response->assertViewHas('avgQuizScore', 60);

        // Check Course Performance Data
        $coursePerformance = $response->viewData('coursePerformance');
        $this->assertEquals('Math 101', $coursePerformance->first()['name']);
        $this->assertEquals(60, $coursePerformance->first()['avg_score']);
    }
}
