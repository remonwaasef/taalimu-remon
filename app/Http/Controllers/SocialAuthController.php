<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use App\Services\TelegramService;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle(Request $request)
    {
        // Save plan selection before redirecting to Google OAuth
        if ($request->has('plan')) {
            session(['selected_plan' => $request->plan]);
        }
        if ($request->has('cycle')) {
            session(['billing_cycle' => $request->cycle]);
        }
        if ($request->has('account_type')) {
            session(['account_type' => $request->account_type]);
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // 1. Check if user already exists by google_id → Login directly
            $user = User::where('google_id', $googleUser->id)->first();
            if ($user) {
                Auth::login($user, true);
                
                // Redirect based on role or tenant type
                if ($user->role === 'instructor' || ($user->tenant && $user->tenant->type === 'instructor')) {
                    return redirect()->route('instructor.dashboard', ['tenant' => $user->tenant->domain]);
                }
                
                return redirect()->intended('/dashboard');
            }

            // 2. Check if user exists with same email → Link google_id & Login
            $existingUser = User::where('email', $googleUser->email)->first();
            if ($existingUser) {
                $existingUser->forceFill([
                    'google_id' => $googleUser->id,
                    'email_verified_at' => $existingUser->email_verified_at ?? now(),
                ])->save();
                
                Auth::login($existingUser, true);
                
                if ($existingUser->role === 'instructor' || ($existingUser->tenant && $existingUser->tenant->type === 'instructor')) {
                    return redirect()->route('instructor.dashboard', ['tenant' => $existingUser->tenant->domain]);
                }

                return redirect()->intended('/dashboard');
            }

            // 3. New user → Pass Google data via encrypted token (session-independent)
            $googleData = [
                'id'    => $googleUser->id,
                'name'  => $googleUser->name,
                'email' => $googleUser->email,
            ];
            
            // Encrypt the Google user data into a URL-safe token
            $token = encrypt(json_encode($googleData));

            // Also store in session as a fallback
            session(['google_user' => $googleData]);

            // Build redirect URL with plan/cycle persisted from session
            $planParam  = session('selected_plan', '');
            $cycleParam = session('billing_cycle', 'monthly');
            $accountTypeParam = session('account_type', 'center');
            $query = http_build_query(array_filter([
                'plan'  => $planParam,
                'cycle' => $cycleParam,
                'account_type' => $accountTypeParam,
            ]));

            $redirectUrl = route('google.complete-registration') . ($query ? '?' . $query : '');

            \Log::info('Google Callback Success', [
                'session_id' => session()->getId(),
                'google_email' => $googleUser->email,
                'has_token' => !empty($token),
            ]);

            session()->save();

            return redirect($redirectUrl);

        } catch (\Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('login.portal')
                ->withErrors(['email' => __('Unable to login with Google. Please try again.')]);
        }
    }

    /**
     * Show the complete registration form for Google-authenticated users.
     */
    public function showCompleteRegistration(Request $request)
    {
        // Try to get Google user data: first from session, then from encrypted token
        $googleData = session('google_user');
        
        if (!$googleData && $request->has('token')) {
            try {
                $googleData = json_decode(decrypt($request->query('token')), true);
                if ($googleData && isset($googleData['id'], $googleData['email'])) {
                    // Restore it into session for the POST form submission
                    session(['google_user' => $googleData]);
                    session()->save();
                    \Log::info('Google user data restored from encrypted token', ['email' => $googleData['email']]);
                } else {
                    $googleData = null;
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to decrypt Google token', ['error' => $e->getMessage()]);
                $googleData = null;
            }
        }

        if (!$googleData) {
            \Log::warning('Google Complete Registration - No data available', [
                'session_id' => session()->getId(),
                'has_token' => $request->has('token'),
            ]);
            return redirect()->route('login.portal')
                ->withErrors(['email' => __('Session expired. Please try again with Google.')]);
        }

        // AUTO-LOGIN FALLBACK: If user already exists (from a previous partial attempt), skip the form
        $user = \App\Models\User::where('email', $googleData['email'])->first();
        if ($user) {
             Auth::login($user, true);
             session()->forget('google_user');
             
             if ($user->role === 'instructor' || ($user->tenant && $user->tenant->type === 'instructor')) {
                 return redirect()->route('instructor.dashboard', ['tenant' => $user->tenant->domain]);
             }
             return redirect()->intended('/dashboard');
        }

        // Fetch packages for the sidebar summary
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

        $selectedPlanSlug = $request->query('plan', session('selected_plan', $packages->firstWhere('is_default', true)?->slug ?? $packages->first()?->slug));
        $selectedCycle = $request->query('cycle', session('billing_cycle', 'monthly'));
        $accountType = $request->query('account_type', session('account_type', 'center'));

        return view('auth.complete-google-registration', compact('packages', 'packagesData', 'selectedPlanSlug', 'selectedCycle', 'accountType'));
    }

    /**
     * Handle the complete registration for Google-authenticated users.
     */
    public function completeRegistration(Request $request, TelegramService $telegram)
    {
        $googleData = session('google_user');
        
        \Log::info('Google Complete Registration Start', [
            'session_id' => session()->getId(),
            'has_google_user' => !empty($googleData),
            'google_email' => $googleData['email'] ?? 'N/A'
        ]);

        if (!$googleData) {
            return redirect()->route('login.portal')
                ->withErrors(['email' => __('Session expired. Please try again with Google.')]);
        }

        // DUPLICATE SUBMISSION GUARD: Prevent creating account twice on double-click
        $submissionKey = 'google_registration_lock_' . md5($googleData['email']);
        if (session()->has($submissionKey)) {
            \Log::warning('Duplicate Google registration submission blocked', ['email' => $googleData['email']]);
            // User already exists from the first submission, redirect them
            $existingUser = User::where('email', $googleData['email'])->first();
            if ($existingUser) {
                Auth::login($existingUser, true);
                session()->forget('google_user');
                session()->forget($submissionKey);
                if ($existingUser->role === 'instructor' || ($existingUser->tenant && $existingUser->tenant->type === 'instructor')) {
                    return redirect()->route('instructor.dashboard', ['tenant' => $existingUser->tenant->domain]);
                }
                return redirect()->intended('/dashboard');
            }
            return redirect()->route('login.portal')
                ->withErrors(['email' => __('Registration is being processed. Please wait.')]);
        }
        
        $request->validate([
            'account_type' => 'required|in:center,instructor',
            'center_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'plan' => 'required|exists:packages,slug',
            'billing_cycle' => 'required|in:monthly,term,yearly',
            'payment_gateway' => 'nullable|in:paypal,paymob,test',
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ]);

        // PHONE VERIFICATION GATE: Bypassed for Google registrations as requested
        // $phoneVerified = session('phone_verified') && session('phone_verified_number') === $request->phone;
        // if (!$phoneVerified) {
        //     return back()->withErrors(['phone' => __('messages.verify_phone_first')])->withInput();
        // }

        // 1. Check if user already exists (safety check for race conditions)
        if (User::where('email', $googleData['email'])->exists()) {
             $existing = User::where('email', $googleData['email'])->first();
             if (!$existing->google_id) {
                 $existing->update(['google_id' => $googleData['id']]);
             }
             Auth::login($existing, true);
             session()->forget('google_user');
             return redirect()->intended('/dashboard');
        }

        // Set submission lock BEFORE the transaction to prevent double-clicks
        session([$submissionKey => true]);
        session()->save();

        try {
            DB::beginTransaction();

            // Auto-generate subdomain
            $subdomain = $this->generateSubdomain($request->center_name);

            // 1. Create Tenant
            $tenant = Tenant::forceCreate([
                'name' => $request->center_name,
                'email' => $googleData['email'],
                'phone' => $request->phone,
                'domain' => $subdomain,
                'type' => $request->account_type,
                'database_name' => 'edu_central',
                'status' => 'active',
            ]);

            // Save locale and currency into tenant settings from session/GeoIP
            $registrationLocale = session('locale', 'ar');
            $registrationCurrency = session('suggested_currency', 'EGP');
            $tenant->settings = array_merge($tenant->settings ?? [], [
                'default_locale' => $registrationLocale,
                'currency' => $registrationCurrency,
            ]);
            $tenant->save();

            // 2. Create User (no password needed for Google users)
            $user = new User([
                'name' => $googleData['name'],
                'email' => $googleData['email'],
                'phone' => $request->phone,
                'password' => Hash::make(Str::random(32)), // Random password, user logs in via Google
                'google_id' => $googleData['id'],
                'email_verified_at' => now(), // Trust Google verification
                'locale' => session('locale', 'ar'),
            ]);
            $user->tenant_id = $tenant->id;
            $user->role = $request->account_type === 'instructor' ? 'instructor' : 'center_admin';
            $user->save();

            event(new Registered($user));

            // Set Spatie Team Context
            app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
            $user->assignRole($user->role);

            // 3. Handle Subscription logic
            $billingCycle = $request->input('billing_cycle', 'monthly');
            $package = \App\Models\Package::where('slug', $request->plan)->first();
            
            if ($billingCycle === 'term') {
                $basePrice = $package->term_price ?: ($package->price * 4);
            } elseif ($billingCycle === 'yearly') {
                $basePrice = $package->yearly_price ?: ($package->price * 10);
            } else {
                $basePrice = $package->price;
            }

            // Handle Coupon Discount
            $discountAmount = 0;
            $couponId = null;
            if ($request->filled('coupon_code')) {
                $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->first();
                if ($coupon && $coupon->isValid() && (!$coupon->package_id || $coupon->package_id === $package->id)) {
                    $discountAmount = $coupon->calculateDiscount($basePrice);
                    $couponId = $coupon->id;
                }
            }
            $finalAmount = max(0, $basePrice - $discountAmount);

            $isTrialPlan = $package->trial_days > 0;

            if ($isTrialPlan) {
                \App\Models\Subscription::forceCreate([
                    'tenant_id' => $tenant->id,
                    'package_id' => $package->id,
                    'name' => 'default',
                    'stripe_id' => 'sub_trial_' . Str::random(10),
                    'stripe_status' => 'trialing',
                    'stripe_price' => $package->slug,
                    'quantity' => 1,
                    'trial_ends_at' => now()->addDays($package->trial_days),
                    'ends_at' => now()->addDays($package->trial_days),
                    'status' => 'trialing',
                    'billing_cycle' => $billingCycle,
                    'base_price' => $basePrice,
                    'total_amount' => $finalAmount,
                    'discount_amount' => $discountAmount,
                ]);
            }

            // COMMIT the database transaction - everything critical is saved
            DB::commit();

            // === POST-COMMIT SIDE EFFECTS (non-critical, won't rollback DB) ===

            // Send WhatsApp OTP (non-critical - account is already created)
            try {
                $otpCode = $user->generatePhoneVerificationCode();
                $whatsapp = app(\App\Services\WhatsAppService::class);
                $message = __('messages.otp_sent_sms') . ": {$otpCode}";
                $whatsapp->sendSystemMessage($user->phone, $message);
            } catch (\Exception $otpEx) {
                \Log::warning('WhatsApp OTP failed (non-critical): ' . $otpEx->getMessage());
            }

            // Send Telegram Notification (non-critical)
            try {
                $telegram->sendRegistrationAlert($tenant, $user, $isTrialPlan ? '(Registration - Free Trial)' : '(Registration - Paid Plan)');
            } catch (\Exception $telegramEx) {
                \Log::warning('Telegram notification failed (non-critical): ' . $telegramEx->getMessage());
            }

            // Clear session data ONLY after successful DB commit
            session()->forget('google_user');
            session()->forget($submissionKey);
            session()->forget(['phone_verified', 'phone_verified_number', 'phone_verification_token', 'phone_verified_at']);

            if ($isTrialPlan) {
                // Login the user
                Auth::login($user, true);

                // Set session data for success page
                session([
                    'registration_success' => true,
                    'tenant_domain' => $subdomain,
                    'admin_email' => $googleData['email'],
                    'center_name' => $request->center_name,
                    'billing_cycle' => $billingCycle,
                    'base_price' => $basePrice,
                    'total_amount' => $finalAmount,
                    'registration_hmac' => hash_hmac('sha256', $tenant->id . '|' . $user->id, config('app.key')),
                ]);

                return redirect()->route('dashboard');
            } else {
                // Paid Plan Flow (Modular Payment Gateway)
                session([
                    'tenant_domain' => $subdomain,
                    'admin_email' => $googleData['email'],
                    'center_name' => $request->center_name,
                    'tenant_id' => $tenant->id,
                    'selected_plan' => $request->plan,
                    'billing_cycle' => $billingCycle,
                    'base_price' => $basePrice,
                    'total_amount' => $finalAmount,
                    'discount_amount' => $discountAmount,
                    'registration_hmac' => hash_hmac('sha256', $tenant->id . '|' . $user->id, config('app.key')),
                ]);

                // Determine Gateway
                $gatewayName = $request->input('payment_gateway', 'paymob');
                $gateway = \App\Services\PaymentFactory::make($gatewayName);
                
                $redirectUrl = $gateway->createCheckoutSession($tenant, $package, $billingCycle, [
                    'total_amount' => $finalAmount,
                    'base_price' => $basePrice,
                    'coupon_id' => $couponId,
                    'discount_amount' => $discountAmount,
                ]);

                return redirect()->away($redirectUrl);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            // Release submission lock on failure so user can try again
            session()->forget($submissionKey);
            \Log::error('Google Registration Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
                'has_google_user' => session()->has('google_user'),
                'input' => $request->all(),
            ]);
            // WE DO NOT FORGET google_user HERE, so the user can try again!
            session()->save(); 
            return back()->withErrors(['center_name' => __('Registration failed: ') . $e->getMessage()])->withInput();
        }
    }

    /**
     * Generate a unique subdomain from the center name.
     */
    private function generateSubdomain(string $name): string
    {
        $slug = Str::slug($name);
        if (empty($slug)) {
            $slug = 'center';
        }

        $original = $slug;
        $counter = 1;

        while (Tenant::where('domain', $slug)->exists()) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
