<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Center\Services\OnboardingService;

class OnboardingController extends Controller
{
    public function __construct(protected OnboardingService $onboarding) {}

    /**
     * One-time fix: Create pending invoices for students who have enrollments but no sales.
     * Protected by auth middleware. Run once then remove the route.
     */
    public function fixMissingInvoices()
    {
        $tenant = auth()->user()->tenant;
        if (! $tenant) {
            abort(403);
        }

        $fixed = $this->onboarding->createMissingInvoices($tenant);

        return response()->json([
            'success' => true,
            'message' => count($fixed) > 0 ? 'تم إنشاء '.count($fixed).' فاتورة بنجاح' : 'لا يوجد طلاب بدون فواتير',
            'fixed' => $fixed,
        ]);
    }

    public function show()
    {
        $tenant = auth()->user()->tenant;
        $status = $tenant->onboarding_status;

        if ($status === 'completed') {
            return redirect()->route('center.dashboard');
        }

        return view('center::onboarding.wizard', array_merge(
            compact('status', 'tenant'),
            $this->onboarding->wizardData($tenant)
        ));
    }

    public function updateLocale(Request $request)
    {
        $request->validate([
            'locale' => 'required|in:ar,en,fr',
        ]);

        $tenant = auth()->user()->tenant;

        auth()->user()->update(['locale' => $request->locale]);
        session(['locale' => $request->locale]);

        $this->onboarding->applyLocaleDefaults($tenant, $request->locale);

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
            ]);

            $this->onboarding->saveAcademicSettings($tenant, $request->locale, $request->currency, $request->education_system);

            // Set user locale too
            auth()->user()->update(['locale' => $request->locale]);
            session(['locale' => $request->locale]);

            return response()->json(['success' => true, 'redirect' => route('center.onboarding.show').'?step=step_2']);
        }

        if ($step === 'step_2') {
            if (! $request->boolean('skip')) {
                $request->validate([
                    'instructors' => 'required|array|min:1',
                    'instructors.*.instructor_name' => 'required|string|max:255',
                    'instructors.*.instructor_phone' => ['required', 'string', 'max:20', 'regex:/^[0-9\+\-\s\(\)]+$/'],
                    'instructors.*.instructor_specialization' => 'nullable|string|max:255',
                    'instructors.*.instructor_email' => 'nullable|email|max:255',
                    'instructors.*.commission_type' => 'required|in:percentage,fixed',
                    'instructors.*.commission_rate' => 'required|numeric|min:0',
                ]);

                $this->onboarding->syncInstructors($tenant, $request->input('instructors'));
            }

            $tenant->update(['onboarding_status' => 'step_3']);

            return response()->json(['success' => true, 'next_step' => 'step_3']);
        }

        if ($step === 'step_3') {
            if (! $request->boolean('skip')) {
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

                $this->onboarding->syncCourses($tenant, $request->input('courses'));
            }

            $tenant->update(['onboarding_status' => 'step_4']);

            return response()->json(['success' => true, 'next_step' => 'step_4']);
        }

        if ($step === 'step_4') {
            if (! $request->boolean('skip')) {
                $request->validate([
                    'students' => 'required|array|min:1',
                    'students.*.student_name' => 'required|string|max:255',
                    'students.*.student_email' => 'nullable|email|max:255',
                    'students.*.student_phone' => ['required', 'string', 'max:20', 'regex:/^[0-9\+\-\s\(\)]+$/'],
                    'students.*.parent_name' => 'nullable|string|max:255',
                    'students.*.parent_phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\+\-\s\(\)]+$/'],
                    'students.*.parent_email' => 'nullable|email|max:255',
                    'students.*.grade_id' => 'nullable|exists:grades,id',
                    'students.*.enroll_course_indices' => 'nullable|array',
                    'students.*.enroll_course_indices.*' => 'integer|min:0',
                ]);

                try {
                    $this->onboarding->registerStudents($tenant, $request->students, auth()->user());
                } catch (\RuntimeException $e) {
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage(),
                    ], 422);
                }
            }

            \Log::info('Onboarding completed for tenant: '.$tenant->domain);
            $tenant->update(['onboarding_status' => 'completed']);
            $redirectUrl = route('center.dashboard');
            \Log::info('Redirecting to: '.$redirectUrl);

            return response()->json(['success' => true, 'redirect' => $redirectUrl]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid step.'], 400);
    }
}
