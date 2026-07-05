<?php

namespace Modules\Center\Services;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Schedule;
use App\Models\Stage;
use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use App\Services\FinanceService;
use App\Services\SettingsService;
use App\Services\StudentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Business logic for the center onboarding wizard steps
 * (academic settings, instructors, courses, students).
 */
class OnboardingService
{
    public function __construct(
        protected StudentService $studentService,
        protected FinanceService $financeService,
        protected SettingsService $settingsService,
    ) {}

    /**
     * Data needed to render the wizard with any previously-entered records.
     */
    public function wizardData(Tenant $tenant): array
    {
        $stages = Stage::where('tenant_id', $tenant->id)
            ->with('grades')
            ->orderBy('order')
            ->get();

        $existingInstructorsQuery = Instructor::where('tenant_id', $tenant->id)
            ->select('id', 'name', 'phone', 'specialization', 'email', 'commission_type', 'commission_rate')
            ->orderBy('id')
            ->limit(100)
            ->get();

        $existingInstructors = $existingInstructorsQuery->map(function ($inst) {
            return [
                'instructor_name' => $inst->name,
                'instructor_phone' => $inst->phone,
                'instructor_specialization' => $inst->specialization ?? '',
                'instructor_email' => $inst->email ?? '',
                'commission_type' => $inst->commission_type ?? 'percentage',
                'commission_rate' => $inst->commission_rate ?? 0,
            ];
        })->toArray();

        $instructorsListIds = $existingInstructorsQuery->pluck('id')->toArray();

        $existingCourses = Course::where('tenant_id', $tenant->id)
            ->select('id', 'instructor_id', 'title', 'price', 'sessions_count')
            ->orderBy('id')
            ->limit(100)
            ->with(['schedules' => function ($query) {
                $query->select('id', 'course_id', 'day_of_week', 'start_time', 'end_time');
            }])
            ->get()->map(function ($course) use ($instructorsListIds) {
                $idx = array_search($course->instructor_id, $instructorsListIds);

                $schedules = $course->schedules->map(function ($s) {
                    return [
                        'day' => (string) $s->day_of_week,
                        'time' => substr($s->start_time, 0, 5),
                        'time_end' => substr($s->end_time, 0, 5),
                    ];
                })->toArray();

                if (empty($schedules)) {
                    $schedules = [['day' => '0', 'time' => '16:00', 'time_end' => '18:00']];
                }

                return [
                    'instructor_index' => $idx !== false ? (string) $idx : '0',
                    'course_name' => $course->title,
                    'price' => $course->price,
                    'sessions_count' => $course->sessions_count,
                    'schedules' => $schedules,
                ];
            })->toArray();

        $existingStudents = Student::where('tenant_id', $tenant->id)
            ->select('id', 'name', 'email', 'phone', 'parent_name', 'parent_phone', 'parent_email', 'grade_id')
            ->orderBy('id')
            ->limit(100)
            ->get()->map(function ($student) {
                return [
                    'student_name' => $student->name,
                    'student_email' => $student->email ?? '',
                    'student_phone' => $student->phone,
                    'parent_name' => $student->parent_name ?? '',
                    'parent_phone' => $student->parent_phone ?? '',
                    'parent_email' => $student->parent_email ?? '',
                    'grade_id' => $student->grade_id ?? '',
                    'enroll_course_indices' => [],
                ];
            })->toArray();

        return compact('stages', 'existingInstructors', 'existingCourses', 'existingStudents');
    }

    /**
     * Map a locale to default currency + education system and persist on the tenant.
     */
    public function applyLocaleDefaults(Tenant $tenant, string $locale): void
    {
        $settings = $tenant->settings ?? [];
        $settings['default_locale'] = $locale;

        $currencyMap = [
            'ar' => 'EGP',
            'fr' => 'EUR',
            'en' => 'USD',
        ];
        $systemMap = [
            'ar' => 'egyptian_national',
            'fr' => 'french_system',
            'en' => 'european_system',
        ];

        if (isset($currencyMap[$locale])) {
            if (! isset($settings['financial'])) {
                $settings['financial'] = [];
            }
            $settings['financial']['currency'] = $currencyMap[$locale];
            // Also keep a legacy/flat version if other parts of the system depend on it
            $settings['currency'] = $currencyMap[$locale];
        }
        if (isset($systemMap[$locale])) {
            $settings['education_system'] = $systemMap[$locale];
        }

        $tenant->settings = $settings;
        $tenant->save();
    }

    /**
     * Step 1: persist locale/currency/education-system and apply the academic template.
     */
    public function saveAcademicSettings(Tenant $tenant, string $locale, string $currency, string $educationSystem): void
    {
        $settings = $tenant->settings ?? [];
        $settings['default_locale'] = $locale;

        if (! isset($settings['financial'])) {
            $settings['financial'] = [];
        }
        $settings['financial']['currency'] = $currency;
        $settings['currency'] = $currency; // Legacy support

        $settings['education_system'] = $educationSystem;

        $tenant->settings = $settings;
        $tenant->onboarding_status = 'step_2';
        $tenant->save();

        try {
            $this->settingsService->applyTemplate($tenant, $educationSystem);
        } catch (\Exception $e) {
            Log::error('Onboarding Template Application Error: '.$e->getMessage());
        }
    }

    /**
     * Step 2: create/update instructors by list position and delete removed trailing rows.
     */
    public function syncInstructors(Tenant $tenant, array $instructorsInput): void
    {
        $existingInstructors = Instructor::where('tenant_id', $tenant->id)->with('user')->get();

        foreach ($instructorsInput as $index => $instructorData) {
            if (isset($existingInstructors[$index])) {
                $existingInstructor = $existingInstructors[$index];
                $existingInstructor->update([
                    'name' => $instructorData['instructor_name'],
                    'phone' => $instructorData['instructor_phone'],
                    'email' => $instructorData['instructor_email'] ?: $existingInstructor->email,
                    'specialization' => $instructorData['instructor_specialization'],
                    'commission_type' => $instructorData['commission_type'],
                    'commission_rate' => $instructorData['commission_rate'],
                ]);
                if ($existingInstructor->user) {
                    $existingInstructor->user->update([
                        'name' => $instructorData['instructor_name'],
                        'phone' => $instructorData['instructor_phone'],
                    ]);
                }
            } else {
                $plainPassword = Str::random(12);
                $user = User::create([
                    'tenant_id' => $tenant->id,
                    'name' => $instructorData['instructor_name'],
                    'phone' => $instructorData['instructor_phone'],
                    'email' => $instructorData['instructor_email'] ?: 'instructor_'.time().'_'.$index.'@'.$tenant->domain,
                    'password' => $plainPassword,
                    'role' => 'instructor',
                    'email_verified_at' => now(),
                    'phone_verified_at' => now(),
                ]);

                $instructor = Instructor::create([
                    'tenant_id' => $tenant->id,
                    'user_id' => $user->id,
                    'name' => $instructorData['instructor_name'],
                    'phone' => $instructorData['instructor_phone'],
                    'email' => $user->email,
                    'specialization' => $instructorData['instructor_specialization'],
                    'commission_type' => $instructorData['commission_type'],
                    'commission_rate' => $instructorData['commission_rate'],
                    'status' => 'active',
                ]);

                if ($instructorData['instructor_email']) {
                    try {
                        Mail::to($user->email)->queue(
                            new \App\Mail\WelcomeTeacherMail(
                                $instructor,
                                $plainPassword,
                                $tenant->name ?? 'المنصة',
                                url('/login')
                            )
                        );
                    } catch (\Exception $e) {
                        Log::error('Failed to send welcome email to instructor: '.$e->getMessage());
                    }
                }
            }
        }

        if ($existingInstructors->count() > count($instructorsInput)) {
            for ($i = count($instructorsInput); $i < $existingInstructors->count(); $i++) {
                $instructorToRemove = $existingInstructors[$i];
                if ($instructorToRemove->user) {
                    $instructorToRemove->user->delete();
                }
                $instructorToRemove->delete();
            }
        }
    }

    /**
     * Step 3: create/update courses (+schedules) by list position and delete removed trailing rows.
     */
    public function syncCourses(Tenant $tenant, array $coursesInput): void
    {
        $existingCourses = Course::where('tenant_id', $tenant->id)->orderBy('id', 'asc')->get();

        $instructors = Instructor::where('tenant_id', $tenant->id)
            ->orderBy('id', 'asc')
            ->get();

        foreach ($coursesInput as $index => $courseData) {
            $instructorIndex = (int) ($courseData['instructor_index'] ?? 0);
            $instructor = $instructors->get($instructorIndex);

            if (isset($existingCourses[$index])) {
                $course = $existingCourses[$index];
                $course->update([
                    'instructor_id' => $instructor?->id,
                    'title' => $courseData['course_name'],
                    'price' => $courseData['price'],
                    'sessions_count' => $courseData['sessions_count'],
                ]);
                Schedule::where('course_id', $course->id)->delete();
            } else {
                $course = Course::create([
                    'tenant_id' => $tenant->id,
                    'instructor_id' => $instructor?->id,
                    'title' => $courseData['course_name'],
                    'price' => $courseData['price'],
                    'sessions_count' => $courseData['sessions_count'],
                    'status' => 'active',
                ]);
            }

            foreach ($courseData['schedules'] as $sched) {
                $startTime = Carbon::createFromFormat('H:i', $sched['time']);
                $endTime = ! empty($sched['time_end']) ? Carbon::createFromFormat('H:i', $sched['time_end']) : (clone $startTime)->addHours(2);

                Schedule::create([
                    'tenant_id' => $tenant->id,
                    'course_id' => $course->id,
                    'instructor_id' => $instructor?->id,
                    'day_of_week' => $sched['day'],
                    'start_time' => $startTime->format('H:i:s'),
                    'end_time' => $endTime->format('H:i:s'),
                ]);
            }
        }

        if ($existingCourses->count() > count($coursesInput)) {
            for ($i = count($coursesInput); $i < $existingCourses->count(); $i++) {
                $existingCourses[$i]->delete();
            }
        }
    }

    /**
     * Step 4: register students and create their enrollment sales.
     *
     * @throws \RuntimeException when a student fails to register (message is user-facing)
     */
    public function registerStudents(Tenant $tenant, array $studentsInput, ?User $creator): void
    {
        // Pre-fetch all tenant courses ordered by ID for index-based lookup
        $allCourses = Course::where('tenant_id', $tenant->id)
            ->orderBy('id', 'asc')
            ->get();

        foreach ($studentsInput as $studentInput) {
            try {
                $studentData = \App\DTOs\StudentData::fromArray([
                    'name' => $studentInput['student_name'],
                    'email' => $studentInput['student_email'] ?? null,
                    'phone' => $studentInput['student_phone'],
                    'parent_name' => $studentInput['parent_name'] ?? null,
                    'parent_phone' => $studentInput['parent_phone'] ?? null,
                    'parent_email' => $studentInput['parent_email'] ?? null,
                    'grade_id' => $studentInput['grade_id'] ?? null,
                ]);

                $result = $this->studentService->registerStudent($studentData, $creator);
                $student = $result['student'];

                // Enroll student in all selected courses in a single sale creation
                $courseIndices = $studentInput['enroll_course_indices'] ?? [];
                $saleItems = [];
                foreach ($courseIndices as $courseIndex) {
                    $course = $allCourses->get((int) $courseIndex);
                    if ($course) {
                        $saleItems[] = ['id' => $course->id, 'price' => $course->price];
                    }
                }
                if (! empty($saleItems)) {
                    try {
                        $this->financeService->createSale([
                            'student_id' => $student->id,
                            'items' => $saleItems,
                            'payment_method' => 'cash',
                            'paid_amount' => 0,
                            'notes' => 'Onboarding Enrollment',
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Onboarding Finance Error: '.$e->getMessage());
                    }
                }
            } catch (\Exception $e) {
                throw new \RuntimeException("خطأ في تسجيل الطالب ({$studentInput['student_name']}): ".$e->getMessage());
            }
        }
    }

    /**
     * One-time fix: create pending invoices for students who have enrollments but no sales.
     */
    public function createMissingInvoices(Tenant $tenant): array
    {
        $students = Student::where('tenant_id', $tenant->id)
            ->has('enrollments')
            ->doesntHave('sales')
            ->with('enrollments.course')
            ->get();

        $fixed = [];

        foreach ($students as $student) {
            foreach ($student->enrollments as $enrollment) {
                $course = $enrollment->course;
                if (! $course) {
                    continue;
                }

                $price = $course->price ?? 0;

                $sale = Sale::create([
                    'tenant_id' => $tenant->id,
                    'student_id' => $student->id,
                    'subtotal_amount' => $price,
                    'discount_amount' => 0,
                    'tax_amount' => 0,
                    'total_amount' => $price,
                    'paid_amount' => 0,
                    'status' => $price > 0 ? 'pending' : 'paid',
                    'payment_method' => 'cash',
                    'notes' => 'إصلاح تلقائي - تسجيل من الإعداد الأولي',
                ]);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'item_type' => Course::class,
                    'item_id' => $course->id,
                    'price' => $price,
                    'quantity' => 1,
                ]);

                $fixed[] = [
                    'student' => $student->name,
                    'course' => $course->title,
                    'amount' => $price,
                    'sale_id' => $sale->id,
                ];
            }
        }

        return $fixed;
    }
}
