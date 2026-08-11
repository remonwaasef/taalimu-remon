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

    protected $lesson;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenant = Tenant::create(['domain' => 'test', 'name' => 'Test', 'onboarding_status' => 'completed']);
        app()->instance('tenant', $this->tenant);

        // Quizzes belong to a lesson (schema requires lesson_id)
        $course = \App\Models\Course::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Math Course',
        ]);
        $section = \App\Models\Section::create([
            'tenant_id' => $this->tenant->id,
            'course_id' => $course->id,
            'title' => 'Chapter 1',
        ]);
        $this->lesson = \App\Models\Lesson::create([
            'tenant_id' => $this->tenant->id,
            'section_id' => $section->id,
            'title' => 'Lesson 1',
        ]);
    }

    private function makeQuiz(array $overrides = []): Quiz
    {
        return Quiz::create(array_merge([
            'tenant_id' => $this->tenant->id,
            'lesson_id' => $this->lesson->id,
            'title' => 'Math Quiz 1',
        ], $overrides));
    }

    #[Test]
    public function it_can_create_a_quiz()
    {
        $quiz = $this->makeQuiz([
            'description' => 'Basic algebra',
            'duration_minutes' => 30,
            'passing_score' => 60,
            'is_randomized' => false,
        ]);

        $this->assertDatabaseHas('quizzes', ['title' => 'Math Quiz 1']);
        $this->assertEquals(30, $quiz->duration_minutes);
        $this->assertEquals(60, $quiz->passing_score);
    }

    #[Test]
    public function it_has_questions_relationship()
    {
        $quiz = $this->makeQuiz();

        Question::create([
            'tenant_id' => $this->tenant->id,
            'quiz_id' => $quiz->id,
            'content' => 'What is 2+2?',
            'type' => 'mcq',
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
            'password' => 'password',
            'role' => 'student',
        ]);

        $quiz = $this->makeQuiz();

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
        $quiz = $this->makeQuiz([
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

        $quiz = $this->makeQuiz([
            'title' => 'Quiz',
            'category_id' => $category->id,
        ]);

        $this->assertEquals('Mathematics', $quiz->category->name);
    }
}
