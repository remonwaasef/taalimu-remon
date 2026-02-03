<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stage;
use App\Models\Grade;

class SettingsController extends Controller
{
    public function index()
    {
        $this->authorize('update', app('tenant'));
        $stages = Stage::with('grades')->orderBy('order')->get();
        $templates = config('academic.templates', []);
        return view('center::settings.index', compact('stages', 'templates'));
    }

    public function update(Request $request)
    {
        $currentTenant = app('tenant');
        $tenant = \App\Models\Tenant::findOrFail($currentTenant->id);
        $this->authorize('update', $tenant);

        \Illuminate\Support\Facades\Log::info('Settings Update Attempt', ['tenant_id' => $tenant->id, 'data' => $request->except(['logo', 'favicon', '_token'])]);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
            'settings' => 'nullable|array',
        ]);

        // Update basic and new info directly in columns
        $tenant->name = $request->name;
        $tenant->phone = $request->phone;
        $tenant->address = $request->address;
        $tenant->description = $request->description;
        $tenant->facebook_url = $request->facebook_url;
        $tenant->instagram_url = $request->instagram_url;
        $tenant->twitter_url = $request->twitter_url;
        $tenant->youtube_url = $request->youtube_url;
        $tenant->linkedin_url = $request->linkedin_url;

        // Retrieve current settings or init array for academic/financial/appearance
        $settings = $tenant->settings ?? [];

        // Merge other structured settings from form (e.g., settings[academic][year])
        if ($request->has('settings')) {
            $settings = array_replace_recursive($settings, $request->input('settings'));
        }

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store("{$tenant->id}/logos", 'public');
            $tenant->logo = $path;
        }

        // Handle Favicon Upload
        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store("{$tenant->id}/favicons", 'public');
            $tenant->favicon = $path;
        }

        $tenant->settings = $settings;
        $tenant->save();

        // Extra insurance: Force clear the specific tenant lookup cache
        \Illuminate\Support\Facades\Cache::forget("tenant_lookup_{$tenant->domain}");

        return back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }

    public function updateAcademic(Request $request)
    {
        $this->authorize('update', app('tenant'));
        $request->validate([
            'stages' => 'nullable|array',
            'stages.*.id' => 'nullable|exists:stages,id',
            'stages.*.name' => 'required|string|max:255',
            'stages.*.grades' => 'nullable|array',
            'stages.*.grades.*.id' => 'nullable|exists:grades,id',
            'stages.*.grades.*.name' => 'required|string|max:255',
            'deleted_stages' => 'nullable|array',
            'deleted_grades' => 'nullable|array',
        ]);

        // Handle Deletions
        if ($request->deleted_stages) {
            Stage::whereIn('id', $request->deleted_stages)->delete();
        }
        if ($request->deleted_grades) {
            Grade::whereIn('id', $request->deleted_grades)->delete();
        }

        // Handle Academic Settings (Year, Grading, etc.)
        if ($request->has('settings')) {
            $tenant = app('tenant');
            $settings = $tenant->settings ?? [];
            $settings = array_replace_recursive($settings, $request->input('settings'));
            $tenant->settings = $settings;
            $tenant->save();
        }

        // Handle Upserts
        if ($request->stages) {
            foreach ($request->stages as $index => $stageData) {
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

        return back()->with('success', 'تم تحديث الهيكل الأكاديمي بنجاح');
    }

    public function applyAcademicTemplate(Request $request)
    {
        $this->authorize('update', app('tenant'));
        $request->validate([
            'template_key' => 'required|string|in:' . implode(',', array_keys(config('academic.templates', []))),
        ]);

        $template = config("academic.templates.{$request->template_key}");

        // Transaction for safety
        \Illuminate\Support\Facades\DB::transaction(function () use ($template) {
            // Optional: Backup or clear existing? User requested avoiding manual entry, 
            // usually implies replacing or starting fresh.
            // Let's clear existing to avoid messy mix.
            Grade::query()->delete();
            Stage::query()->delete();

            foreach ($template['stages'] as $sIndex => $stageData) {
                $stage = Stage::create([
                    'name' => $stageData['name'],
                    'order' => $sIndex,
                ]);

                foreach ($stageData['grades'] as $gIndex => $gradeName) {
                    Grade::create([
                        'stage_id' => $stage->id,
                        'name' => $gradeName,
                        'order' => $gIndex,
                    ]);
                }
            }
        });

        return back()->with('success', 'تم تطبيق النموذج الأكاديمي بنجاح');
    }
}
