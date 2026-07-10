<?php

namespace Tests\Unit\Models;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenant = Tenant::create(['domain' => 'test', 'name' => 'Test', 'onboarding_status' => 'completed']);
        app()->instance('tenant', $this->tenant);
    }

    #[Test]
    public function it_can_create_a_quiz()
    {
        $quiz = Quiz::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Math Quiz 1',
            'description' => 'Basic algebra',
            'time_limit' => 30,
            'duration_minutes' => 30,
            'passing_score' => 60,
            'is_randomized' => false,
        ]);

        $this->assertDatabaseHas('quizzes', ['title' => 'Math Quiz 1']);
        $this->assertEquals(30, $quiz->time_limit);
        $this->assertEquals(60, $quiz->passing_score);
    }

    #[Test]
    public function it_has_questions_relationship()
    {
        $quiz = Quiz::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Math Quiz 1',
        ]);

        Question::create([
            'tenant_id' => $this->tenant->id,
            'quiz_id' => $quiz->id,
            'question_text' => 'What is 2+2?',
            'question_type' => 'multiple_choice',
            'points' => 10,
        ]);

        $this->assertCount(1, $quiz->questions);
    }

    #[Test]
    public function it_has_attempts_relationship()
    {
        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Student',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        $quiz = Quiz::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Math Quiz 1',
        ]);

        QuizAttempt::create([
            'tenant_id' => $this->tenant->id,
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'score' => 80,
            'status' => 'completed',
        ]);

        $this->assertCount(1, $quiz->attempts);
    }

    #[Test]
    public function it_casts_is_randomized_to_boolean()
    {
        $quiz = Quiz::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Quiz',
            'is_randomized' => 1,
        ]);

        $this->assertIsBool($quiz->is_randomized);
        $this->assertTrue($quiz->is_randomized);
    }

    #[Test]
    public function it_has_a_category_relationship()
    {
        $category = \App\Models\QuestionCategory::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Mathematics',
        ]);

        $quiz = Quiz::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Quiz',
            'category_id' => $category->id,
        ]);

        $this->assertEquals('Mathematics', $quiz->category->name);
    }
}
