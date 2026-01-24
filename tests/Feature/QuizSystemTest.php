<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Assignment;
use App\Models\Question;
use App\Models\Instructor;
use App\Models\Package;
use App\Models\Subscription;

class QuizSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $instructorUser; // The login user
    protected $instructorProfile; // The instructor profile
    protected $student;
    protected $course;
    protected $section;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Tenant
        $this->tenant = Tenant::create(['domain' => 'test', 'name' => 'Test Center']);
        
        // Setup Subscription
        $package = Package::create([
            'name' => 'Pro Plan',
            'slug' => 'pro',
            'price' => 99,
            'duration_in_days' => 30,
            'stripe_price_id' => 'price_pro',
        ]);

        // Add features
        $examFeature = \App\Models\Feature::firstOrCreate(['code' => 'manage_exams'], ['name' => 'Manage Exams', 'type' => 'boolean']);
        $package->features()->attach($examFeature->id, ['value' => '1']);

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

        // Setup Instructor Profile first so we can link it
        $this->instructorProfile = Instructor::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Dr. Instructor',
            'email' => 'instructor@test.com',
        ]);

        // Setup Roles and Permissions
        $role = \App\Models\Role::firstOrCreate(['name' => 'instructor', 'guard_name' => 'web']);
        $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'edit courses', 'guard_name' => 'web']);
        $role->givePermissionTo($permission);

        // Setup Users
        $this->instructorUser = User::factory()->create([
            'email' => 'instructor@test.com', 
            'tenant_id' => $this->tenant->id,
            'role' => 'instructor',
            'instructor_id' => $this->instructorProfile->id
        ]);
        $this->instructorUser->assignRole($role);
        
        $this->student = User::factory()->create(['email' => 'student@test.com', 'tenant_id' => $this->tenant->id]);

        // Setup Course
        $this->course = Course::create([
            'tenant_id' => $this->tenant->id,
            'instructor_id' => $this->instructorProfile->id,
            'title' => 'Test Course',
            'slug' => 'test-course',
            'description' => 'Test Description',
            'price' => 100,
        ]);

        $this->section = $this->course->sections()->create(['title' => 'Section 1', 'sort_order' => 1]);
    }

    public function test_instructor_can_create_quiz()
    {
        $this->actingAs($this->instructorUser);

        $lesson = Lesson::create([
            'section_id' => $this->section->id,
            'title' => 'Quiz Lesson',
            'type' => 'quiz',
            'sort_order' => 1,
        ]);

        $response = $this->post(route('center.quizzes.store', ['tenant' => $this->tenant->domain, 'lesson' => $lesson->id]), [
            'title' => 'My Quiz',
            'passing_score' => 70,
            'duration_minutes' => 10,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('quizzes', ['title' => 'My Quiz', 'duration_minutes' => 10]);
    }

    public function test_student_can_take_quiz_and_get_score()
    {
        $this->actingAs($this->student);

        // Create Quiz
        $lesson = Lesson::create([
            'section_id' => $this->section->id,
            'title' => 'Quiz Lesson',
            'type' => 'quiz',
            'sort_order' => 1,
        ]);

        $quiz = Quiz::create([
            'lesson_id' => $lesson->id,
            'title' => 'My Quiz',
            'passing_score' => 50,
            'duration_minutes' => 10,
        ]);

        // Create Question
        $question = $quiz->questions()->create(['content' => '1+1?', 'type' => 'mcq', 'points' => 10]);
        $correctOption = $question->options()->create(['content' => '2', 'is_correct' => true]);
        $wrongOption = $question->options()->create(['content' => '3', 'is_correct' => false]);

        // Start Quiz (Visit Show Page)
        $this->get(route('center.quizzes.show', ['tenant' => $this->tenant->domain, 'quiz' => $quiz->id]));

        // Submit Correct Answer
        $response = $this->post(route('center.quizzes.submit', ['tenant' => $this->tenant->domain, 'quiz' => $quiz->id]), [
            'answers' => [$question->id => $correctOption->id],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('quiz_attempts', [
            'user_id' => $this->student->id,
            'score' => 100,
            'passed' => true,
        ]);
    }

    public function test_quiz_time_limit_enforcement()
    {
        $this->actingAs($this->student);

        // Create Quiz with 1 minute duration
        $lesson = Lesson::create([
            'section_id' => $this->section->id,
            'title' => 'Timed Quiz',
            'type' => 'quiz',
            'sort_order' => 1,
        ]);

        $quiz = Quiz::create([
            'lesson_id' => $lesson->id,
            'title' => 'Timed Quiz',
            'passing_score' => 50,
            'duration_minutes' => 1,
        ]);

        $question = $quiz->questions()->create(['content' => '1+1?', 'type' => 'mcq', 'points' => 10]);
        $correctOption = $question->options()->create(['content' => '2', 'is_correct' => true]);

        // Start Quiz Session
        $this->get(route('center.quizzes.show', ['tenant' => $this->tenant->domain, 'quiz' => $quiz->id]));

        // Simulate time passing (2 minutes later)
        \Carbon\Carbon::setTestNow(now()->addMinutes(2));

        // Attempt to submit
        $response = $this->post(route('center.quizzes.submit', ['tenant' => $this->tenant->domain, 'quiz' => $quiz->id]), [
            'answers' => [$question->id => $correctOption->id],
        ]);

        // Should fail or redirect back with error
        $response->assertSessionHas('error', 'Time limit exceeded. Submission rejected.');
        
        // Ensure no attempt was recorded
        $this->assertDatabaseMissing('quiz_attempts', [
            'user_id' => $this->student->id,
            'quiz_id' => $quiz->id,
        ]);
    }
}
