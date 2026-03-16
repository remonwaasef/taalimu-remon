<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Course;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Enrollment;
use App\Models\Stage;
use App\Models\Grade;
use App\Models\Subscription;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ExperimentalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instructorsData = [
            [
                'domain' => 'teacher1',
                'name' => 'أ. محمد الزيات',
                'email' => 'teacher1@example.com',
                'specialization' => 'اللغة العربية',
                'courses' => ['بلاغة ونقد', 'نحو وصرف'],
            ],
            [
                'domain' => 'teacher2',
                'name' => 'د. سارة المنصوري',
                'email' => 'teacher2@example.com',
                'specialization' => 'الكيمياء',
                'courses' => ['الكيمياء العضوية', 'الكيمياء التحليلية'],
            ],
        ];

        foreach ($instructorsData as $data) {
            // 1. Create Tenant
            $tenant = Tenant::updateOrCreate(
                ['domain' => $data['domain']],
                [
                    'name' => 'مركز ' . $data['name'],
                    'status' => 'active',
                ]
            );

            // Set tenant context
            app()->instance('tenant', $tenant);
            if (class_exists(\Spatie\Permission\PermissionRegistrar::class)) {
                app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
            }

            // 2. Create Active Subscription
            Subscription::updateOrCreate(
                ['tenant_id' => $tenant->id, 'name' => 'default'],
                [
                    'stripe_id' => 'sub_exp_' . $tenant->id,
                    'stripe_status' => 'active',
                    'quantity' => 1,
                    'status' => 'active',
                    'ends_at' => now()->addYear(),
                ]
            );

            // 3. Create Instructor User
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => 'center_admin', // As an independent teacher, he/she is the admin
                    'tenant_id' => $tenant->id,
                ]
            );
            
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('center_admin');
            }

            // 4. Create Instructor Record
            $instructor = Instructor::updateOrCreate(
                ['email' => $data['email'], 'tenant_id' => $tenant->id],
                [
                    'user_id' => $user->id,
                    'name' => $data['name'],
                    'specialization' => $data['specialization'],
                ]
            );
            $user->update(['instructor_id' => $instructor->id]);

            // 5. Create Stage and Grade
            $stage = Stage::updateOrCreate(
                ['tenant_id' => $tenant->id, 'name' => 'المرحلة الثانوية'],
                []
            );
            $grade = Grade::updateOrCreate(
                ['tenant_id' => $tenant->id, 'stage_id' => $stage->id, 'name' => 'الصف الثالث الثانوي'],
                []
            );

            // 6. Create Courses and Students
            foreach ($data['courses'] as $courseTitle) {
                $course = Course::updateOrCreate(
                    ['tenant_id' => $tenant->id, 'title' => $courseTitle],
                    [
                        'instructor_id' => $instructor->id,
                        'price' => rand(200, 500),
                        'status' => 'published',
                        'registration_token' => Str::random(16),
                    ]
                );

                // Create 50 students for each course
                for ($i = 1; $i <= 50; $i++) {
                    $studentEmail = "student_{$data['domain']}_c" . Str::slug($courseTitle) . "_{$i}@example.com";
                    
                    $studentUser = User::updateOrCreate(
                        ['email' => $studentEmail],
                        [
                            'name' => "طالب تجريبي " . $i,
                            'password' => Hash::make('password'),
                            'role' => 'student',
                            'tenant_id' => $tenant->id,
                            'qr_identifier' => Str::random(12),
                        ]
                    );

                    if (method_exists($studentUser, 'assignRole')) {
                        $studentUser->assignRole('student');
                    }

                    $student = Student::updateOrCreate(
                        ['email' => $studentEmail, 'tenant_id' => $tenant->id],
                        [
                            'user_id' => $studentUser->id,
                            'grade_id' => $grade->id,
                            'grade_level' => '12',
                            'name' => $studentUser->name,
                            'status' => 'active',
                        ]
                    );

                    Enrollment::updateOrCreate(
                        ['user_id' => $studentUser->id, 'course_id' => $course->id],
                        [
                            'tenant_id' => $tenant->id,
                            'status' => 'active',
                            'enrolled_at' => now(),
                        ]
                    );
                }
            }
        }

        $this->command->info('✅ تم إنشاء البيانات التجريبية لـ 2 معلمين مستقلين بنجاح!');
        $this->command->info('قناة المعلم 1: teacher1.domain.com | الإيميل: teacher1@example.com');
        $this->command->info('قناة المعلم 2: teacher2.domain.com | الإيميل: teacher2@example.com');
        $this->command->info('كلمة المرور للجميع: password');
    }
}
