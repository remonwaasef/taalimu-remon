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

    public function __construct(StudentService $studentService, FinanceService $financeService)
    {
        $this->studentService = $studentService;
        $this->financeService = $financeService;
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

        return view('center::onboarding.wizard', compact('status', 'tenant'));
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
        
        // Map language to default currency
        $currencyMap = [
            'ar' => 'EGP',
            'fr' => 'EUR',
            'en' => 'USD',
        ];
        if (isset($currencyMap[$request->locale])) {
            $settings['currency'] = $currencyMap[$request->locale];
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
                // other academic settings validations can go here
            ]);

            // Save Settings
            $settings = $tenant->settings ?? [];
            $settings['default_locale'] = $request->locale;
            $settings['currency'] = $request->currency;
            
            $tenant->settings = $settings;
            $tenant->onboarding_status = 'step_2';
            $tenant->save();

            // Set user locale too
            auth()->user()->update(['locale' => $request->locale]);
            session(['locale' => $request->locale]);

            return response()->json(['success' => true, 'next_step' => 'step_2']);
        }

        if ($step === 'step_2') {
            if (!$request->boolean('skip')) {
                $request->validate([
                    'instructor_name' => 'required|string|max:255',
                    'instructor_phone' => 'required|string|max:20',
                    'instructor_specialization' => 'nullable|string|max:255',
                    'instructor_email' => 'nullable|email|max:255',
                ]);
                
                // Prevent duplicates: check if an onboarding instructor already exists
                $existingInstructor = \App\Models\Instructor::where('tenant_id', $tenant->id)->first();
                if ($existingInstructor) {
                    // Update existing records instead of creating new ones
                    $existingInstructor->update([
                        'name' => $request->instructor_name,
                        'phone' => $request->instructor_phone,
                        'email' => $request->instructor_email ?: $existingInstructor->email,
                        'specialization' => $request->instructor_specialization,
                    ]);
                    // Also update associated user
                    if ($existingInstructor->user) {
                        $existingInstructor->user->update([
                            'name' => $request->instructor_name,
                            'phone' => $request->instructor_phone,
                        ]);
                    }
                } else {
                    $user = \App\Models\User::create([
                        'tenant_id' => $tenant->id,
                        'name' => $request->instructor_name,
                        'phone' => $request->instructor_phone,
                        'email' => $request->instructor_email ?: 'instructor_' . time() . '@' . $tenant->domain,
                        'password' => 'password123',
                        'role' => 'instructor',
                        'email_verified_at' => now(),
                        'phone_verified_at' => now(),
                    ]);

                    \App\Models\Instructor::create([
                        'tenant_id' => $tenant->id,
                        'user_id' => $user->id,
                        'name' => $request->instructor_name,
                        'phone' => $request->instructor_phone,
                        'email' => $user->email,
                        'specialization' => $request->instructor_specialization,
                        'status' => 'active',
                    ]);
                }
            }
            
            $tenant->update(['onboarding_status' => 'step_3']);
            return response()->json(['success' => true, 'next_step' => 'step_3']);
        }

        if ($step === 'step_3') {
            if (!$request->boolean('skip')) {
                $request->validate([
                    'course_name' => 'required|string|max:255',
                    'price' => 'required|numeric|min:0',
                    'sessions_count' => 'required|integer|min:1',
                    'schedules' => 'required|array|min:1',
                    'schedules.*.day' => 'required|integer|between:0,6',
                    'schedules.*.time' => 'required',
                ]);

                $instructor = \App\Models\Instructor::where('tenant_id', $tenant->id)->first();
                
                // Prevent duplicates: check if an onboarding course already exists
                $existingCourse = \App\Models\Course::where('tenant_id', $tenant->id)->first();
                if ($existingCourse) {
                    // Update existing course
                    $existingCourse->update([
                        'instructor_id' => $instructor?->id,
                        'title' => $request->course_name,
                        'price' => $request->price,
                        'sessions_count' => $request->sessions_count,
                    ]);
                    // Delete old schedules and recreate
                    \App\Models\Schedule::where('course_id', $existingCourse->id)->delete();
                    $course = $existingCourse;
                } else {
                    $course = \App\Models\Course::create([
                        'tenant_id' => $tenant->id,
                        'instructor_id' => $instructor?->id,
                        'title' => $request->course_name,
                        'price' => $request->price,
                        'sessions_count' => $request->sessions_count,
                        'status' => 'active',
                    ]);
                }

                // Create Schedules (fresh)
                foreach ($request->input('schedules') as $sched) {
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
            
            $tenant->update(['onboarding_status' => 'step_4']);
            return response()->json(['success' => true, 'next_step' => 'step_4']);
        }

        if ($step === 'step_4') {
            if (!$request->boolean('skip')) {
                $request->validate([
                    'student_name' => 'required|string|max:255',
                    'student_phone' => 'required|string|max:20',
                ]);

                // 1. Register Student using natural StudentService
                // This handles unique email, unique code, and admin notifications
                $studentData = \App\DTOs\StudentData::fromArray([
                    'name' => $request->student_name,
                    'phone' => $request->student_phone,
                ]);

                $result = $this->studentService->registerStudent($studentData, auth()->user());
                $student = $result['student'];

                // 2. Enroll in course if requested using FinanceService
                // Note: enroll_in_course comes as boolean from Alpine.js JSON
                $shouldEnroll = filter_var($request->input('enroll_in_course', false), FILTER_VALIDATE_BOOLEAN);
                if ($shouldEnroll) {
                    $course = \App\Models\Course::where('tenant_id', $tenant->id)->latest()->first();
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
