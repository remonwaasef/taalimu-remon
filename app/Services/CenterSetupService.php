<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\Stage;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;

class CenterSetupService
{
    /**
     * Setup the center based on selected type.
     *
     * @param Tenant $tenant
     * @param string $type
     * @return bool
     */
    public function setup(Tenant $tenant, string $type)
    {
        return DB::transaction(function () use ($tenant, $type) {
            // Update tenant with center type
            $tenant->update(['center_type' => $type]);
            
            $template = $this->getTemplate($type);
            
            // 1. Setup Academic Structure
            $this->setupAcademicStructure($tenant, $template['academic'] ?? []);
            
            // 2. Setup Default Settings
            $this->setupDefaultSettings($tenant, $template['settings'] ?? []);
            
            return true;
        });
    }

    /**
     * Get templates for center types.
     */
    protected function getTemplate(string $type)
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;

        $templates = [
            'tutoring' => [
                'academic' => [
                    ['name' => 'المرحلة الابتدائية', 'grades' => ['الصف الأول الابتدائى', 'الصف الثاني الابتدائى', 'الصف الثالث الابتدائى', 'الصف الرابع الابتدائى', 'الصف الخامس الابتدائى', 'الصف السادس الابتدائى']],
                    ['name' => 'المرحلة الإعدادية', 'grades' => ['الصف الأول الإعدادي', 'الصف الثاني الإعدادي', 'الصف الثالث الإعدادي']],
                    ['name' => 'المرحلة الثانوية', 'grades' => ['الصف الأول الثانوي', 'الصف الثاني الثانوي', 'الصف الثالث الثانوي']],
                ],
                'settings' => [
                    'currency' => 'EGP',
                    'academic_year' => "$currentYear-$nextYear",
                    'grading_system' => 'percentage',
                    'attendance_mode' => 'qr_code',
                ]
            ],
            'languages' => [
                'academic' => [
                    ['name' => 'Beginner Levels', 'grades' => ['Level A1', 'Level A2']],
                    ['name' => 'Intermediate Levels', 'grades' => ['Level B1', 'Level B2']],
                    ['name' => 'Advanced Levels', 'grades' => ['Level C1', 'Level C2']],
                ],
                'settings' => [
                    'currency' => 'EGP',
                    'academic_year' => "$currentYear",
                    'grading_system' => 'points',
                ]
            ],
            'quran' => [
                'academic' => [
                    ['name' => 'مستويات الحفظ', 'grades' => ['المستوى الأول', 'المستوى الثاني', 'المستوى الثالث', 'المستوى الرابع', 'المستوى الخامس']],
                ],
                'settings' => [
                    'currency' => 'EGP',
                    'academic_year' => "$currentYear",
                ]
            ],
            'vocational' => [
                'academic' => [
                    ['name' => 'Professional Training', 'grades' => ['Foundations', 'Intermediate', 'Advanced', 'Professional']],
                ],
                'settings' => [
                    'currency' => 'EGP',
                    'academic_year' => "$currentYear",
                ]
            ],
            'institute' => [
                'academic' => [
                    ['name' => 'المرحلة الابتدائية', 'grades' => ['الصف الأول الابتدائى', 'الصف الثاني الابتدائى', 'الصف الثالث الابتدائى', 'الصف الرابع الابتدائى', 'الصف الخامس الابتدائى', 'الصف السادس الابتدائى']],
                    ['name' => 'المرحلة الإعدادية', 'grades' => ['الصف الأول الإعدادي', 'الصف الثاني الإعدادي', 'الصف الثالث الإعدادي']],
                    ['name' => 'المرحلة الثانوية', 'grades' => ['الصف الأول الثانوي', 'الصف الثاني الثانوي', 'الصف الثالث الثانوي']],
                ],
                'settings' => [
                    'currency' => 'EGP',
                    'academic_year' => "$currentYear-$nextYear",
                ]
            ],
            'other' => [
                'academic' => [],
                'settings' => [
                    'currency' => 'EGP',
                    'academic_year' => "$currentYear-$nextYear",
                ]
            ]
        ];

        return $templates[$type] ?? $templates['other'];
    }

    /**
     * Create stages and grades for the tenant.
     */
    protected function setupAcademicStructure(Tenant $tenant, array $stages)
    {
        foreach ($stages as $index => $stageData) {
            $stage = Stage::create([
                'tenant_id' => $tenant->id,
                'name' => $stageData['name'],
                'order' => $index + 1
            ]);

            foreach ($stageData['grades'] as $gIndex => $gradeName) {
                Grade::create([
                    'tenant_id' => $tenant->id,
                    'stage_id' => $stage->id,
                    'name' => $gradeName,
                    'order' => $gIndex + 1
                ]);
            }
        }
    }

    /**
     * Merge default settings for the tenant.
     */
    protected function setupDefaultSettings(Tenant $tenant, array $defaultSettings)
    {
        $settings = $tenant->settings ?? [];
        $settings = array_merge($settings, $defaultSettings);
        $tenant->update(['settings' => $settings]);
    }
}
