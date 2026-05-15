<?php

namespace Modules\Center\Http\Controllers;

use Modules\Center\Http\Controllers\CenterBaseController as Controller;
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
        parent::__construct();
        $this->settingsService = $settingsService;
    }

    public function index()
    {
        $this->authorize('update', $this->tenant);
        $stages = Stage::with('grades')->orderBy('order')->get();
        $templates = config('academic.templates', []);
        return view('center::settings.index', compact('stages', 'templates'));
    }

    public function update(UpdateSettingsRequest $request)
    {
        $tenant = Tenant::findOrFail($this->tenant->id);
        
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
        $tenant = Tenant::findOrFail($this->tenant->id);

        $this->settingsService->updateAcademicStructure($tenant, $request->validated());

        return back()->with('success', __('center::messages.msg_079'));
    }

    public function applyAcademicTemplate(ApplyTemplateRequest $request)
    {
        try {
            $this->settingsService->applyTemplate($this->tenant, $request->template_key);
            return back()->with('success', __('center::messages.msg_080'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to apply academic template: " . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تطبيق النموذج: ' . $e->getMessage());
        }
    }

    public function resetEmailTemplates()
    {
        $tenant = Tenant::findOrFail($this->tenant->id);
        $settings = $tenant->settings ?? [];

        if (isset($settings['email_templates'])) {
            unset($settings['email_templates']);
        }

        $tenant->settings = $settings;
        $tenant->save();

        return back()->with('success', 'تم إعادة ضبط نصوص البريد الإلكتروني للوضع الافتراضي بنجاح');
    }

    /**
     * Update payment reminder scheduling settings for the tenant.
     * Saves default due day, monthly fee, pre-due email reminders,
     * post-due WhatsApp reminders, and overdue auto-repeat configuration.
     */
    public function updateReminders(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'default_due_day'                   => 'required|integer|min:1|max:28',
            'default_monthly_fee'               => 'nullable|numeric|min:0',
            'email_reminders'                   => 'array',
            'email_reminders.*.days_before'     => 'required|integer|min:0',
            'email_reminders.*.enabled'         => 'required|boolean',
            'whatsapp_reminders'                => 'array',
            'whatsapp_reminders.*.days_after'   => 'required|integer|min:1',
            'whatsapp_reminders.*.enabled'      => 'required|boolean',
            'whatsapp_before_due'               => 'nullable|boolean',
            'overdue_repeat_enabled'            => 'nullable|boolean',
            'overdue_repeat_interval'           => 'nullable|integer|min:1|max:30',
            'overdue_max_reminders'             => 'nullable|integer|min:1|max:50',
            'email_template'                    => 'nullable|string|max:2000',
            'whatsapp_template'                 => 'nullable|string|max:2000',
        ]);

        $tenant = Tenant::findOrFail($this->tenant->id);
        
        $this->settingsService->updatePaymentReminders($tenant, $request->all());

        return back()->with('success', __('center::settings.reminders.saved'));
    }
}
