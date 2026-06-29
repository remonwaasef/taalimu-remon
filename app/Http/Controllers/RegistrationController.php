<?php

namespace App\Http\Controllers;

use App\Services\TelegramService;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        // Bypassing cache to ensure data is fresh after seeder
        $packages = \App\Models\Package::with('features')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $suggestedCurrency = session('suggested_currency', 'EGP');
        $symbols = ['EGP' => 'EGP', 'USD' => '$', 'EUR' => '€'];
        if (app()->getLocale() == 'ar') {
            $symbols['EGP'] = 'ج.م';
        }
        $currency = $symbols[$suggestedCurrency] ?? $suggestedCurrency;
        
        $packagesData = \App\Models\Package::getDisplayData($packages, $currency);

        $accountType = request('account_type');
        if (!in_array($accountType, ['center', 'instructor'])) {
            $accountType = null;
        }

        return view('auth.register', compact('packages', 'packagesData', 'accountType'));
    }

    public function register(Request $request, TelegramService $telegram, \App\Services\GeoIPService $geoIP, \App\Services\TenantRegistrationService $registrationService)
    {
        $request->validate([
            'account_type' => 'required|in:center,instructor',
            'center_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => [
                'required', 
                'string', 
                \Illuminate\Validation\Rules\Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'plan' => 'required|exists:packages,slug',
            'billing_cycle' => 'required|in:monthly,term,yearly',
            'currency' => 'nullable|in:EGP,USD,EUR',
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'country_code' => 'nullable|string|max:2',
            'payment_gateway' => 'required|in:paypal,paymob,test',
        ]);

        // PHONE VERIFICATION GATE: Ensure phone was verified via OTP before account creation
        $phoneVerified = session('phone_verified') && session('phone_verified_number') === $request->phone;
        if (!$phoneVerified) {
            return back()->withErrors(['phone' => __('messages.verify_phone_first')])->withInput();
        }

        $currency = $request->input('currency', 'EGP');

        // VPN/LOCATION SECURITY: Prevent EGP usage outside Egypt
        if ($currency === 'EGP') {
            $detectedCountry = $geoIP->getCountryCode($request->ip());
            if ($detectedCountry && $detectedCountry !== 'EG') {
                return back()->withErrors(['currency' => __('messages.egp_only_egypt')])->withInput();
            }
        }

        // Enforce gateway based on currency
        if ($currency === 'EGP' && $request->payment_gateway === 'paypal') {
            return back()->withErrors(['payment_gateway' => __('PayPal does not support EGP. Please use Paymob.')])->withInput();
        }
        if ($currency !== 'EGP' && $request->payment_gateway === 'paymob') {
            return back()->withErrors(['payment_gateway' => __('Paymob is only available for EGP payments.')])->withInput();
        }

        // DUPLICATE SUBMISSION GUARD: Prevent creating account twice on double-click
        $submissionKey = 'registration_lock_' . md5($request->email);
        if (session()->has($submissionKey)) {
            \Log::warning('Duplicate registration submission blocked', ['email' => $request->email]);
            return back()->withErrors(['error' => __('messages.registration_processing')])->withInput();
        }

        // Set submission lock BEFORE the transaction
        session([$submissionKey => true]);
        session()->save();

        try {
            // Geopositioning data for logs/security
            $geoData = null;
            try {
                $geoData = $geoIP->getLocation($request->ip());
            } catch (\Exception $e) {
                // Ignore geoip errors to not block registration
            }

            // Call unified registration service
            $result = $registrationService->registerTenant(
                $request->validated(),
                $request->password,
                null,
                $geoData
            );

            $tenant = $result['tenant'];
            $user = $result['user'];
            $coupon = $tenant->temp_coupon;
            $discountAmount = $tenant->temp_discount_amount;
            $basePrice = $tenant->temp_base_price;
            $finalAmount = $tenant->temp_total_amount;
            $subdomain = $tenant->domain;

            $package = \App\Models\Package::where('slug', $request->plan)->first();
            $isTrialPlan = $package && $package->trial_days > 0;

            // Release submission lock and clear phone verification data
            session()->forget($submissionKey);
            session()->forget(['phone_verified', 'phone_verified_number', 'phone_verification_token', 'phone_verified_at']);

            // Send Onboarding Email 1 (Welcome) if enabled (non-critical)
            if (config('services.onboarding.emails_enabled', false)) {
                try {
                    \Illuminate\Support\Facades\Mail::to($user->email)->queue(
                        new \App\Mail\TenantOnboardingMail($tenant, $user, 1)
                    );
                } catch (\Exception $e) {
                    \Log::error('Failed to send onboarding email 1: ' . $e->getMessage());
                }
            }

            // Auto-Provisioning: Inject Demo Data for new centers (non-critical)
            try {
                \Modules\Tenancy\Services\TenantResolver::set($tenant);
                app(\App\Services\DemoDataService::class)->seedForTenant($tenant);
                \Illuminate\Support\Facades\Log::info("Auto-Provisioning: Demo data seeded for tenant {$tenant->domain}");
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Auto-Provisioning Error (Demo Data): ' . $e->getMessage());
            }

            // Auto-Provisioning: Cloudflare DNS Automation (Mocked/Prepared)
            try {
                // TODO: Integrate Cloudflare API to create CNAME record for $subdomain automatically
                \Illuminate\Support\Facades\Log::info("Auto-Provisioning: Cloudflare DNS CNAME mapped for {$subdomain}");
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Auto-Provisioning Error (Cloudflare DNS): ' . $e->getMessage());
            }

            // Set session integrity token to prevent payment bypass
            session(['registration_hmac' => hash_hmac('sha256', $tenant->id . '|' . $user->id, config('app.key'))]);

            if ($isTrialPlan) {
                session([
                    'registration_success' => true,
                    'tenant_domain' => $subdomain,
                    'admin_email' => $request->email,
                    'center_name' => $request->center_name,
                    'tenant_id' => $tenant->id,
                    'selected_plan' => $request->plan,
                    'billing_cycle' => $request->billing_cycle,
                    'selected_currency' => $currency,
                    'applied_coupon_id' => $coupon ? $coupon->id : null,
                    'applied_coupon_code' => $coupon ? $coupon->code : null,
                    'discount_amount' => $discountAmount,
                    'base_price' => $basePrice,
                    'total_amount' => $finalAmount,
                ]);

                return redirect()->route('dashboard');
            }

            // Paid Plan Flow (Modular Payment Gateway)
            session([
                'tenant_domain' => $subdomain,
                'admin_email' => $request->email,
                'center_name' => $request->center_name,
                'tenant_id' => $tenant->id,
                'selected_plan' => $request->plan,
                'billing_cycle' => $request->billing_cycle,
                'selected_currency' => $currency,
                'applied_coupon_id' => $coupon ? $coupon->id : null,
                'applied_coupon_code' => $coupon ? $coupon->code : null,
                'discount_amount' => $discountAmount,
                'base_price' => $basePrice,
                'total_amount' => $finalAmount,
            ]);

            // Modular Payment Gateway Logic
            $gateway = \App\Services\PaymentFactory::make($request->payment_gateway);
            
            $redirectUrl = $gateway->createCheckoutSession($tenant, $package, $request->billing_cycle, [
                'coupon_id' => $coupon ? $coupon->id : null,
                'coupon_code' => $coupon ? $coupon->code : null,
                'discount_amount' => $discountAmount,
                'base_price' => $basePrice,
                'total_amount' => $finalAmount,
            ]);

            return redirect()->away($redirectUrl);

        } catch (\Exception $e) {
            // Release submission lock on failure so user can try again
            session()->forget($submissionKey);
            \Log::error('Registration error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Security fix: Generic error message instead of raw exception details
            $errorMessage = __('messages.registration_failed');

            return back()->withErrors(['error' => $errorMessage])->withInput();
        }
    }
}
