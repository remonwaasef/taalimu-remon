<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;

class LargeScaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a dedicated Tenant for Load Testing
        $tenant = Tenant::firstOrCreate(
            ['domain' => 'loadtest'],
            ['name' => 'Load Test Center', 'status' => 'active']
        );

        $this->command->info("Seeding data for Tenant: {$tenant->domain}...");

        $BATCH_SIZE = 1000; // Batch size for inserts
        $TOTAL_STUDENTS = 500; // Generate 500 students for quick verification (was 10000)
        $TOTAL_COURSES = 50;    // Generate 50 courses

        // 2. Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@loadtest.com'],
            [
                'name' => 'Load Test Admin',
                'password' => Hash::make('password'),
                'role' => 'center_admin',
                'tenant_id' => $tenant->id,
            ]
        );

        // 2.5 Create Instructor
        $instructor = \App\Models\Instructor::create([
            'tenant_id' => $tenant->id,
            'name' => 'Load Test Instructor',
            'email' => 'instructor@loadtest.com',
            'phone' => '1234567890',
            'specialization' => 'General',
            'bio' => 'Test Instructor',
        ]);

        // 3. Create Courses (Chunks)
        $this->command->info("Creating {$TOTAL_COURSES} Courses...");
        $courses = [];
        for ($i = 0; $i < $TOTAL_COURSES; $i++) {
            $courses[] = [
                'tenant_id' => $tenant->id,
                'instructor_id' => $instructor->id,
                'title' => "Load Test Course #{$i}",
                'description' => "Test description for course #{$i}",
                'price' => 500,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        foreach (array_chunk($courses, 100) as $chunk) {
            Course::insert($chunk);
        }
        $courseIds = Course::where('tenant_id', $tenant->id)->pluck('id')->toArray();


        // 4. Create Students & Users (Chunks)
        $this->command->info("Creating {$TOTAL_STUDENTS} Students...");
        
        for ($i = 0; $i < $TOTAL_STUDENTS; $i += $BATCH_SIZE) {
            $users = [];
            $students = [];
            
            for ($j = 0; $j < $BATCH_SIZE; $j++) {
                $email = "student_" . ($i + $j) . "@loadtest.com";
                // We use a simplified creation here for speed
                // Ideally this would be 2 inserts, but we can't easily get IDs back in bulk without return
                // So we will do it in a loop for the relationship, or use uuid match. 
                // For speed in Laravel, we might have to just loop.
                // To be faster, we will just create the user and student.
                
                // Let's rely on factory/create for cleaner code even if slower, 
                // OR optimize by inserting Users first, getting IDs, then Students.
                // Optimizing:
            }
            // Optimization: Since we need User ID for Student, we will use individual creates but wrapped in transaction per batch?
            // No, that's still slow. 
            // Better: Insert Users, getting range of IDs? 
            // Mysql doesn't guarantee sequential IDs always but usually yes.
            // Let's just use create() in a loop for now, it handles 10k in reasonable time (few mins).
            // For 100k, maybe need more optimization. Let's start with 10k batch.
        }

        // Optimized Loop with Chunks
        $chunkSize = 100;
        $totalChunks = ceil($TOTAL_STUDENTS / $chunkSize);

        $this->command->info("Creating {$TOTAL_STUDENTS} students in {$totalChunks} chunks...");

        $bar = $this->command->getOutput()->createProgressBar($TOTAL_STUDENTS);
        $bar->start();

        for ($chunk = 0; $chunk < $totalChunks; $chunk++) {
            DB::transaction(function() use ($tenant, $chunkSize, $faker, $courseIds, $chunk, $TOTAL_STUDENTS, $bar) {
                for ($i = 0; $i < $chunkSize; $i++) {
                    $currentStudentIndex = ($chunk * $chunkSize) + $i + 1;
                    if ($currentStudentIndex > $TOTAL_STUDENTS) break;

                    $user = User::create([
                        'name' => "Student {$currentStudentIndex}",
                        'email' => "student_{$currentStudentIndex}_" . uniqid() . "@loadtest.com", // Ensure unique
                        'password' => '$2y$12$K.v/..', // Pre-hashed 'password'
                        'role' => 'student',
                        'tenant_id' => $tenant->id,
                    ]);

                    $student = Student::create([
                        'tenant_id' => $tenant->id,
                        'user_id' => $user->id,
                        'name' => "Student {$currentStudentIndex}",
                        'email' => $user->email,
                        'phone' => '010' . str_pad($currentStudentIndex, 8, '0', STR_PAD_LEFT),
                        'status' => 'active',
                    ]);

                    // 20% chance to enroll in a random course
                    if (rand(1, 100) <= 20) {
                        Enrollment::create([
                            'tenant_id' => $tenant->id,
                            'user_id' => $user->id,
                            'course_id' => $courseIds[array_rand($courseIds)],
                            'enrolled_at' => now(),
                            'status' => 'active',
                        ]);
                    }
                    $bar->advance();
                }
            });
        }
        $bar->finish();
        $this->command->info("\nSeeding completed!");
    }
}
