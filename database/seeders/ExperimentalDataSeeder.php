<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Instructor;
use Modules\Center\Models\Attendance;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Enrollment;
use App\Models\Subscription;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ExperimentalDataSeeder extends Seeder
{
    public function run()
    {
        $faker_mode = false; // Faker removed

        $centers = [
            [
                'domain' => 'exp-smart-center',
                'name' => 'مركز الذكاء التعليمي',
                'specialization' => 'Mathematics & Science',
            ],
            [
                'domain' => 'exp-future-academy',
                'name' => 'أكاديمية المستقبل',
                'specialization' => 'Languages',
            ],
            [
                'domain' => 'exp-excellence-hub',
                'name' => 'دار التميز التعليمي',
                'specialization' => 'General Education',
            ],
        ];

        foreach ($centers as $centerData) {
            $this->command->info("Seeding center: {$centerData['name']}");

            // 1. Create Tenant
            $tenant = Tenant::updateOrCreate(
                ['domain' => $centerData['domain']],
                [
                    'name' => $centerData['name'],
                    'status' => 'active',
                    'email' => "info@{$centerData['domain']}.com",
                ]
            );

            // Set tenant context
            app()->instance('tenant', $tenant);
            app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);

            // 2. Active Subscription
            Subscription::updateOrCreate(
                ['tenant_id' => $tenant->id, 'name' => 'default'],
                [
                    'stripe_id' => 'sub_exp_' . $tenant->id,
                    'stripe_status' => 'active',
                    'stripe_price' => 'price_premium',
                    'quantity' => 1,
                    'status' => 'active',
                    'ends_at' => now()->addYear(),
                ]
            );

            // 3. Admin User
            $admin = User::updateOrCreate(
                ['email' => "admin@{$centerData['domain']}.com"],
                [
                    'name' => 'مدير ' . $centerData['name'],
                    'password' => Hash::make('password'),
                    'role' => 'center_admin',
                    'tenant_id' => $tenant->id,
                ]
            );
            $admin->assignRole('center_admin');

            // 4. Instructors
            $instructors = [];
            for ($i = 1; $i <= 3; $i++) {
                $instructor = Instructor::updateOrCreate(
                    ['email' => "instructor{$i}@{$centerData['domain']}.com", 'tenant_id' => $tenant->id],
                    [
                        'name' => "مدرس تجريبي {$i}",
                        'specialization' => $centerData['specialization'],
                        'bio' => 'مدرس متخصص وخبير في مجاله لسنوات طويلة.',
                        'phone' => '010' . str_pad($i, 8, '0', STR_PAD_LEFT),
                    ]
                );
                
                // Also create User for instructor if they need to login
                $user = User::updateOrCreate(
                    ['email' => $instructor->email],
                    [
                        'name' => $instructor->name,
                        'password' => Hash::make('password'),
                        'role' => 'instructor',
                        'tenant_id' => $tenant->id,
                    ]
                );
                $user->assignRole('instructor');
                $instructor->update(['user_id' => $user->id]);
                
                $instructors[] = $instructor;
            }

            // 5. Classrooms
            $rooms = [];
            $roomNames = ['قاعة الفارابي', 'قاعة ابن سينا', 'قاعة الخوارزمي'];
            foreach ($roomNames as $index => $name) {
                $rooms[] = Classroom::updateOrCreate(
                    ['tenant_id' => $tenant->id, 'name' => $name],
                    ['capacity' => ($index + 1) * 20]
                );
            }

            // 6. Courses
            $courses = [];
            $courseTitles = [
                'Mathematics & Science' => ['الرياضيات التطبيقية', 'أساسيات الكيمياء', 'الفيزياء الحديثة'],
                'Languages' => ['اللغة العربية الفصحى', 'اللغة الإنجليزية المكثفة', 'قواعد اللغة الفرنسية'],
                'General Education' => ['مهارات التفوق الدراسي', 'التاريخ الحديث', 'الجغرافيا البشرية']
            ];

            $titles = $courseTitles[$centerData['specialization']];
            foreach ($titles as $index => $title) {
                $courses[] = Course::updateOrCreate(
                    ['tenant_id' => $tenant->id, 'title' => $title],
                    [
                        'instructor_id' => $instructors[$index % count($instructors)]->id,
                        'description' => "دورة تدريبية متكاملة في " . $title,
                        'price' => 500 + ($index * 100),
                        'status' => 'published',
                    ]
                );
            }

            // 7. Students (20 students per center)
            $students = [];
            for ($i = 1; $i <= 20; $i++) {
                $student = Student::updateOrCreate(
                    ['email' => "student{$i}@{$centerData['domain']}.com", 'tenant_id' => $tenant->id],
                    [
                        'name' => "طالب تجريبي {$i}",
                        'phone' => '012' . str_pad($i, 8, '0', STR_PAD_LEFT),
                        'grade_level' => (string)(($i % 12) + 1),
                        'status' => 'active',
                        'joined_at' => now()->subMonths(2),
                    ]
                );

                $user = User::updateOrCreate(
                    ['email' => $student->email],
                    [
                        'name' => $student->name,
                        'password' => Hash::make('password'),
                        'role' => 'student',
                        'tenant_id' => $tenant->id,
                    ]
                );
                $studentRole = \Spatie\Permission\Models\Role::where('name', 'student')->whereNull('tenant_id')->first();
                $user->assignRole($studentRole);
                $student->update(['user_id' => $user->id]);

                $students[] = $student;

                // Enroll student in first 2 courses
                $enrolledCourses = array_slice($courses, 0, 2);
                foreach ($enrolledCourses as $course) {
                    Enrollment::updateOrCreate(
                        ['user_id' => $user->id, 'course_id' => $course->id],
                        [
                            'status' => 'active',
                            'enrolled_at' => now()->subDays(rand(10, 30)),
                            'tenant_id' => $tenant->id,
                        ]
                    );

                    // Financial Records (Sale & Payment)
                    $sale = Sale::updateOrCreate(
                        ['tenant_id' => $tenant->id, 'student_id' => $student->id, 'notes' => 'Generated by Experimental Seeder'],
                        [
                            'total_amount' => $course->price,
                            'paid_amount' => $course->price,
                            'status' => 'paid',
                            'payment_method' => 'cash',
                        ]
                    );

                    SaleItem::updateOrCreate(
                        ['sale_id' => $sale->id, 'item_id' => $course->id, 'item_type' => Course::class],
                        [
                            'price' => $course->price,
                            'quantity' => 1,
                        ]
                    );

                    Payment::create([
                        'tenant_id' => $tenant->id,
                        'sale_id' => $sale->id,
                        'amount' => $course->price,
                        'payment_method' => 'cash',
                        'received_by' => $admin->id,
                        'paid_at' => now()->subDays(rand(1, 10)),
                    ]);
                }
            }

            // 8. Schedules and Attendance
            foreach ($courses as $index => $course) {
                $days = [0, 2, 4]; // Sun, Tue, Thu
                foreach ($days as $day) {
                    $schedule = Schedule::updateOrCreate(
                        [
                            'tenant_id' => $tenant->id,
                            'course_id' => $course->id,
                            'day_of_week' => $day,
                            'start_time' => '10:00'
                        ],
                        [
                            'classroom_id' => $rooms[$index % count($rooms)]->id,
                            'instructor_id' => $course->instructor_id,
                            'end_time' => '12:00',
                        ]
                    );

                    // Attendance for the last 2 sessions
                    $attendanceDates = [now()->subDays(2), now()->subDays(9)];
                    foreach ($attendanceDates as $date) {
                        if ($date->dayOfWeek == $day) {
                            foreach ($students as $student) {
                                // Only attend if enrolled
                                if (Enrollment::where('user_id', $student->user_id)->where('course_id', $course->id)->exists()) {
                                    Attendance::updateOrCreate(
                                        [
                                            'student_id' => $student->id,
                                            'schedule_id' => $schedule->id,
                                            'session_date' => $date->format('Y-m-d')
                                        ],
                                        [
                                            'tenant_id' => $tenant->id,
                                            'course_id' => $course->id,
                                            'status' => 'present',
                                            'check_in_time' => $date->copy()->setTime(10, 0),
                                        ]
                                    );
                                }
                            }
                        }
                    }
                }
            }

            // 9. Quizzes & Exams
            foreach ($courses as $course) {
                $section = Section::updateOrCreate(['course_id' => $course->id, 'title' => 'المحتوى العام'], ['sort_order' => 1]);
                
                $lesson = Lesson::updateOrCreate(
                    ['section_id' => $section->id, 'title' => 'اختبار منتصف الدورة'],
                    ['type' => 'quiz', 'sort_order' => 1]
                );

                $quiz = Quiz::updateOrCreate(
                    ['lesson_id' => $lesson->id],
                    [
                        'title' => 'اختبار تقييمي',
                        'passing_score' => 60,
                        'duration_minutes' => 30
                    ]
                );

                $q = Question::updateOrCreate(
                    ['quiz_id' => $quiz->id, 'content' => 'السؤال الأول من الاختبار التجريبي؟'],
                    ['type' => 'mcq', 'points' => 10]
                );
                QuestionOption::updateOrCreate(['question_id' => $q->id, 'content' => 'الخيار الصحيح'], ['is_correct' => true]);
                QuestionOption::updateOrCreate(['question_id' => $q->id, 'content' => 'الخيار الخاطئ'], ['is_correct' => false]);
            }
        }

        $this->command->info("✅ جميع البيانات التجريبية تم إنشاؤها بنجاح!");
        $this->command->warn("يمنك استخدام هذه البيانات للوصول للمراكز التالية:");
        foreach ($centers as $center) {
            $this->command->line("- " . $center['name'] . ": admin@{$center['domain']}.com | password");
        }
    }
}
