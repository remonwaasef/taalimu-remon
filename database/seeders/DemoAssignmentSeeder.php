<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Assignment;
use App\Models\Tenant;

class DemoAssignmentSeeder extends Seeder
{
    public function run()
    {
        $tenant = Tenant::where('domain', 'remon')->first();
        
        if (!$tenant) {
            $this->command->info('Tenant remon not found.');
            return;
        }

        // Set tenant context
        app()->instance('tenant', $tenant);

        $course = Course::where('title', 'Laravel Mastery')->first();
        
        if (!$course) {
            $this->command->info('Course Laravel Mastery not found.');
            return;
        }

        $section = $course->sections()->first();

        // Create Assignment Lesson
        $lesson = Lesson::firstOrCreate([
            'section_id' => $section->id,
            'title' => 'Project Submission',
        ], [
            'type' => 'text', // Using text type for assignment lesson container
            'sort_order' => 4,
            'content' => 'Please submit your final project here.',
        ]);

        // Create Assignment
        $assignment = Assignment::firstOrCreate([
            'lesson_id' => $lesson->id,
        ], [
            'title' => 'Build a Blog',
            'description' => 'Create a simple blog using Laravel. Submit the source code as a ZIP file.',
            'due_date' => now()->addDays(7),
            'max_score' => 100,
        ]);

        $this->command->info('Demo assignment seeded.');
    }
}
