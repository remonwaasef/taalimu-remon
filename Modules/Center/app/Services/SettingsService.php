<?php

namespace Modules\Center\Services;

use App\Models\Tenant;
use App\Models\Stage;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Update basic tenant settings including files.
     */
    public function updateBasicSettings(Tenant $tenant, array $data, $logoFile = null, $faviconFile = null)
    {
        Log::info('Settings Update Attempt', ['tenant_id' => $tenant->id, 'data' => collect($data)->except(['settings'])->toArray()]);

        // Basic Info
        $tenant->fill(collect($data)->only([
            'name', 'phone', 'address', 'description', 
            'facebook_url', 'instagram_url', 'twitter_url', 
            'youtube_url', 'linkedin_url', 'timezone'
        ])->toArray());

        $settings = $tenant->settings ?? [];

        if (isset($data['settings'])) {
            $settings = array_replace_recursive($settings, $data['settings']);
        }

        // Upload files
        if ($logoFile) {
            $tenant->logo = $logoFile->store("{$tenant->id}/logos", 'public');
        }

        if ($faviconFile) {
            $tenant->favicon = $faviconFile->store("{$tenant->id}/favicons", 'public');
        }

        $tenant->settings = $settings;
        $tenant->save();

        Cache::forget("tenant_lookup_{$tenant->domain}");

        return $tenant;
    }

    /**
     * Update Academic Structural Settings (Stages and Grades).
     */
    public function updateAcademicStructure(Tenant $tenant, array $data)
    {
        if (isset($data['deleted_stages'])) {
            Stage::whereIn('id', $data['deleted_stages'])->delete();
        }
        if (isset($data['deleted_grades'])) {
            Grade::whereIn('id', $data['deleted_grades'])->delete();
        }

        if (isset($data['settings'])) {
            $settings = $tenant->settings ?? [];
            
            if (isset($data['settings']['academic']['late_levels'])) {
                if (!isset($settings['academic'])) {
                    $settings['academic'] = [];
                }
                $settings['academic']['late_levels'] = $data['settings']['academic']['late_levels'];
                
                $mergedSettings = $data['settings'];
                unset($mergedSettings['academic']['late_levels']);
                $settings = array_replace_recursive($settings, $mergedSettings);
            } else {
                $settings = array_replace_recursive($settings, $data['settings']);
            }

            $tenant->settings = $settings;
            $tenant->save();
        }

        if (isset($data['stages'])) {
            foreach ($data['stages'] as $index => $stageData) {
                $stage = Stage::updateOrCreate(
                    ['id' => $stageData['id'] ?? null],
                    [
                        'name' => $stageData['name'],
                        'order' => $index
                    ]
                );

                if (isset($stageData['grades'])) {
                    foreach ($stageData['grades'] as $gIndex => $gradeData) {
                        Grade::updateOrCreate(
                            ['id' => $gradeData['id'] ?? null],
                            [
                                'stage_id' => $stage->id,
                                'name' => $gradeData['name'],
                                'order' => $gIndex
                            ]
                        );
                    }
                }
            }
        }
    }

    /**
     * Apply a predefined academic template structure.
     */
    public function applyTemplate(Tenant $tenant, string $templateKey)
    {
        $template = config("academic.templates.{$templateKey}");
        $tenantId = $tenant->id;

        Log::info("Applying academic template {$templateKey} for tenant {$tenantId}");

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
    }
}
