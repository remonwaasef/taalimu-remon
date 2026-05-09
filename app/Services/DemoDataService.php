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
use App\Services\SubscriptionService;

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
        SubscriptionService::silence(true);
        try {
            return DB::transaction(function () use ($tenant) {
            // 1. Create Academic Structure if not exists
            $stage = Stage::firstOrCreate(
                [__('services.string_66')],
                ['order' => 1]
            );

            $grade = Grade::firstOrCreate(
                [__('services.string_67')],
                ['order' => 3]
            );

            // 2. Create Instructors
            $instructorsData = [
                [
                    __('services.string_68'),
                    __('services.string_69'),
                    'email' => 'ahmed.demo@' . $tenant->domain,
                    'status' => 'active',
                ],
                [
                    __('services.string_70'),
                    __('services.string_71'),
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
                [__('services.string_72'), 'instructor_id' => $instructors[0]->id, 'price' => 500],
                [__('services.string_73'), 'instructor_id' => $instructors[1]->id, 'price' => 450],
            ];

            foreach ($coursesData as $data) {
                $courses[] = Course::create(array_merge($data, [
                    'tenant_id' => $tenant->id,
                    __('services.string_74'),
                    'sessions_count' => 12,
                    'status' => 'published',
                ]));
            }

            // 4. Create Students & Enrollments & Sales
            $studentsNames = [__('services.string_75'), __('services.string_76'), __('services.string_77'), __('services.string_78'), __('services.string_79')];
            
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
        } finally {
            SubscriptionService::silence(false);
        }
    }

    public function removeDemoDataForTenant($tenant)
    {
        SubscriptionService::silence(true);
        try {
            return DB::transaction(function () use ($tenant) {
                // 1. Delete Demo Students (Users & Profiles)
                $demoUsers = User::where('tenant_id', $tenant->id)
                    ->where('role', 'student')
                    ->where(function ($q) {
                        $q->where('email', 'like', '%.demo%@%')
                          ->orWhere('email', 'like', 'std%.demo%@%');
                    })->get();

                foreach ($demoUsers as $user) {
                    // This cascades to student profile via StudentService or foreign keys
                    // But to be safe, delete related records directly if not cascaded
                    if ($user->student) {
                        $user->student->sales()->delete();
                        $user->student->enrollments()->delete();
                        \Modules\Center\Models\Attendance::where('student_id', $user->student->id)->delete();
                        $user->student->delete();
                    }
                    \App\Models\PointLog::where('user_id', $user->id)->delete();
                    $user->delete();
                }

                // 2. Delete Demo Instructors
                $demoInstructors = Instructor::where('tenant_id', $tenant->id)
                    ->where('email', 'like', '%.demo@%')->get();

                foreach ($demoInstructors as $instructor) {
                    $instructor->courses()->chunk(50, function($courses) {
                       foreach($courses as $course) {
                           $course->schedules()->delete();
                           $course->delete();
                       }
                    });
                    $instructor->delete();
                }

                return true;
            });
        } finally {
            SubscriptionService::silence(false);
            
            // Recalculate usage aggressively or simply forget cache so it recounting
            \Illuminate\Support\Facades\Cache::forget("tenant_{$tenant->id}_usage_max_students");
            \Illuminate\Support\Facades\Cache::forget("tenant_{$tenant->id}_usage_max_instructors");
            \Illuminate\Support\Facades\Cache::forget("tenant_{$tenant->id}_usage_max_courses");
        }
    }
}
