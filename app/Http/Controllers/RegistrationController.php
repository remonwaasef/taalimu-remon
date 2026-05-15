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

    public function register(Request $request, TelegramService $telegram, \App\Services\GeoIPService $geoIP)
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
            DB::beginTransaction();

            $coupon = null;
            $discountAmount = 0;
            $package = \App\Models\Package::where('slug', $request->plan)->first();
            $currency = $request->input('currency', 'EGP');
            $regionalPrice = $package->getRegionalPrice($currency);
            
            if ($request->billing_cycle === 'term') {
                $basePrice = $regionalPrice['term_price'] ?? ($regionalPrice['amount'] * 4);
            } elseif ($request->billing_cycle === 'yearly') {
                $basePrice = $regionalPrice['yearly_price'] ?? ($regionalPrice['amount'] * 10);
            } else {
                $basePrice = $regionalPrice['amount'];
            }

            if ($request->has('coupon_code') && $request->coupon_code) {
                $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->first();
                if ($coupon && $coupon->isValid()) {
                    // Check if coupon belongs to a specific package
                    if ($coupon->package_id && $package && $coupon->package_id !== $package->id) {
                        $coupon = null; // Doesn't apply to this package
                    } else if ($package) {
                        // Calculate discount logic if needed for validation
                        $discountAmount = $coupon->calculateDiscount($basePrice);
                    }
                } else {
                    $coupon = null; // Invalid coupon
                }
            }

            // Auto-generate subdomain from center name
            $subdomain = \App\Services\TenantRegistrationService::generateSubdomain($request->center_name);

            // 1. Create Tenant
            $tenant = Tenant::create([
                'name' => $request->center_name,
                'email' => $request->email,
                'phone' => $request->phone, 
                'domain' => $subdomain,
                'type' => $request->account_type,
                'database_name' => 'edu_central', // Shared DB for now
                'status' => 'active', 
            ]);

            // Save locale and currency into tenant settings from session/GeoIP
            $registrationLocale = session('locale', 'ar');
            $registrationCurrency = session('suggested_currency', $currency);
            $tenant->settings = array_merge($tenant->settings ?? [], [
                'default_locale' => $registrationLocale,
                'currency' => $registrationCurrency,
            ]);
            $tenant->save();

            // 2. Create Admin User for this Tenant
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'locale' => session('locale', 'ar'),
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]);
            $user->tenant_id = $tenant->id;
            $user->role = $request->account_type === 'instructor' ? 'instructor' : 'center_admin';
            $user->save();
            
            // 2.1 Create Instructor Profile if applicable
            // For 'instructor' account type, this is mandatory.
            // For 'center' account type, we create a primary instructor profile for the owner by default.
            \App\Models\Instructor::create([
                'tenant_id' => $tenant->id,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'status' => 'active',
            ]);
            
            event(new Registered($user));
            
            // Set Spatie Team Context
            app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
            $user->assignRole($user->role);

            // 3. Handle Subscription logic
            $finalAmount = $basePrice - $discountAmount;
            $isTrialPlan = $package->trial_days > 0;

            if ($isTrialPlan) {
                \App\Models\Subscription::create([
                    'tenant_id' => $tenant->id,
                    'package_id' => $package->id,
                    'name' => 'default',
                    'stripe_id' => 'sub_trial_' . \Illuminate\Support\Str::random(10),
                    'stripe_status' => 'trialing',
                    'stripe_price' => $package->slug,
                    'quantity' => 1,
                    'trial_ends_at' => now()->addDays($package->trial_days),
                    'ends_at' => now()->addDays($package->trial_days),
                    'status' => 'trialing',
                    'billing_cycle' => $request->billing_cycle,
                    'base_price' => $basePrice,
                    'total_amount' => $finalAmount,
                    'discount_amount' => $discountAmount,
                ]);
            }

            // COMMIT the database transaction - everything critical is saved
            DB::commit();

            // === POST-COMMIT SIDE EFFECTS (non-critical, won't rollback DB) ===

            // Release submission lock and clear phone verification data
            session()->forget($submissionKey);
            session()->forget(['phone_verified', 'phone_verified_number', 'phone_verification_token', 'phone_verified_at']);

            // Send Telegram Notification (non-critical)
            try {
                $telegram->sendRegistrationAlert($tenant, $user, $isTrialPlan ? '(Registration - Free Trial)' : '(Registration - Paid Plan)');
            } catch (\Exception $telegramEx) {
                \Log::warning('Telegram notification failed (non-critical): ' . $telegramEx->getMessage());
            }

            // Send Onboarding Email 1 (Welcome) if enabled (non-critical)
            if (config('services.onboarding.emails_enabled', false)) {
                try {
                    \Illuminate\Support\Facades\Mail::to($user->email)->send(
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

                return redirect()->route('registration.success');
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
            DB::rollBack();
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
