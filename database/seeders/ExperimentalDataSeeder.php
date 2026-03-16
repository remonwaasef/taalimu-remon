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
            // 1. Create Tenant (Type: instructor)
            $tenant = Tenant::updateOrCreate(
                ['domain' => $data['domain']],
                [
                    'name' => $data['name'],
                    'status' => 'active',
                    'type' => 'instructor', // CRITICAL: Identify as an independent instructor
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
                    'stripe_price' => 'price_basic', // Starter plan for independent teachers
                ]
            );

            // 3. Create Instructor User (Role: instructor)
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => 'instructor', // Changed from center_admin
                    'tenant_id' => $tenant->id,
                    'email_verified_at' => now(), // Bypass verification
                ]
            );
            
            // Assign Role (Forceful to bypass observers and handle team context)
            \Illuminate\Support\Facades\DB::table('model_has_roles')->where('model_id', $user->id)->delete();
            $role = \App\Models\Role::where('name', 'instructor')->whereNull('tenant_id')->first();
            if ($role) {
                \Illuminate\Support\Facades\DB::table('model_has_roles')->insert([
                    'role_id' => $role->id,
                    'model_type' => User::class,
                    'model_id' => $user->id,
                    'tenant_id' => $tenant->id,
                ]);
            }
            $user->updateQuietly(['role' => 'instructor']);

            // 4. Create Instructor Record
            $instructor = Instructor::updateOrCreate(
                ['email' => $data['email'], 'tenant_id' => $tenant->id],
                [
                    'user_id' => $user->id,
                    'name' => $data['name'],
                    'specialization' => $data['specialization'],
                    'status' => 'active',
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
                            'email_verified_at' => now(), // Bypass verification
                        ]
                    );

                    // Assign Role (Forceful)
                    \Illuminate\Support\Facades\DB::table('model_has_roles')->where('model_id', $studentUser->id)->delete();
                    $sRole = \App\Models\Role::where('name', 'student')->whereNull('tenant_id')->first();
                    if ($sRole) {
                        \Illuminate\Support\Facades\DB::table('model_has_roles')->insert([
                            'role_id' => $sRole->id,
                            'model_type' => User::class,
                            'model_id' => $studentUser->id,
                            'tenant_id' => $tenant->id,
                        ]);
                    }
                    $studentUser->updateQuietly(['role' => 'student']);

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
