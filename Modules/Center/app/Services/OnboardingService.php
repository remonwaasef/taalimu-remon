<?php

namespace Modules\Center\Services;

use App\Models\Tenant;
use App\Models\Instructor;
use App\Models\Course;
use App\Models\Student;

class OnboardingService
{
    public function getStatus(int $tenantId)
    {
        $tenantModel = Tenant::find($tenantId);
        
        if (!$tenantModel) {
            return null;
        }

        $onboardingCompleted = !is_null($tenantModel->onboarding_completed_at);
        
        $hasInstructors = Instructor::where('tenant_id', $tenantId)->exists();
        $hasCourses = Course::where('tenant_id', $tenantId)->exists();
        $hasStudents = Student::where('tenant_id', $tenantId)->exists();

        $steps = [
            ['done' => $hasInstructors, 'label' => 'إضافة مدرس'],
            ['done' => $hasCourses, 'label' => 'إنشاء دورة'],
            ['done' => $hasStudents, 'label' => 'تسجيل طالب']
        ];

        $completedSteps = count(array_filter($steps, fn($s) => $s['done']));
        $progress = ($completedSteps / count($steps)) * 100;

        return (object) [
            'show_cards' => !$onboardingCompleted,
            'show_sidebar_ring' => !$onboardingCompleted,
            'instructor_added' => $hasInstructors,
            'course_added' => $hasCourses,
            'student_added' => $hasStudents,
            'all_done' => $hasInstructors && $hasCourses && $hasStudents,
            'progress' => (int) $progress,
            'steps' => $steps
        ];
    }
}
