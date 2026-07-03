<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class DemoQuizSeeder extends Seeder
{
    public function run()
    {
        $tenant = Tenant::where('domain', 'remon')->first();

        if (! $tenant) {
            $this->command->info('Tenant remon not found.');

            return;
        }

        // Set tenant context
        app()->instance('tenant', $tenant);

        $course = Course::where('title', 'Laravel Mastery')->first();

        if (! $course) {
            $this->command->info('Course Laravel Mastery not found.');

            return;
        }

        $section = $course->sections()->first();

        // Create Quiz Lesson
        $lesson = Lesson::firstOrCreate([
            'section_id' => $section->id,
            'title' => 'Laravel Basics Quiz',
        ], [
            'type' => 'quiz',
            'sort_order' => 3,
        ]);

        // Create Quiz
        $quiz = Quiz::firstOrCreate([
            'lesson_id' => $lesson->id,
        ], [
            'title' => 'Laravel Basics Quiz',
            'description' => 'Test your knowledge of Laravel fundamentals.',
            'passing_score' => 70,
            'duration_minutes' => 10,
        ]);

        // Create Questions
        $q1 = $quiz->questions()->create([
            'content' => 'What is the command to create a new Laravel project?',
            'type' => 'mcq',
            'points' => 5,
        ]);
        $q1->options()->create(['content' => 'laravel new project', 'is_correct' => true]);
        $q1->options()->create(['content' => 'php artisan create', 'is_correct' => false]);

        $q2 = $quiz->questions()->create([
            'content' => 'Laravel is based on PHP.',
            'type' => 'true_false',
            'points' => 5,
        ]);
        $q2->options()->create(['content' => 'True', 'is_correct' => true]);
        $q2->options()->create(['content' => 'False', 'is_correct' => false]);

        $this->command->info('Demo quiz seeded.');
    }
}
