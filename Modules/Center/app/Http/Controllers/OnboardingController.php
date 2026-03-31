<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
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

        return view('center::onboarding.wizard', compact('status'));
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
        $tenant->settings = $settings;
        $tenant->save();

        return response()->json(['success' => true]);
    }

    public function submit(Request $request, \App\Services\FinanceService $financeService)
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

                // Prevent duplicates: check if an onboarding student already exists
                $existingStudent = \App\Models\Student::where('tenant_id', $tenant->id)->first();
                if ($existingStudent) {
                    // Update existing
                    $existingStudent->update([
                        'name' => $request->student_name,
                        'phone' => $request->student_phone,
                    ]);
                    if ($existingStudent->user) {
                        $existingStudent->user->update([
                            'name' => $request->student_name,
                            'phone' => $request->student_phone,
                        ]);
                    }
                    $user = $existingStudent->user;
                } else {
                    $user = \App\Models\User::create([
                        'tenant_id' => $tenant->id,
                        'name' => $request->student_name,
                        'phone' => $request->student_phone,
                        'email' => 'student_' . $request->student_phone . '@' . $tenant->id . '.edu',
                        'password' => \Illuminate\Support\Facades\Hash::make($request->student_phone),
                        'user_type' => 'student',
                        'status' => 'active',
                    ]);

                    \App\Models\Student::create([
                        'tenant_id' => $tenant->id,
                        'user_id' => $user->id,
                        'name' => $request->student_name,
                        'phone' => $request->student_phone,
                        'status' => 'active',
                    ]);
                }

                // Enroll in course and create financial record if requested
                if ($request->boolean('enroll_in_course') && $user) {
                    $course = \App\Models\Course::where('tenant_id', $tenant->id)->latest()->first();
                    $student = \App\Models\Student::where('user_id', $user->id)->first();
                    if ($course && $student) {
                        // Use FinanceService to create a Sale (this handles Enrollment too)
                        try {
                            $financeService->createSale([
                                'student_id' => $student->id,
                                'items' => [
                                    ['id' => $course->id, 'price' => $course->price]
                                ],
                                'paid_amount' => 0,
                                'payment_method' => 'cash',
                                'notes' => 'قيد تلقائي عند إعداد المركز',
                            ]);
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error("Onboarding Sale Creation Failed: " . $e->getMessage());
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
