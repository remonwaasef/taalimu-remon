<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stage;
use App\Models\Grade;
use App\Models\Instructor;
use App\Models\Course;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OnboardingController extends Controller
{
    /**
     * Apply an academic template (Step 1)
     */
    public function applyTemplate(Request $request)
    {
        $request->validate([
            'template_key' => 'required|string',
        ]);

        $templates = config('academic.templates', []);
        if (!isset($templates[$request->template_key])) {
            return response()->json(['success' => false, 'message' => 'Invalid template choice'], 422);
        }

        $template = $templates[$request->template_key];
        $tenantId = app('tenant')->id;

        try {
            DB::transaction(function () use ($template, $tenantId) {
                // Wipe existing structure to avoid conflicts during onboarding
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
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error("Onboarding Template Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'خطأ أثناء تطبيق النموذج: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Quick add instructor (Step 3)
     */
    public function storeInstructor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        try {
            $instructor = Instructor::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'status' => 'active',
                'tenant_id' => app('tenant')->id,
            ]);
            return response()->json(['success' => true, 'id' => $instructor->id]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Quick add course (Step 4)
     */
    public function storeCourse(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'instructor_id' => 'required|exists:instructors,id',
        ]);

        try {
            $course = Course::create([
                'title' => $request->title,
                'instructor_id' => $request->instructor_id,
                'status' => 'published',
                'tenant_id' => app('tenant')->id,
                'price' => 0,
                'sessions_count' => 0,
            ]);
            return response()->json(['success' => true, 'id' => $course->id]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Quick register student (Step 5)
     */
    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'grade_id' => 'required|exists:grades,id',
        ]);

        try {
            Student::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'grade_id' => $request->grade_id,
                'status' => 'active',
                'tenant_id' => app('tenant')->id,
                'joined_at' => now(),
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Quick enrollment (Step 6)
     */
    public function enroll(Request $request)
    {
        try {
            $student = Student::where('tenant_id', app('tenant')->id)->latest()->first();
            $course = Course::where('tenant_id', app('tenant')->id)->latest()->first();

            if ($student && $course) {
                Enrollment::updateOrCreate([
                    'user_id' => $student->user_id, // Note: Enrollment uses user_id in this system
                    'course_id' => $course->id,
                    'tenant_id' => app('tenant')->id,
                ], [
                    'status' => 'active',
                    'enrolled_at' => now(),
                ]);
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false, 'message' => 'لم يتم العثور على طالب أو دورة'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get grades for the current tenant
     */
    public function getGrades()
    {
        $grades = Grade::with('stage')->orderBy('order')->get();
        return response()->json($grades);
    }

    /**
     * Mark onboarding as complete
     */
    public function complete()
    {
        $tenant = app('tenant');
        $settings = $tenant->settings ?? [];
        $settings['onboarding_completed'] = true;
        $tenant->settings = $settings;
        $tenant->save();

        return response()->json(['success' => true]);
    }
}
