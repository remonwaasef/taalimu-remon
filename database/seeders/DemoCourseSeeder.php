<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\Tenant;

class DemoCourseSeeder extends Seeder
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

        $instructor = User::where('email', 'remon@remon.com')->first();

        // Create Course
        $course = Course::firstOrCreate([
            'title' => 'Laravel Mastery',
            'tenant_id' => $tenant->id,
        ], [
            'instructor_id' => $instructor->id,
            'description' => 'Master Laravel from scratch.',
            'price' => 99.99,
            'status' => 'published',
        ]);

        // Create Sections
        $section1 = Section::firstOrCreate([
            'course_id' => $course->id,
            'title' => 'Introduction',
        ], ['sort_order' => 1]);

        $section2 = Section::firstOrCreate([
            'course_id' => $course->id,
            'title' => 'Advanced Concepts',
        ], ['sort_order' => 2]);

        // Create Lessons
        Lesson::firstOrCreate([
            'section_id' => $section1->id,
            'title' => 'Welcome to the Course',
        ], [
            'type' => 'video',
            'content' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Rick Roll for demo :D
            'duration' => 5,
            'sort_order' => 1,
            'is_free' => true,
        ]);

        Lesson::firstOrCreate([
            'section_id' => $section1->id,
            'title' => 'Setup Environment',
        ], [
            'type' => 'text',
            'content' => '<h3>Install PHP and Composer</h3><p>Follow the official documentation.</p>',
            'duration' => 15,
            'sort_order' => 2,
        ]);

        Lesson::firstOrCreate([
            'section_id' => $section2->id,
            'title' => 'Service Containers',
        ], [
            'type' => 'video',
            'content' => 'https://www.youtube.com/watch?v=yXJ9E7X3Z4c',
            'duration' => 20,
            'sort_order' => 1,
        ]);

        // Enroll User
        Enrollment::firstOrCreate([
            'user_id' => $instructor->id,
            'course_id' => $course->id,
        ], [
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        $this->command->info('Demo course seeded and user enrolled.');
    }
}
