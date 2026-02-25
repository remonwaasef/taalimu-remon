<?php

namespace App\Services;

use App\Models\Instructor;
use App\Models\Course;
use App\Models\Student;
use App\Models\Stage;
use App\Models\Grade;
use App\Models\User;
use App\DTOs\CourseData;
use App\DTOs\StudentData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemoDataService
{
    protected $courseService;
    protected $studentService;
    protected $financeService;

    public function __construct(CourseService $courseService, StudentService $studentService, FinanceService $financeService)
    {
        $this->courseService = $courseService;
        $this->studentService = $studentService;
        $this->financeService = $financeService;
    }

    public function seedForTenant($tenant)
    {
        return DB::transaction(function () use ($tenant) {
            // 1. Create Academic Structure if not exists
            $stage = Stage::firstOrCreate(
                ['tenant_id' => $tenant->id, 'name' => 'المرحلة الثانوية'],
                ['order' => 1]
            );

            $grade = Grade::firstOrCreate(
                ['tenant_id' => $tenant->id, 'stage_id' => $stage->id, 'name' => 'الصف الثالث الثانوي'],
                ['order' => 3]
            );

            // 2. Create Instructors
            $instructorsData = [
                [
                    'name' => 'أحمد محمد علي',
                    'specialization' => 'اللغة العربية',
                    'email' => 'ahmed.demo@' . $tenant->domain,
                    'status' => 'active',
                ],
                [
                    'name' => 'سارة حسن',
                    'specialization' => 'الرياضيات',
                    'email' => 'sara.demo@' . $tenant->domain,
                    'status' => 'active',
                ]
            ];

            $instructors = [];
            foreach ($instructorsData as $data) {
                $instructors[] = Instructor::create(array_merge($data, [
                    'tenant_id' => $tenant->id,
                    'phone' => '01' . rand(100000000, 999999999),
                    'commission_rate' => 20,
                    'commission_type' => 'percentage',
                ]));
            }

            // 3. Create Courses
            $courses = [];
            $coursesData = [
                ['title' => 'دورة النحو الشاملة', 'instructor_id' => $instructors[0]->id, 'price' => 500],
                ['title' => 'مراجعة التفاضل والتكامل', 'instructor_id' => $instructors[1]->id, 'price' => 450],
            ];

            foreach ($coursesData as $data) {
                $courses[] = Course::create(array_merge($data, [
                    'tenant_id' => $tenant->id,
                    'description' => 'دورة تجريبية لاستكشاف مميزات النظام.',
                    'sessions_count' => 12,
                    'status' => 'published',
                ]));
            }

            // 4. Create Students & Enrollments & Sales
            $studentsNames = ['ياسين خالد', 'مريم إبراهيم', 'عمر يوسف', 'ليلى عبد الله', 'حمزة علي'];
            
            foreach ($studentsNames as $index => $name) {
                $studentEmail = Str::slug($name, '.') . '.demo' . $index . '@' . $tenant->domain;
                
                // Register Student
                $sData = StudentData::fromArray([
                    'name' => $name,
                    'email' => $studentEmail,
                    'phone' => '01' . rand(100000000, 999999999),
                    'grade_id' => $grade->id,
                    'password' => 'password123',
                ]);

                $result = $this->studentService->registerStudent($sData, auth()->user());
                $student = $result['student'];

                // Enroll in a random course
                $course = $courses[array_rand($courses)];
                
                // Create Sale/Invoice
                $this->financeService->createSale([
                    'student_id' => $student->id,
                    'items' => [['id' => $course->id, 'price' => $course->price]],
                    'payment_method' => 'cash',
                    'paid_amount' => $index % 2 == 0 ? $course->price : 0, // Some paid, some debt
                    'status' => $index % 2 == 0 ? 'paid' : 'pending',
                ]);
            }

            return true;
        });
    }
}
