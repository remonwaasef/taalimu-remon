<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use Modules\Center\Http\Requests\UpdateSettingsRequest;
use Modules\Center\Http\Requests\UpdateAcademicRequest;
use Modules\Center\Http\Requests\ApplyTemplateRequest;
use Modules\Center\Services\SettingsService;
use App\Models\Tenant;

class SettingsController extends Controller
{
    protected SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index()
    {
        $this->authorize('update', app('tenant'));
        $stages = Stage::with('grades')->orderBy('order')->get();
        $templates = config('academic.templates', []);
        return view('center::settings.index', compact('stages', 'templates'));
    }

    public function update(UpdateSettingsRequest $request)
    {
        $tenant = Tenant::findOrFail(app('tenant')->id);
        
        $this->settingsService->updateBasicSettings(
            $tenant,
            $request->validated(),
            $request->file('logo'),
            $request->file('favicon')
        );

        return back()->with('success', __('center::messages.msg_078'));
    }

    public function updateAcademic(UpdateAcademicRequest $request)
    {
        $tenant = Tenant::findOrFail(app('tenant')->id);

        $this->settingsService->updateAcademicStructure($tenant, $request->validated());

        return back()->with('success', __('center::messages.msg_079'));
    }

    public function applyAcademicTemplate(ApplyTemplateRequest $request)
    {
        try {
            $this->settingsService->applyTemplate(app('tenant'), $request->template_key);
            return back()->with('success', __('center::messages.msg_080'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to apply academic template: " . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تطبيق النموذج: ' . $e->getMessage());
        }
    }
}
