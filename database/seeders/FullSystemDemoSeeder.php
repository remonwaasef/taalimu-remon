<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Center\Models\Attendance;

class FullSystemDemoSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Demo Tenant
        $tenant = Tenant::updateOrCreate(
            ['domain' => 'demo-center'],
            [
                'name' => 'مركز الاختبار التجريبي',
                'status' => 'active',
            ]
        );

        // Set tenant context for scopes
        app()->instance('tenant', $tenant);
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);

        // 1.0 Create Tenant Roles (Critical for Spatie Teams)
        // 1.0 Ensure Global Roles Exist
        // We do NOT create tenant-specific roles here. We rely on the global roles created in RolesAndPermissionsSeeder.
        $roles = ['center_admin', 'instructor', 'student', 'secretary', 'accountant', 'staff'];
        foreach ($roles as $roleName) {
            $exists = \Spatie\Permission\Models\Role::where('name', $roleName)->whereNull('tenant_id')->exists();
            if (! $exists) {
                $this->command->warn("Warning: Global role '$roleName' not found! Make sure RolesAndPermissionsSeeder is run first.");
            }
        }

        // 1.1 Create Active Subscription for Demo Tenant
        Subscription::updateOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'default'],
            [
                'stripe_id' => 'sub_demo_'.$tenant->id,
                'stripe_status' => 'active',
                'stripe_price' => 'price_demo_pro',
                'quantity' => 1,
                'status' => 'active',
                'ends_at' => now()->addYear(),
            ]
        );

        // 2. Create Login Users (for demo purposes)
        // Admin
        $user = User::updateOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'مدير المركز',
                'password' => Hash::make('password'),
                'role' => 'center_admin',
                'tenant_id' => $tenant->id,
            ]
        );
        $user->assignRole('center_admin');

        // 3. Create Instructors (Records)
        $inst1 = Instructor::updateOrCreate(
            ['email' => 'instructor1@demo.com', 'tenant_id' => $tenant->id],
            [
                'name' => 'أحمد محمد (رياضيات)',
                'specialization' => 'الرياضيات المتقدمة',
                'bio' => 'مدرس خبير في الرياضيات لأكثر من 10 سنوات.',
            ]
        );
        $userInst1 = User::updateOrCreate(
            ['email' => $inst1->email],
            [
                'name' => $inst1->name,
                'password' => Hash::make('password'),
                'role' => 'instructor',
                'tenant_id' => $tenant->id,
                'instructor_id' => $inst1->id,
                'qr_identifier' => \Illuminate\Support\Str::random(32),
            ]
        );
        // Fetch Global Role Object to ensure we link to the correct ID
        $instructorRole = \Spatie\Permission\Models\Role::where('name', 'instructor')->whereNull('tenant_id')->first();
        $userInst1->assignRole($instructorRole);
        $inst1->update(['user_id' => $userInst1->id]);

        $inst2 = Instructor::updateOrCreate(
            ['email' => 'instructor2@demo.com', 'tenant_id' => $tenant->id],
            [
                'name' => 'سارة علي (علوم)',
                'specialization' => 'الفيزياء والكيمياء',
                'bio' => 'متخصصة في تبسيط العلوم للطلاب.',
            ]
        );
        $userInst2 = User::updateOrCreate(
            ['email' => $inst2->email],
            [
                'name' => $inst2->name,
                'password' => Hash::make('password'),
                'role' => 'instructor',
                'tenant_id' => $tenant->id,
                'instructor_id' => $inst2->id,
                'qr_identifier' => \Illuminate\Support\Str::random(32),
            ]
        );
        // Fetch Global Role Object to ensure we link to the correct ID
        $instructorRole = \Spatie\Permission\Models\Role::where('name', 'instructor')->whereNull('tenant_id')->first();
        $userInst2->assignRole($instructorRole);
        $inst2->update(['user_id' => $userInst2->id]);

        // 4. Create Students (Records)
        $students = [];
        for ($i = 1; $i <= 10; $i++) {
            $student = Student::updateOrCreate(
                ['email' => "student$i@demo.com", 'tenant_id' => $tenant->id],
                [
                    'name' => 'طالب تجريبي '.$i,
                    'phone' => '012'.str_pad($i, 8, '0', STR_PAD_LEFT),
                    'grade_level' => 'الصف الثالث الثانوي',
                    'status' => 'active',
                ]
            );
            $students[] = $student;
        }

        // 5. Classrooms
        $room1 = Classroom::updateOrCreate(['tenant_id' => $tenant->id, 'name' => 'القاعة الكبرى'], ['capacity' => 50]);
        $room2 = Classroom::updateOrCreate(['tenant_id' => $tenant->id, 'name' => 'معمل الحاسب'], ['capacity' => 20]);

        // 6. Courses
        $course1 = Course::updateOrCreate(
            ['tenant_id' => $tenant->id, 'title' => 'دورة الرياضيات المتقدمة'],
            [
                'instructor_id' => $inst1->id,
                'description' => 'شرح وافٍ لمنهج الرياضيات بالكامل مع حل تدريبات مكثفة.',
                'price' => 500.00,
                'registration_token' => \Illuminate\Support\Str::random(16),
                'status' => 'published',
            ]
        );

        $course2 = Course::updateOrCreate(
            ['tenant_id' => $tenant->id, 'title' => 'أساسيات الفيزياء'],
            [
                'instructor_id' => $inst2->id,
                'description' => 'تبسيط مفاهيم الميكانيكا والطاقة لطلاب المرحلة الثانوية.',
                'price' => 350.00,
                'registration_token' => \Illuminate\Support\Str::random(16),
                'status' => 'published',
            ]
        );

        // 7. Sections & Lessons
        foreach ([$course1, $course2] as $course) {
            $section = Section::updateOrCreate(['course_id' => $course->id, 'title' => 'الوحدة الأولى: الأساسيات'], ['sort_order' => 1]);

            // Text Lesson
            $textLesson = Lesson::updateOrCreate(
                ['section_id' => $section->id, 'title' => 'مقدمة عن الدورة'],
                [
                    'type' => 'text',
                    'content' => 'أهلاً بكم في دورة '.$course->title,
                    'sort_order' => 1,
                    'is_free' => true,
                ]
            );

            // Quiz Lesson
            $quizLessonItem = Lesson::updateOrCreate(
                ['section_id' => $section->id, 'title' => 'اختبار تشخيصي'],
                [
                    'type' => 'quiz',
                    'sort_order' => 2,
                ]
            );
            $quiz = Quiz::updateOrCreate(
                ['lesson_id' => $quizLessonItem->id],
                [
                    'title' => 'اختبار تحديد المستوى',
                    'passing_score' => 50,
                    'duration_minutes' => 15,
                ]
            );

            $q1 = Question::updateOrCreate(
                ['quiz_id' => $quiz->id, 'content' => 'هل تعتبر هذه الدورة مخصصة للمبتدئين؟'],
                ['type' => 'true_false', 'points' => 10]
            );
            QuestionOption::updateOrCreate(['question_id' => $q1->id, 'content' => 'نعم'], ['is_correct' => true]);
            QuestionOption::updateOrCreate(['question_id' => $q1->id, 'content' => 'لا'], ['is_correct' => false]);

            // Assignment Lesson
            $assignmentLessonItem = Lesson::updateOrCreate(
                ['section_id' => $section->id, 'title' => 'التكليف الأول'],
                [
                    'type' => 'assignment',
                    'sort_order' => 3,
                ]
            );
            Assignment::updateOrCreate(
                ['lesson_id' => $assignmentLessonItem->id],
                [
                    'title' => 'واجب المحاضرة الأولى',
                    'max_score' => 100,
                    'due_date' => now()->addDays(3),
                ]
            );
        }

        // 8. Schedules
        $days = [0, 1, 2, 3, 4]; // Sun to Thu
        foreach ($days as $day) {
            Schedule::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'course_id' => $course1->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00',
                ],
                [
                    'classroom_id' => $room1->id,
                    'instructor_id' => $inst1->id,
                    'end_time' => '11:00',
                ]
            );

            Schedule::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'course_id' => $course2->id,
                    'day_of_week' => $day,
                    'start_time' => '12:00',
                ],
                [
                    'classroom_id' => $room2->id,
                    'instructor_id' => $inst2->id,
                    'end_time' => '14:00',
                ]
            );
        }

        // 9. Enrollments & Sales & Attendance
        foreach ($students as $index => $student) {
            $enrolledCourse = ($index % 2 == 0) ? $course1 : $course2;

            // Sync user
            $user = User::updateOrCreate(
                ['email' => $student->email],
                [
                    'name' => $student->name,
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'tenant_id' => $tenant->id,
                    'qr_identifier' => \Illuminate\Support\Str::random(32),
                ]
            );
            // Fetch Global Role Object to ensure we link to the correct ID
            $studentRole = \Spatie\Permission\Models\Role::where('name', 'student')->whereNull('tenant_id')->first();
            $user->assignRole($studentRole);

            $student->update(['user_id' => $user->id]);

            Enrollment::updateOrCreate(
                ['user_id' => $user->id, 'course_id' => $enrolledCourse->id],
                [
                    'status' => 'active',
                    'enrolled_at' => now()->subDays(10),
                    'tenant_id' => $tenant->id,
                ]
            );

            // Sale (using updateOrCreate on notes suggests it was created by seeder)
            $sale = Sale::updateOrCreate(
                ['tenant_id' => $tenant->id, 'student_id' => $student->id, 'notes' => 'تسجيل يدوي من Seeder'],
                [
                    'total_amount' => $enrolledCourse->price,
                    'paid_amount' => $enrolledCourse->price,
                    'status' => 'paid',
                    'payment_method' => 'cash',
                ]
            );

            SaleItem::updateOrCreate(
                ['sale_id' => $sale->id, 'item_id' => $enrolledCourse->id, 'item_type' => Course::class],
                [
                    'price' => $enrolledCourse->price,
                    'quantity' => 1,
                ]
            );

            // Attendance
            $courseSchedules = Schedule::where('course_id', $enrolledCourse->id)->get();
            foreach ($courseSchedules->take(2) as $sched) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'schedule_id' => $sched->id,
                        'session_date' => now()->subDays(2)->format('Y-m-d'),
                    ],
                    [
                        'tenant_id' => $tenant->id,
                        'course_id' => $enrolledCourse->id,
                        'status' => 'present',
                        'check_in_time' => now()->subDays(2)->setTime(9, 0),
                    ]
                );
            }
        }

        echo "✅ تمت عملية إنشاء البيانات التجريبية بنجاح بنظام مرن (Idempotent)!\n";
        echo "نطاق المركز: demo-center\n";
        echo "البريد: admin@demo.com | كلمة السر: password\n";
    }
}
