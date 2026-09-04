<?php

namespace App\Services;

use App\DTOs\StudentData;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Instructor;
use App\Models\Stage;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Tenancy\Services\TenantResolver;

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
        TenantResolver::set($tenant);

        // First clean up any prior or soft-deleted demo records for this tenant
        $this->removeDemoDataForTenant($tenant);

        SubscriptionService::silence(true);
        try {
            return DB::transaction(function () use ($tenant) {
                // 1. Create Academic Structure if not exists
                $stage = Stage::firstOrCreate(
                    ['name' => 'المرحلة الثانوية', 'tenant_id' => $tenant->id],
                    ['order' => 1]
                );

                $grade = Grade::firstOrCreate(
                    ['name' => 'الصف الثالث الثانوي', 'tenant_id' => $tenant->id, 'stage_id' => $stage->id],
                    ['order' => 3]
                );

                // Unique batch token to ensure uniqueness
                $batch = substr(md5(uniqid('', true)), 0, 5);

                // 2. Create Instructors
                $instructorsData = [
                    [
                        'name' => 'أحمد محمد',
                        'specialization' => 'الرياضيات',
                        'email' => 'ahmed.demo.' . $batch . '@' . $tenant->domain,
                        'status' => 'active',
                    ],
                    [
                        'name' => 'سارة أحمد',
                        'specialization' => 'اللغة العربية',
                        'email' => 'sara.demo.' . $batch . '@' . $tenant->domain,
                        'status' => 'active',
                    ],
                ];

                $instructors = [];
                foreach ($instructorsData as $data) {
                    $instructors[] = Instructor::create(array_merge($data, [
                        'tenant_id' => $tenant->id,
                        'phone' => '01' . rand(10, 99) . rand(1000000, 9999999),
                        'commission_rate' => 20,
                        'commission_type' => 'percentage',
                    ]));
                }

                // 3. Create Courses
                $courses = [];
                $coursesData = [
                    ['title' => 'دورة الرياضيات المتقدمة', 'instructor_id' => $instructors[0]->id, 'price' => 500],
                    ['title' => 'دورة اللغة العربية', 'instructor_id' => $instructors[1]->id, 'price' => 450],
                ];

                foreach ($coursesData as $data) {
                    $courses[] = Course::create(array_merge($data, [
                        'tenant_id' => $tenant->id,
                        'description' => 'دورة تجريبية للعرض',
                        'sessions_count' => 12,
                        'status' => 'published',
                    ]));
                }

                // 4. Create Students & Enrollments & Sales
                $studentsNames = ['محمد علي', 'فاطمة حسن', 'يوسف إبراهيم', 'نور الدين', 'مريم عبدالله'];

                foreach ($studentsNames as $index => $name) {
                    $studentEmail = Str::slug($name, '.') . '.demo' . $index . '.' . $batch . '@' . $tenant->domain;

                    // Register Student
                    $sData = StudentData::fromArray([
                        'name' => $name,
                        'email' => $studentEmail,
                        'phone' => '01' . rand(10, 99) . rand(1000000, 9999999),
                        'grade_id' => $grade->id,
                        'password' => Str::random(12),
                    ]);

                    // During self-registration no user is authenticated yet — fall back
                    // to the tenant's admin (first non-student user) as the creator.
                    $creator = (auth()->check() && User::where('id', auth()->id())->exists())
                        ? auth()->user()
                        : User::where('tenant_id', $tenant->id)->where('role', '!=', 'student')->orderBy('id')->first();

                    if (! $creator) {
                        $creator = User::firstOrCreate(
                            ['email' => 'admin@' . $tenant->domain . '.local'],
                            [
                                'name' => 'مدير ' . $tenant->name,
                                'password' => 'password',
                                'role' => 'center_admin',
                                'tenant_id' => $tenant->id,
                            ]
                        );
                    }
                    $result = $this->studentService->registerStudent($sData, $creator);
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
                        'received_by' => $creator?->id,
                    ]);
                }

                return true;
            });
        } finally {
            SubscriptionService::silence(false);

            // Bust caches so dashboard stats update immediately
            \Illuminate\Support\Facades\Cache::forget("tenant_{$tenant->id}_usage_max_students");
            \Illuminate\Support\Facades\Cache::forget("tenant_{$tenant->id}_usage_max_instructors");
            \Illuminate\Support\Facades\Cache::forget("tenant_{$tenant->id}_usage_max_courses");
            \App\Support\TenantCache::forget('dashboard_stats_v3');
            \App\Support\TenantCache::forget('active_instructors_count');
            \App\Support\TenantCache::forget('recent_activities');
            \App\Support\TenantCache::forget("dashboard_ai_insights_v3_{$tenant->id}");
        }
    }

    public function removeDemoDataForTenant($tenant)
    {
        SubscriptionService::silence(true);
        try {
            return DB::transaction(function () use ($tenant) {
                // 1. Delete Demo Students (Users & Profiles, including soft-deleted)
                $demoUsers = User::withTrashed()
                    ->where('tenant_id', $tenant->id)
                    ->where('role', 'student')
                    ->where(function ($q) {
                        $q->where('email', 'like', '%.demo%@%')
                            ->orWhere('email', 'like', 'std%.demo%@%');
                    })->get();

                foreach ($demoUsers as $user) {
                    $student = Student::withTrashed()->where('user_id', $user->id)->first();
                    if ($student) {
                        foreach ($student->sales()->withTrashed()->get() as $sale) {
                            $sale->items()->delete();
                            $sale->payments()->delete();
                            $sale->refunds()->delete();
                            $sale->forceDelete();
                        }
                        $student->enrollments()->delete();
                        $student->guardians()->detach();
                        \Modules\Center\Models\Attendance::where('student_id', $student->id)->delete();
                        $student->forceDelete();
                    }
                    \App\Models\PointLog::where('user_id', $user->id)->delete();
                    $user->forceDelete();
                }

                // Also clean up any orphan students with demo emails
                $orphanStudents = Student::withTrashed()
                    ->where('tenant_id', $tenant->id)
                    ->where(function ($q) {
                        $q->where('email', 'like', '%.demo%@%');
                    })->get();

                foreach ($orphanStudents as $student) {
                    foreach ($student->sales()->withTrashed()->get() as $sale) {
                        $sale->items()->delete();
                        $sale->payments()->delete();
                        $sale->refunds()->delete();
                        $sale->forceDelete();
                    }
                    $student->enrollments()->delete();
                    $student->guardians()->detach();
                    \Modules\Center\Models\Attendance::where('student_id', $student->id)->delete();
                    $student->forceDelete();
                }

                // 2. Delete Demo Instructors & Courses
                $demoInstructors = Instructor::where('tenant_id', $tenant->id)
                    ->where(function ($q) {
                        $q->where('email', 'like', '%.demo@%')
                            ->orWhere('email', 'like', '%.demo.%@%');
                    })->get();

                foreach ($demoInstructors as $instructor) {
                    $courses = Course::withTrashed()->where('instructor_id', $instructor->id)->get();
                    foreach ($courses as $course) {
                        $course->schedules()->delete();
                        $course->enrollments()->delete();
                        $course->forceDelete();
                    }
                    $instructor->delete();
                }

                // Also delete any remaining demo courses
                Course::withTrashed()
                    ->where('tenant_id', $tenant->id)
                    ->where('description', 'دورة تجريبية للعرض')
                    ->forceDelete();

                // 3. Delete Demo Academic Structure
                Grade::where('tenant_id', $tenant->id)->where('name', 'الصف الثالث الثانوي')->delete();
                Stage::where('tenant_id', $tenant->id)->where('name', 'المرحلة الثانوية')->delete();

                return true;
            });
        } finally {
            SubscriptionService::silence(false);

            // Recalculate usage aggressively or simply forget cache so it recounting
            \Illuminate\Support\Facades\Cache::forget("tenant_{$tenant->id}_usage_max_students");
            \Illuminate\Support\Facades\Cache::forget("tenant_{$tenant->id}_usage_max_instructors");
            \Illuminate\Support\Facades\Cache::forget("tenant_{$tenant->id}_usage_max_courses");
            \App\Support\TenantCache::forget('dashboard_stats_v3');
            \App\Support\TenantCache::forget('active_instructors_count');
            \App\Support\TenantCache::forget('recent_activities');
            \App\Support\TenantCache::forget("dashboard_ai_insights_v3_{$tenant->id}");
        }
    }
}
