<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\Stage;
use App\Models\Tenant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Tenant settings management.
 *
 * Moved from Modules\Center\Services\SettingsService to the shared app layer:
 * it is consumed by the Center module, the Instructor module and the main
 * routes file, so it must not live inside a single module (modules should not
 * depend on their sibling modules).
 */
class SettingsService
{
    use \App\Traits\HandlesFileUploads;

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
            'youtube_url', 'linkedin_url', 'timezone',
        ])->toArray());

        $settings = $tenant->settings ?? [];

        if (isset($data['settings'])) {
            $settings = array_replace_recursive($settings, $data['settings']);
        }

        // Upload files using the trait and safely delete old ones
        if ($logoFile) {
            $tenant->logo = $this->uploadFile($logoFile, $tenant->logo, 'logos', 'public');
        }

        if ($faviconFile) {
            $tenant->favicon = $this->uploadFile($faviconFile, $tenant->favicon, 'favicons', 'public');
        }

        $tenant->settings = $settings;
        $tenant->save();

        Cache::forget("taalimu:tenancy:domain:{$tenant->domain}");
        Cache::forget("tenant_lookup_{$tenant->domain}");

        return $tenant;
    }

    /**
     * Update Academic Structural Settings (Stages and Grades).
     */
    public function updateAcademicStructure(Tenant $tenant, array $data)
    {
        if (isset($data['deleted_stages'])) {
            foreach ($data['deleted_stages'] as $stageId) {
                $stage = \App\Models\Stage::find($stageId);
                if ($stage) {
                    $hasStudents = \App\Models\Student::whereHas('grade', function ($q) use ($stageId) {
                        $q->where('stage_id', $stageId);
                    })->exists();

                    if ($hasStudents) {
                        $stage->delete(); // Soft delete
                    } else {
                        $stage->forceDelete(); // Hard delete
                    }
                }
            }
        }

        if (isset($data['deleted_grades'])) {
            foreach ($data['deleted_grades'] as $gradeId) {
                $grade = \App\Models\Grade::find($gradeId);
                if ($grade) {
                    $hasStudents = \App\Models\Student::where('grade_id', $gradeId)->exists();
                    if ($hasStudents) {
                        $grade->delete(); // Soft delete
                    } else {
                        $grade->forceDelete(); // Hard delete
                    }
                }
            }
        }

        if (isset($data['settings'])) {
            $settings = $tenant->settings ?? [];

            if (isset($data['settings']['academic']['late_levels'])) {
                if (! isset($settings['academic'])) {
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
                        'order' => $index,
                    ]
                );

                if (isset($stageData['grades'])) {
                    foreach ($stageData['grades'] as $gIndex => $gradeData) {
                        Grade::updateOrCreate(
                            ['id' => $gradeData['id'] ?? null],
                            [
                                'stage_id' => $stage->id,
                                'name' => $gradeData['name'],
                                'order' => $gIndex,
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

        // Update the tenant setting so it reflects correctly in the dropdown
        $settings = $tenant->settings ?? [];
        $settings['education_system'] = $templateKey;
        $tenant->settings = $settings;
        $tenant->save();

        // Clear lookup cache
        Cache::forget("taalimu:tenancy:domain:{$tenant->domain}");
        Cache::forget("tenant_lookup_{$tenant->domain}");

        Stage::clearCache();
    }

    /**
     * Update payment reminder scheduling settings for the tenant.
     */
    public function updatePaymentReminders(Tenant $tenant, array $data)
    {
        $settings = $tenant->settings ?? [];

        $settings['payment_reminders'] = [
            'default_due_day' => (int) ($data['default_due_day'] ?? 1),
            'default_monthly_fee' => ! empty($data['default_monthly_fee']) ? (float) $data['default_monthly_fee'] : null,
            'email_reminders' => collect($data['email_reminders'] ?? [])->map(function ($item) {
                return [
                    'days_before' => (int) ($item['days_before'] ?? 0),
                    'enabled' => (bool) ($item['enabled'] ?? false),
                ];
            })->toArray(),
            'whatsapp_reminders' => collect($data['whatsapp_reminders'] ?? [])->map(function ($item) {
                return [
                    'days_after' => (int) ($item['days_after'] ?? 0),
                    'enabled' => (bool) ($item['enabled'] ?? false),
                ];
            })->toArray(),
            'whatsapp_before_due' => (bool) ($data['whatsapp_before_due'] ?? false),
            'overdue_repeat_enabled' => (bool) ($data['overdue_repeat_enabled'] ?? false),
            'overdue_repeat_interval' => (int) ($data['overdue_repeat_interval'] ?? 7),
            'overdue_max_reminders' => ! empty($data['overdue_max_reminders']) ? (int) $data['overdue_max_reminders'] : null,
            'email_template' => $data['email_template'] ?? null,
            'whatsapp_template' => $data['whatsapp_template'] ?? null,
            // Free WhatsApp channel via Telegram "click-to-send" links (reminders:send-wa-links).
            'wa_telegram_enabled' => (bool) ($data['wa_telegram_enabled'] ?? false),
            'telegram_chat_id' => ! empty($data['telegram_chat_id']) ? trim((string) $data['telegram_chat_id']) : null,
            'wa_days_before' => (int) ($data['wa_days_before'] ?? 3),
        ];

        $tenant->settings = $settings;
        $tenant->save();

        return $tenant;
    }
}
