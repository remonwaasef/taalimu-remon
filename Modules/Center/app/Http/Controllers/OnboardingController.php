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
            
            $tenant->update(['onboarding_status' => 'step_3']);
            return response()->json(['success' => true, 'next_step' => 'step_3']);
        }

        if ($step === 'step_3') {
            if (!$request->boolean('skip')) {
                $request->validate([
                    'course_name' => 'required|string|max:255',
                ]);
                
                \App\Models\Course::create([
                    'tenant_id' => $tenant->id,
                    'title' => $request->course_name,
                    'status' => 'active',
                ]);
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
                
                \App\Models\Student::create([
                    'tenant_id' => $tenant->id,
                    'name' => $request->student_name,
                    'phone' => $request->student_phone,
                ]);
            }
            
            $tenant->update(['onboarding_status' => 'completed']);
            return response()->json(['success' => true, 'next_step' => 'completed', 'redirect' => route('center.dashboard')]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid step.'], 400);
    }

}
