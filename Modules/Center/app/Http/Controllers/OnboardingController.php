<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StudentService;
use App\Services\FinanceService;

class OnboardingController extends Controller
{
    protected $studentService;
    protected $financeService;
    protected $settingsService;

    public function __construct(
        \App\Services\StudentService $studentService, 
        \App\Services\FinanceService $financeService,
        \Modules\Center\Services\SettingsService $settingsService
    ) {
        $this->studentService = $studentService;
        $this->financeService = $financeService;
        $this->settingsService = $settingsService;
    }

    /**
     * One-time fix: Create pending invoices for students who have enrollments but no sales.
     * Protected by auth middleware. Run once then remove the route.
     */
    public function fixMissingInvoices()
    {
        $tenant = auth()->user()->tenant;
        if (!$tenant) abort(403);

        $students = \App\Models\Student::where('tenant_id', $tenant->id)
            ->has('enrollments')
            ->doesntHave('sales')
            ->with('enrollments.course')
            ->get();

        $fixed = [];

        foreach ($students as $student) {
            foreach ($student->enrollments as $enrollment) {
                $course = $enrollment->course;
                if (!$course) continue;

                $price = $course->price ?? 0;

                $sale = \App\Models\Sale::create([
                    'tenant_id'       => $tenant->id,
                    'student_id'      => $student->id,
                    'subtotal_amount' => $price,
                    'discount_amount' => 0,
                    'tax_amount'      => 0,
                    'total_amount'    => $price,
                    'paid_amount'     => 0,
                    'status'          => $price > 0 ? 'pending' : 'paid',
                    'payment_method'  => 'cash',
                    'notes'           => 'إصلاح تلقائي - تسجيل من الإعداد الأولي',
                ]);

                \App\Models\SaleItem::create([
                    'sale_id'   => $sale->id,
                    'item_type' => \App\Models\Course::class,
                    'item_id'   => $course->id,
                    'price'     => $price,
                    'quantity'  => 1,
                ]);

                $fixed[] = [
                    'student' => $student->name,
                    'course'  => $course->title,
                    'amount'  => $price,
                    'sale_id' => $sale->id,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => count($fixed) > 0 ? 'تم إنشاء ' . count($fixed) . ' فاتورة بنجاح' : 'لا يوجد طلاب بدون فواتير',
            'fixed'   => $fixed,
        ]);
    }

    public function show()
    {
        // Fix: Ensure step_4 is in the ENUM (Self-correcting DB)
        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE tenants MODIFY COLUMN onboarding_status ENUM('pending', 'step_1', 'step_2', 'step_3', 'step_4', 'completed') DEFAULT 'pending'");
        } catch (\Exception $e) {}

        $tenant = auth()->user()->tenant;
        $status = $tenant->onboarding_status;

        if ($status === 'completed') {
            return redirect()->route('center.dashboard');
        }

        // Fetch stages and grades for student registration step
        $stages = \App\Models\Stage::where('tenant_id', $tenant->id)
            ->with('grades')
            ->orderBy('order')
            ->get();

        $existingInstructors = \App\Models\Instructor::where('tenant_id', $tenant->id)->orderBy('id')->get()->map(function($inst) {
            return [
                'instructor_name' => $inst->name,
                'instructor_phone' => $inst->phone,
                'instructor_specialization' => $inst->specialization ?? '',
                'instructor_email' => $inst->email ?? '',
            ];
        })->toArray();

        $instructorsListIds = \App\Models\Instructor::where('tenant_id', $tenant->id)->orderBy('id')->pluck('id')->toArray();
        $existingCourses = \App\Models\Course::where('tenant_id', $tenant->id)->orderBy('id')->with('schedules')->get()->map(function($course) use ($instructorsListIds) {
            $idx = array_search($course->instructor_id, $instructorsListIds);
            
            $schedules = $course->schedules->map(function($s) {
                return [
                    'day' => (string)$s->day_of_week,
                    'time' => substr($s->start_time, 0, 5),
                    'time_end' => substr($s->end_time, 0, 5),
                ];
            })->toArray();

            if (empty($schedules)) {
                $schedules = [['day' => '0', 'time' => '16:00', 'time_end' => '18:00']];
            }

            return [
                'instructor_index' => $idx !== false ? (string)$idx : '0',
                'course_name' => $course->title,
                'price' => $course->price,
                'sessions_count' => $course->sessions_count,
                'schedules' => $schedules,
            ];
        })->toArray();

        return view('center::onboarding.wizard', compact('status', 'tenant', 'stages', 'existingInstructors', 'existingCourses'));
    }

    public function updateLocale(Request $request)
    {
        $request->validate([
            'locale' => 'required|in:ar,en,fr',
        ]);

        $tenant = auth()->user()->tenant;
        
        // Update user locale
        auth()->user()->update(['locale' => $request->locale]);
        
        // Update session locale
        session(['locale' => $request->locale]);

        // Optional: Also update tenant default setting if you want it to be the "choice"
        $settings = $tenant->settings ?? [];
        $settings['default_locale'] = $request->locale;
        
        // Map language to default currency and education system
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

        if (isset($currencyMap[$request->locale])) {
            if (!isset($settings['financial'])) {
                $settings['financial'] = [];
            }
            $settings['financial']['currency'] = $currencyMap[$request->locale];
            // Also keep a legacy/flat version if other parts of the system depend on it
            $settings['currency'] = $currencyMap[$request->locale];
        }
        if (isset($systemMap[$request->locale])) {
            $settings['education_system'] = $systemMap[$request->locale];
        }

        $tenant->settings = $settings;
        $tenant->save();

        return response()->json(['success' => true]);
    }

    public function submit(Request $request)
    {
        $tenant = auth()->user()->tenant;
        $step = $request->input('step');

        if ($step === 'step_1') {
            $request->validate([
                'locale' => 'required|in:ar,en,fr',
                'currency' => 'required|string|max:3',
                'education_system' => 'required|string|in:egyptian_national,egyptian_azhar,french_system,european_system',
                // other academic settings validations can go here
            ]);

            // Save Settings
            $settings = $tenant->settings ?? [];
            $settings['default_locale'] = $request->locale;
            
            if (!isset($settings['financial'])) {
                $settings['financial'] = [];
            }
            $settings['financial']['currency'] = $request->currency;
            $settings['currency'] = $request->currency; // Legacy support
            
            $settings['education_system'] = $request->education_system;
            
            $tenant->settings = $settings;
            $tenant->onboarding_status = 'step_2';
            $tenant->save();

            // Apply Academic Template
            try {
                $this->settingsService->applyTemplate($tenant, $request->education_system);
            } catch (\Exception $e) {
                \Log::error("Onboarding Template Application Error: " . $e->getMessage());
            }

            // Set user locale too
            auth()->user()->update(['locale' => $request->locale]);
            session(['locale' => $request->locale]);

            return response()->json(['success' => true, 'redirect' => route('center.onboarding.show') . '?step=step_2']);
        }

        if ($step === 'step_2') {
            if (!$request->boolean('skip')) {
                $request->validate([
                    'instructors' => 'required|array|min:1',
                    'instructors.*.instructor_name' => 'required|string|max:255',
                    'instructors.*.instructor_phone' => 'required|string|max:20',
                    'instructors.*.instructor_specialization' => 'nullable|string|max:255',
                    'instructors.*.instructor_email' => 'nullable|email|max:255',
                ]);
                
                $existingInstructors = \App\Models\Instructor::where('tenant_id', $tenant->id)->get();
                $instructorsInput = $request->input('instructors');

                foreach ($instructorsInput as $index => $instructorData) {
                    if (isset($existingInstructors[$index])) {
                        $existingInstructor = $existingInstructors[$index];
                        $existingInstructor->update([
                            'name' => $instructorData['instructor_name'],
                            'phone' => $instructorData['instructor_phone'],
                            'email' => $instructorData['instructor_email'] ?: $existingInstructor->email,
                            'specialization' => $instructorData['instructor_specialization'],
                        ]);
                        if ($existingInstructor->user) {
                            $existingInstructor->user->update([
                                'name' => $instructorData['instructor_name'],
                                'phone' => $instructorData['instructor_phone'],
                            ]);
                        }
                    } else {
                        $user = \App\Models\User::create([
                            'tenant_id' => $tenant->id,
                            'name' => $instructorData['instructor_name'],
                            'phone' => $instructorData['instructor_phone'],
                            'email' => $instructorData['instructor_email'] ?: 'instructor_' . time() . '_' . $index . '@' . $tenant->domain,
                            'password' => 'password123',
                            'role' => 'instructor',
                            'email_verified_at' => now(),
                            'phone_verified_at' => now(),
                        ]);

                        \App\Models\Instructor::create([
                            'tenant_id' => $tenant->id,
                            'user_id' => $user->id,
                            'name' => $instructorData['instructor_name'],
                            'phone' => $instructorData['instructor_phone'],
                            'email' => $user->email,
                            'specialization' => $instructorData['instructor_specialization'],
                            'status' => 'active',
                        ]);
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
            
            $tenant->update(['onboarding_status' => 'step_3']);
            return response()->json(['success' => true, 'next_step' => 'step_3']);
        }

        if ($step === 'step_3') {
            if (!$request->boolean('skip')) {
                $request->validate([
                    'courses' => 'required|array|min:1',
                    'courses.*.course_name' => 'required|string|max:255',
                    'courses.*.price' => 'required|numeric|min:0',
                    'courses.*.sessions_count' => 'required|integer|min:1',
                    'courses.*.schedules' => 'required|array|min:1',
                    'courses.*.schedules.*.day' => 'required|integer|between:0,6',
                    'courses.*.schedules.*.time' => 'required',
                    'courses.*.instructor_index' => 'required',
                ]);

                $existingCourses = \App\Models\Course::where('tenant_id', $tenant->id)->orderBy('id', 'asc')->get();
                $coursesInput = $request->input('courses');
                
                foreach ($coursesInput as $index => $courseData) {
                    $instructorIndex = $courseData['instructor_index'] ?? 0;
                    $instructor = \App\Models\Instructor::where('tenant_id', $tenant->id)
                        ->orderBy('id', 'asc')
                        ->skip($instructorIndex)
                        ->first();
                        
                    if (isset($existingCourses[$index])) {
                        $course = $existingCourses[$index];
                        $course->update([
                            'instructor_id' => $instructor?->id,
                            'title' => $courseData['course_name'],
                            'price' => $courseData['price'],
                            'sessions_count' => $courseData['sessions_count'],
                        ]);
                        \App\Models\Schedule::where('course_id', $course->id)->delete();
                    } else {
                        $course = \App\Models\Course::create([
                            'tenant_id' => $tenant->id,
                            'instructor_id' => $instructor?->id,
                            'title' => $courseData['course_name'],
                            'price' => $courseData['price'],
                            'sessions_count' => $courseData['sessions_count'],
                            'status' => 'active',
                        ]);
                    }
                    
                    foreach ($courseData['schedules'] as $sched) {
                        $startTime = \Carbon\Carbon::createFromFormat('H:i', $sched['time']);
                        $endTime = isset($sched['time_end']) ? \Carbon\Carbon::createFromFormat('H:i', $sched['time_end']) : (clone $startTime)->addHours(2);
                        
                        \App\Models\Schedule::create([
                            'tenant_id' => $tenant->id,
                            'course_id' => $course->id,
                            'instructor_id' => $instructor?->user_id,
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
            
            $tenant->update(['onboarding_status' => 'step_4']);
            return response()->json(['success' => true, 'next_step' => 'step_4']);
        }

        if ($step === 'step_4') {
            if (!$request->boolean('skip')) {
                $request->validate([
                    'student_name' => 'required|string|max:255',
                    'student_phone' => 'required|string|max:20',
                    'grade_id' => 'nullable|exists:grades,id',
                ]);

                // 1. Register Student using natural StudentService
                // This handles unique email, unique code, and admin notifications
                $studentData = \App\DTOs\StudentData::fromArray([
                    'name' => $request->student_name,
                    'phone' => $request->student_phone,
                    'grade_id' => $request->grade_id,
                ]);

                $result = $this->studentService->registerStudent($studentData, auth()->user());
                $student = $result['student'];

                // 2. Enroll in course if requested using FinanceService
                $enrollCourseIndex = $request->input('enroll_course_index');
                if ($enrollCourseIndex !== null && $enrollCourseIndex !== '') {
                    $course = \App\Models\Course::where('tenant_id', $tenant->id)
                        ->orderBy('id', 'asc')
                        ->skip((int)$enrollCourseIndex)
                        ->first();
                        
                    if ($course) {
                        try {
                            $this->financeService->createSale([
                                'student_id' => $student->id,
                                'items' => [['id' => $course->id, 'price' => $course->price]],
                                'payment_method' => 'cash',
                                'paid_amount' => 0, // Unpaid invoice = due amount
                                'notes' => 'Onboarding Enrollment',
                            ]);
                        } catch (\Exception $e) {
                            \Log::error("Onboarding Finance Error: " . $e->getMessage());
                        }
                    }
                }
            }
            
            $tenant->update(['onboarding_status' => 'completed']);
            return response()->json(['success' => true, 'redirect' => route('center.dashboard')]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid step.'], 400);
    }

}
