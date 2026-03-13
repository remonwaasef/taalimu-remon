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
            session(['selected_cycle' => $request->cycle]);
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
            $googleUser = Socialite::driver('google')->stateless()->user();
            
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
            $cycleParam = session('selected_cycle', 'monthly');
            $accountTypeParam = session('account_type', 'center');
            $query = http_build_query(array_filter([
                'plan'  => $planParam,
                'cycle' => $cycleParam,
                'account_type' => $accountTypeParam,
                'token' => $token,
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

        // Fetch packages for the sidebar summary
        $packages = \App\Models\Package::with('features')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $currency = \App\Models\SiteSetting::get('currency_symbol', 'جنيه');
        
        $packagesData = $packages->map(function($p) use ($currency) {
            $discountPercent = 0;
            $savingsAmount = 0;
            if ($p->old_price > 0 && $p->old_price > $p->price) {
                $discountPercent = round((($p->old_price - $p->price) / $p->old_price) * 100);
                $savingsAmount = $p->old_price - $p->price;
            }

            return [
                'slug' => $p->slug,
                'name' => app()->getLocale() == 'ar' ? $p->name : ($p->name_en ?: $p->name),
                'price' => number_format($p->price, 0) . ' ' . $currency,
                'price_value' => number_format($p->price, 0),
                'price_raw' => (float)$p->price,
                'old_price' => $p->old_price > 0 ? number_format($p->old_price, 0) . ' ' . $currency : null,
                'old_price_value' => $p->old_price > 0 ? number_format($p->old_price, 0) : null,
                'old_price_raw' => (float)$p->old_price,
                'currency' => $currency,
                'discount_percent' => $discountPercent > 0 ? $discountPercent : null,
                'savings_amount' => $savingsAmount > 0 ? number_format($savingsAmount, 0) : null,
                'discount_label' => $p->discount_label,
                'yearly_price' => $p->yearly_price ? number_format($p->yearly_price, 0) . ' ' . $currency : number_format($p->price * 10, 0) . ' ' . $currency,
                'yearly_price_value' => $p->yearly_price ? number_format($p->yearly_price, 0) : number_format($p->price * 10, 0),
                'yearly_price_raw' => $p->yearly_price ?: ($p->price * 10),
                'term_price' => $p->term_price ? number_format($p->term_price, 0) . ' ' . $currency : number_format($p->price * 4, 0) . ' ' . $currency,
                'term_price_value' => $p->term_price ? number_format($p->term_price, 0) : number_format($p->price * 4, 0),
                'term_price_raw' => $p->term_price ?: ($p->price * 4),
                'regional_prices' => $p->regional_prices ?? [],
            ];
        })->values();

        $selectedPlanSlug = $request->query('plan', session('selected_plan', $packages->firstWhere('is_default', true)?->slug ?? $packages->first()?->slug));
        $selectedCycle = $request->query('cycle', session('selected_cycle', 'monthly'));
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
        
        $request->validate([
            'account_type' => 'required|in:center,instructor',
            'center_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'plan' => 'required|exists:packages,slug',
            'billing_cycle' => 'required|in:monthly,term,yearly',
        ]);

        // 1. Check if user already exists (safety check)
        if (User::where('email', $googleData['email'])->exists()) {
             $existing = User::where('email', $googleData['email'])->first();
             // Link if missing
             if (!$existing->google_id) {
                 $existing->update(['google_id' => $googleData['id']]);
             }
             Auth::login($existing, true);
             session()->forget('google_user');
             return redirect()->intended('/dashboard');
        }

        try {
            DB::beginTransaction();

            // Auto-generate subdomain
            $subdomain = $this->generateSubdomain($request->center_name);

            // 1. Create Tenant
            $tenant = Tenant::create([
                'name' => $request->center_name,
                'email' => $googleData['email'],
                'phone' => $request->phone,
                'domain' => $subdomain,
                'type' => $request->account_type,
                'database_name' => 'edu_central',
                'status' => 'active',
            ]);

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

            if ($request->plan === 'free-trial') {
                \App\Models\Subscription::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'default',
                    'stripe_id' => 'sub_google_' . Str::random(10),
                    'stripe_status' => 'active',
                    'stripe_price' => 'price_free',
                    'quantity' => 1,
                    'ends_at' => now()->addDays(14),
                    'status' => 'active',
                    'billing_cycle' => $billingCycle,
                    'base_price' => $basePrice,
                    'total_amount' => $basePrice,
                    'discount_amount' => 0,
                ]);

                // Send Telegram Notification
                $telegram->sendRegistrationAlert($tenant, $user, '(Google Login - Free Trial)');

                DB::commit();

                // Clear Google session data ONLY ON SUCCESS
                session()->forget('google_user');

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
                    'total_amount' => $basePrice,
                    'registration_hmac' => hash_hmac('sha256', $tenant->id . '|' . $user->id, config('app.key')),
                ]);

                return redirect()->route('registration.success');
            } else {
                // Paid Plan Flow (Modular Payment Gateway)
                DB::commit();

                // Clear Google session data ONLY ON SUCCESS
                session()->forget('google_user');
                
                // Login the user
                Auth::login($user, true);

                session([
                    'tenant_domain' => $subdomain,
                    'admin_email' => $googleData['email'],
                    'center_name' => $request->center_name,
                    'tenant_id' => $tenant->id,
                    'selected_plan' => $request->plan,
                    'billing_cycle' => $billingCycle,
                    'base_price' => $basePrice,
                    'total_amount' => $basePrice,
                    'registration_hmac' => hash_hmac('sha256', $tenant->id . '|' . $user->id, config('app.key')),
                ]);

                // Determine Gateway (default to stripe or catch from request if added to form)
                $gatewayName = $request->input('payment_gateway', 'paymob');
                $gateway = \App\Services\PaymentFactory::make($gatewayName);
                
                $redirectUrl = $gateway->createCheckoutSession($tenant, $package, $billingCycle, [
                    'total_amount' => $basePrice,
                ]);

                return redirect()->away($redirectUrl);
            }

        } catch (\Exception $e) {
            DB::rollBack();
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
