<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

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
                session()->regenerate();

                // Redirect based on role or tenant type
                if ($user->role === 'instructor' || ($user->tenant && $user->tenant->type === 'instructor')) {
                    return redirect()->route('instructor.dashboard', ['tenant' => $user->tenant->domain]);
                }

                return redirect()->intended('/dashboard');
            }

            // 2. Check if user exists with same email
            $existingUser = User::where('email', $googleUser->email)->first();
            if ($existingUser) {
                return redirect()->route('login.portal')
                    ->withErrors(['email' => __('This email is already registered. Please login with your password to link your Google account.')]);
            }

            // 3. New user → Pass Google data via encrypted token (session-independent)
            $googleData = [
                'id' => htmlspecialchars(strip_tags($googleUser->id)),
                'name' => htmlspecialchars(strip_tags($googleUser->name)),
                'email' => htmlspecialchars(strip_tags($googleUser->email)),
            ];

            // Encrypt the Google user data into a URL-safe token
            $token = encrypt(json_encode($googleData));

            // Also store in session as a fallback
            session(['google_user' => $googleData]);

            // Build redirect URL with plan/cycle persisted from session
            $planParam = session('selected_plan', '');
            $cycleParam = session('billing_cycle', 'monthly');
            $accountTypeParam = session('account_type', 'center');
            $query = http_build_query(array_filter([
                'plan' => $planParam,
                'cycle' => $cycleParam,
                'account_type' => $accountTypeParam,
            ]));

            $redirectUrl = route('google.complete-registration').($query ? '?'.$query : '');

            \Log::info('Google Callback Success', [
                'session_id' => session()->getId(),
                'google_email' => $googleUser->email,
                'has_token' => ! empty($token),
            ]);

            session()->save();

            return redirect($redirectUrl);

        } catch (\Exception $e) {
            \Log::error('Google Login Error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
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

        if (! $googleData && $request->has('token')) {
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

        if (! $googleData) {
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
            session()->regenerate();
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
    public function completeRegistration(Request $request, TelegramService $telegram, \App\Services\TenantRegistrationService $registrationService)
    {
        $googleData = session('google_user');

        \Log::info('Google Complete Registration Start', [
            'session_id' => session()->getId(),
            'has_google_user' => ! empty($googleData),
            'google_email' => $googleData['email'] ?? 'N/A',
        ]);

        if (! $googleData) {
            return redirect()->route('login.portal')
                ->withErrors(['email' => __('Session expired. Please try again with Google.')]);
        }

        // DUPLICATE SUBMISSION GUARD: Prevent creating account twice on double-click
        $submissionKey = 'google_registration_lock_'.md5($googleData['email']);
        if (session()->has($submissionKey)) {
            \Log::warning('Duplicate Google registration submission blocked', ['email' => $googleData['email']]);
            // User already exists from the first submission, redirect them
            $existingUser = User::where('email', $googleData['email'])->first();
            if ($existingUser) {
                Auth::login($existingUser, true);
                session()->regenerate();
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

        $validated = $request->validate([
            'account_type' => 'required|in:center,instructor',
            'center_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'plan' => 'required|exists:packages,slug',
            'billing_cycle' => 'required|in:monthly,term,yearly',
            'payment_gateway' => 'nullable|in:paypal,paymob,test',
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ]);

        // 1. Check if user already exists (safety check for race conditions)
        if (User::where('email', $googleData['email'])->exists()) {
            $existing = User::where('email', $googleData['email'])->first();
            if (! $existing->google_id) {
                $existing->update(['google_id' => $googleData['id']]);
            }
            Auth::login($existing, true);
            session()->regenerate();
            session()->forget('google_user');

            return redirect()->intended('/dashboard');
        }

        // Set submission lock BEFORE the transaction to prevent double-clicks
        session([$submissionKey => true]);
        session()->save();

        try {
            // Call unified registration service
            $registrationData = $validated;
            $registrationData['email'] = $googleData['email'];
            $registrationData['name'] = $googleData['name'];
            $registrationData['currency'] = 'EGP'; // Default to EGP for registration settings

            $result = $registrationService->registerTenant(
                $registrationData,
                null, // No password needed for Google social signups
                $googleData['id'],
                null
            );

            $tenant = $result['tenant'];
            $user = $result['user'];
            $coupon = $tenant->temp_coupon;
            $discountAmount = $tenant->temp_discount_amount;
            $basePrice = $tenant->temp_base_price;
            $finalAmount = $tenant->temp_total_amount;
            $subdomain = $tenant->domain;
            $billingCycle = $request->input('billing_cycle', 'monthly');

            $package = \App\Models\Package::where('slug', $request->plan)->first();
            $isTrialPlan = $package && $package->trial_days > 0;

            // Send WhatsApp OTP (non-critical - account is already created)
            try {
                $otpCode = $user->generatePhoneVerificationCode();
                $message = __('messages.otp_sent_sms').": {$otpCode}";
                \App\Jobs\SendSystemWhatsAppMessage::dispatch($user->phone, $message);
            } catch (\Exception $otpEx) {
                \Log::warning('WhatsApp OTP dispatch failed (non-critical): '.$otpEx->getMessage());
            }

            // Clear session data ONLY after successful DB commit
            session()->forget('google_user');
            session()->forget($submissionKey);
            session()->forget(['phone_verified', 'phone_verified_number', 'phone_verification_token', 'phone_verified_at']);

            if ($isTrialPlan) {
                // Login the user
                Auth::login($user, true);
                session()->regenerate();

                // Set session data for success page
                session([
                    'registration_success' => true,
                    'tenant_domain' => $subdomain,
                    'admin_email' => $googleData['email'],
                    'center_name' => $request->center_name,
                    'billing_cycle' => $billingCycle,
                    'base_price' => $basePrice,
                    'total_amount' => $finalAmount,
                    'registration_hmac' => hash_hmac('sha256', $tenant->id.'|'.$user->id, config('app.key')),
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
                    'registration_hmac' => hash_hmac('sha256', $tenant->id.'|'.$user->id, config('app.key')),
                ]);

                // Determine Gateway
                $gatewayName = $request->input('payment_gateway', 'paymob');
                $gateway = \App\Services\PaymentFactory::make($gatewayName);

                $redirectUrl = $gateway->createCheckoutSession($tenant, $package, $billingCycle, [
                    'total_amount' => $finalAmount,
                    'base_price' => $basePrice,
                    'coupon_id' => $coupon ? $coupon->id : null,
                    'discount_amount' => $discountAmount,
                ]);

                return redirect()->away($redirectUrl);
            }

        } catch (\Exception $e) {
            // Release submission lock on failure so user can try again
            session()->forget($submissionKey);
            \Log::error('Google Registration Error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
                'has_google_user' => session()->has('google_user'),
                'input' => $request->except(['password', 'password_confirmation', 'card_pan', 'source', 'cvv']),
            ]);
            // WE DO NOT FORGET google_user HERE, so the user can try again!
            session()->save();

            return back()->withErrors(['center_name' => __('messages.registration_failed')])->withInput();
        }
    }
}
