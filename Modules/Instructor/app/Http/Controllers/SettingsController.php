<?php

namespace Modules\Instructor\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Instructor\Http\Controllers\Traits\ResolvesInstructor;

class SettingsController extends Controller
{
    use ResolvesInstructor;

    /**
     * Display the consolidated settings dashboard
     */
    public function index()
    {
        // One-time price correction logic
        try {
            $basic = \App\Models\Package::where('slug', 'basic')->first();
            if ($basic && ($basic->price > 1000 || $basic->term_price === null)) {
                $updates = [
                    'basic' => [
                        'price' => 450, 'term_price' => 1450, 'yearly_price' => 2500,
                        'regional_prices' => [
                            'EG' => ['amount' => 450, 'currency' => 'EGP', 'term_price' => 1450, 'yearly_price' => 2500],
                            'default' => ['amount' => 15, 'currency' => 'USD', 'term_price' => 49, 'yearly_price' => 85],
                        ],
                    ],
                    'pro' => [
                        'price' => 950, 'term_price' => 3450, 'yearly_price' => 6000,
                        'regional_prices' => [
                            'EG' => ['amount' => 950, 'currency' => 'EGP', 'term_price' => 3450, 'yearly_price' => 6000],
                            'default' => ['amount' => 30, 'currency' => 'USD', 'term_price' => 99, 'yearly_price' => 170],
                        ],
                    ],
                    'enterprise' => [
                        'price' => 1950, 'term_price' => 6950, 'yearly_price' => 12000,
                        'regional_prices' => [
                            'EG' => ['amount' => 1950, 'currency' => 'EGP', 'term_price' => 6950, 'yearly_price' => 12000],
                            'default' => ['amount' => 60, 'currency' => 'USD', 'term_price' => 199, 'yearly_price' => 340],
                        ],
                    ],
                ];

                foreach ($updates as $slug => $data) {
                    \App\Models\Package::where('slug', $slug)->update($data);
                }
                \Illuminate\Support\Facades\Cache::forget('subscription_packages_full');
            }
        } catch (\Exception $e) {
            \Log::error('Settings Price Fix Failed: '.$e->getMessage());
        }

        $tenant = $this->tenant;
        $settings = $tenant->settings['whatsapp'] ?? [];
        $packages = \App\Models\Package::with('features')->where('is_active', true)->orderBy('sort_order')->get();

        return view('instructor::settings', compact('tenant', 'settings', 'packages'));
    }

    /**
     * Update general center information
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'currency' => 'nullable|string|max:10',
            'logo' => 'nullable|image|max:2048',
        ]);

        $tenant = \App\Models\Tenant::findOrFail($this->tenant->id);

        $tenant->name = $request->name;
        $tenant->phone = $request->phone;
        $tenant->address = $request->address;
        $tenant->description = $request->description;

        $settings = $tenant->settings ?? [];
        $settings['currency'] = $request->currency ?? 'EGP';
        $tenant->settings = $settings;

        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $safeExt = in_array(strtolower($logoFile->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp']) ? strtolower($logoFile->getClientOriginalExtension()) : 'png';
            $safeName = \Illuminate\Support\Str::random(30).'.'.$safeExt;
            $tenant->logo = $logoFile->storeAs("{$tenant->id}/logos", $safeName, 'public');
        }

        $tenant->save();

        return redirect()->route('instructor.settings')->with('success', __('instructor::settings.update_success'));
    }

    /**
     * Show WhatsApp settings page
     */
    public function whatsapp()
    {
        $tenant = $this->tenant;
        $settings = $tenant->settings['whatsapp'] ?? [
            'enabled' => false,
            'instance_id' => '',
            'token' => '',
        ];

        return view('instructor::whatsapp', compact('settings'));
    }

    /**
     * Update WhatsApp settings
     */
    public function updateWhatsApp(Request $request)
    {
        $request->validate([
            'phone_number_id' => 'required|string',
            'access_token' => 'required|string',
            'waba_id' => 'nullable|string',
            'api_version' => 'nullable|string',
            'country_code' => 'required|string',
            'attendance_template' => 'nullable|string',
            'payment_template' => 'nullable|string',
            'debt_template' => 'nullable|string',
        ]);

        $tenant = \App\Models\Tenant::findOrFail($this->tenant->id);
        $settings = $tenant->settings ?? [];

        $settings['whatsapp'] = [
            'enabled' => $request->has('enabled'),
            'phone_number_id' => $request->phone_number_id,
            'access_token' => $request->access_token,
            'waba_id' => $request->waba_id,
            'api_version' => $request->api_version ?: 'v21.0',
            'country_code' => $request->country_code,
            'attendance_template' => $request->attendance_template,
            'payment_template' => $request->payment_template,
            'debt_template' => $request->debt_template,
        ];

        $tenant->settings = $settings;
        $tenant->save();

        return back()->with('success', __('instructor::messages.saved'));
    }

    /**
     * Update payment reminder settings
     */
    public function updateReminders(Request $request, \Modules\Center\Services\SettingsService $settingsService)
    {
        $request->validate([
            'default_due_day' => 'required|integer|min:1|max:28',
            'default_monthly_fee' => 'nullable|numeric|min:0',
            'email_reminders' => 'array',
            'whatsapp_reminders' => 'array',
            'whatsapp_before_due' => 'boolean',
            'email_template' => 'nullable|string|max:2000',
            'whatsapp_template' => 'nullable|string|max:2000',
        ]);

        $tenant = \App\Models\Tenant::findOrFail($this->tenant->id);
        $settingsService->updatePaymentReminders($tenant, $request->all());

        return back()->with('success', __('instructor::reminders.saved'));
    }

    /**
     * Update email template settings
     */
    public function updateEmailTemplates(Request $request)
    {
        $request->validate([
            'welcome_student_enabled' => 'required|boolean',
            'welcome_guardian_enabled' => 'required|boolean',
            'welcome_student_subject' => 'nullable|string|max:500',
            'welcome_student_body' => 'nullable|string|max:5000',
            'welcome_guardian_subject' => 'nullable|string|max:500',
            'welcome_guardian_body' => 'nullable|string|max:5000',
            'notif_payment_reminder_enabled' => 'required|boolean',
            'notif_payment_reminder_subject' => 'nullable|string|max:500',
            'notif_payment_reminder_body' => 'nullable|string|max:5000',
            'notif_group_enrollment_enabled' => 'required|boolean',
            'notif_group_enrollment_subject' => 'nullable|string|max:500',
            'notif_group_enrollment_body' => 'nullable|string|max:5000',
            'notif_payment_confirmed_enabled' => 'required|boolean',
            'notif_payment_confirmed_subject' => 'nullable|string|max:500',
            'notif_payment_confirmed_body' => 'nullable|string|max:5000',
        ]);

        $tenant = \App\Models\Tenant::findOrFail($this->tenant->id);
        $settings = $tenant->settings ?? [];

        $settings['email_templates'] = [
            'welcome_student_enabled' => (bool) $request->welcome_student_enabled,
            'welcome_guardian_enabled' => (bool) $request->welcome_guardian_enabled,
            'welcome_student_subject' => $request->welcome_student_subject,
            'welcome_student_body' => $request->welcome_student_body,
            'welcome_guardian_subject' => $request->welcome_guardian_subject,
            'welcome_guardian_body' => $request->welcome_guardian_body,
            'notif_payment_reminder_enabled' => (bool) $request->notif_payment_reminder_enabled,
            'notif_payment_reminder_subject' => $request->notif_payment_reminder_subject,
            'notif_payment_reminder_body' => $request->notif_payment_reminder_body,
            'notif_group_enrollment_enabled' => (bool) $request->notif_group_enrollment_enabled,
            'notif_group_enrollment_subject' => $request->notif_group_enrollment_subject,
            'notif_group_enrollment_body' => $request->notif_group_enrollment_body,
            'notif_payment_confirmed_enabled' => (bool) $request->notif_payment_confirmed_enabled,
            'notif_payment_confirmed_subject' => $request->notif_payment_confirmed_subject,
            'notif_payment_confirmed_body' => $request->notif_payment_confirmed_body,
        ];

        $tenant->settings = $settings;
        $tenant->save();

        return back()->with('success', 'تم حفظ إعدادات البريد الإلكتروني بنجاح');
    }

    /**
     * Reset email templates to defaults
     */
    public function resetEmailTemplates()
    {
        $tenant = \App\Models\Tenant::findOrFail($this->tenant->id);
        $settings = $tenant->settings ?? [];

        if (isset($settings['email_templates'])) {
            unset($settings['email_templates']);
        }

        $tenant->settings = $settings;
        $tenant->save();

        return back()->with('success', 'تم إعادة ضبط نصوص البريد الإلكتروني للوضع الافتراضي بنجاح');
    }

    /**
     * Set the application locale
     */
    public function setLocale($locale)
    {
        if (in_array($locale, ['ar', 'en', 'fr'])) {
            session(['locale' => $locale]);

            if (auth()->check()) {
                auth()->user()->update(['locale' => $locale]);
            }
        }

        return back();
    }
}
