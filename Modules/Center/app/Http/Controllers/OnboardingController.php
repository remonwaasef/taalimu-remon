<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Stage;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OnboardingController extends Controller
{
    /**
     * Show the onboarding wizard.
     */
    public function index()
    {
        $tenant = app('tenant');

        // If already onboarded, redirect to dashboard
        if ($tenant->onboarding_completed_at) {
            return redirect()->route('center.dashboard', ['tenant' => $tenant->domain]);
        }

        // Gather existing data for pre-population
        $templates = config('academic.templates', []);
        $stages = Stage::with('grades')->orderBy('order')->get();
        $instructors = Instructor::all();
        $courses = Course::all();
        $students = Student::all();

        return view('center::onboarding.index', compact(
            'tenant', 'templates', 'stages', 'instructors', 'courses', 'students'
        ));
    }

    /**
     * Save a specific step via AJAX.
     */
    public function saveStep(Request $request)
    {
        $step = $request->input('step');
        $tenant = app('tenant');

        try {
            switch ($step) {
                case 1:
                    return $this->saveProfile($request, $tenant);
                case 2:
                    return $this->saveAcademicSystem($request, $tenant);
                case 3:
                    return $this->saveInstructor($request, $tenant);
                case 4:
                    return $this->saveCourse($request, $tenant);
                case 5:
                    return $this->saveStudent($request, $tenant);
                default:
                    return response()->json(['success' => false, 'message' => 'Invalid step'], 422);
            }
        } catch (\Exception $e) {
            Log::error("Onboarding step {$step} error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Step 1: Save center profile.
     */
    private function saveProfile(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('tenants/logos', 'public');
            $validated['logo'] = $path;
        }

        $tenant->update($validated);

        return response()->json([
            'success' => true,
            'message' => __('onboarding.profile_saved'),
        ]);
    }

    /**
     * Step 2: Apply academic template.
     */
    private function saveAcademicSystem(Request $request, Tenant $tenant)
    {
        $templateKey = $request->input('template_key');

        if ($templateKey === 'custom') {
            // User chose custom — skip template application
            return response()->json([
                'success' => true,
                'message' => __('onboarding.custom_system'),
                'stages' => [],
            ]);
        }

        $request->validate([
            'template_key' => 'required|string|in:' . implode(',', array_keys(config('academic.templates', []))),
        ]);

        $template = config("academic.templates.{$templateKey}");
        $tenantId = $tenant->id;

        DB::transaction(function () use ($template, $tenantId) {
            Grade::where('tenant_id', $tenantId)->delete();
            Stage::where('tenant_id', $tenantId)->delete();

            foreach ($template['stages'] as $sIndex => $stageData) {
                $stage = Stage::create([
                    'tenant_id' => $tenantId,
                    'name' => __($stageData['name']),
                    'order' => $sIndex,
                ]);

                foreach ($stageData['grades'] as $gIndex => $gradeName) {
                    Grade::create([
                        'tenant_id' => $tenantId,
                        'stage_id' => $stage->id,
                        'name' => $gradeName,
                        'order' => $gIndex,
                    ]);
                }
            }
        });

        Stage::clearCache();

        $stages = Stage::with('grades')->orderBy('order')->get();

        return response()->json([
            'success' => true,
            'message' => __('onboarding.system_applied'),
            'stages' => $stages,
        ]);
    }

    /**
     * Step 3: Create first instructor.
     */
    private function saveInstructor(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'instructor_name' => 'required|string|max:255',
            'instructor_email' => 'required|email|max:255',
            'instructor_phone' => 'required|string|max:30',
            'instructor_specialty' => 'nullable|string|max:255',
        ]);

        // Create user account for the instructor
        $user = User::create([
            'name' => $validated['instructor_name'],
            'email' => $validated['instructor_email'],
            'password' => bcrypt(Str::random(12)),
            'role' => 'instructor',
            'tenant_id' => $tenant->id,
            'email_verified_at' => now(),
        ]);

        $instructor = Instructor::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'name' => $validated['instructor_name'],
            'email' => $validated['instructor_email'],
            'phone' => $validated['instructor_phone'],
            'specialty' => $validated['instructor_specialty'] ?? null,
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => __('onboarding.instructor_added'),
            'instructor' => $instructor,
        ]);
    }

    /**
     * Step 4: Create first course.
     */
    private function saveCourse(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'course_title' => 'required|string|max:255',
            'instructor_id' => 'required|exists:instructors,id',
            'grade_id' => 'required|exists:grades,id',
            'price' => 'nullable|numeric|min:0',
        ]);

        $course = Course::create([
            'tenant_id' => $tenant->id,
            'title' => $validated['course_title'],
            'instructor_id' => $validated['instructor_id'],
            'grade_id' => $validated['grade_id'],
            'price' => $validated['price'] ?? 0,
            'status' => 'published',
        ]);

        return response()->json([
            'success' => true,
            'message' => __('onboarding.course_created'),
            'course' => $course,
        ]);
    }

    /**
     * Step 5: Create first student.
     */
    private function saveStudent(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'student_phone' => 'required|string|max:30',
            'student_grade_id' => 'required|exists:grades,id',
            'student_course_id' => 'nullable|exists:courses,id',
        ]);

        // Create user account for the student
        $password = Str::random(8);
        $user = User::create([
            'name' => $validated['student_name'],
            'email' => Str::slug($validated['student_name']) . '-' . Str::random(4) . '@student.local',
            'password' => bcrypt($password),
            'role' => 'student',
            'tenant_id' => $tenant->id,
            'email_verified_at' => now(),
        ]);

        $student = Student::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'name' => $validated['student_name'],
            'phone' => $validated['student_phone'],
            'grade_id' => $validated['student_grade_id'],
            'status' => 'active',
            'code' => 'STD-' . strtoupper(Str::random(6)),
        ]);

        // Enroll in course if selected
        if (!empty($validated['student_course_id'])) {
            try {
                $student->courses()->attach($validated['student_course_id'], [
                    'enrolled_at' => now(),
                ]);
            } catch (\Exception $e) {
                Log::warning("Onboarding: Could not enroll student: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => __('onboarding.student_added'),
            'student' => $student,
            'password' => $password,
        ]);
    }

    /**
     * Complete the onboarding process.
     */
    public function complete(Request $request)
    {
        $tenant = app('tenant');
        $tenant->update(['onboarding_completed_at' => now()]);

        return response()->json([
            'success' => true,
            'redirect' => route('center.dashboard', ['tenant' => $tenant->domain]),
        ]);
    }
}
