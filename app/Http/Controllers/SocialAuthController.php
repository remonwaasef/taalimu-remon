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
    public function redirectToGoogle()
    {
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
                return redirect()->intended('/dashboard');
            }

            // 3. New user → Store Google data in session & redirect to complete registration
            session([
                'google_user' => [
                    'id' => $googleUser->id,
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                ]
            ]);

            return redirect()->route('google.complete-registration');

        } catch (\Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login.portal')
                ->withErrors(['email' => __('Unable to login with Google. Please try again.')]);
        }
    }

    /**
     * Show the complete registration form for Google-authenticated users.
     */
    public function showCompleteRegistration()
    {
        // Ensure Google user data exists in session
        if (!session('google_user')) {
            return redirect()->route('login.portal')
                ->withErrors(['email' => __('Session expired. Please try again with Google.')]);
        }

        return view('auth.complete-google-registration');
    }

    /**
     * Handle the complete registration for Google-authenticated users.
     */
    public function completeRegistration(Request $request, TelegramService $telegram)
    {
        $googleData = session('google_user');
        
        if (!$googleData) {
            return redirect()->route('login.portal')
                ->withErrors(['email' => __('Session expired. Please try again with Google.')]);
        }

        $request->validate([
            'center_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
        ]);

        // Check email uniqueness (safety check)
        if (User::where('email', $googleData['email'])->exists()) {
            session()->forget('google_user');
            return redirect()->route('login.portal')
                ->withErrors(['email' => __('This email is already registered. Please login instead.')]);
        }

        try {
            DB::beginTransaction();

            // Auto-generate subdomain
            $subdomain = $this->generateSubdomain($request->center_name);

            // Get free-trial package
            $package = \App\Models\Package::where('slug', 'free-trial')->first();
            $basePrice = $package ? $package->price : 0;

            // 1. Create Tenant
            $tenant = Tenant::create([
                'name' => $request->center_name,
                'email' => $googleData['email'],
                'phone' => $request->phone,
                'domain' => $subdomain,
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
            $user->role = 'center_admin';
            $user->save();

            event(new Registered($user));

            // Set Spatie Team Context
            app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
            $user->assignRole('center_admin');

            // 3. Create Free Trial Subscription
            \App\Models\Subscription::create([
                'tenant_id' => $tenant->id,
                'name' => 'default',
                'stripe_id' => 'sub_google_' . Str::random(10),
                'stripe_status' => 'active',
                'stripe_price' => 'price_free',
                'quantity' => 1,
                'ends_at' => now()->addDays(14),
                'status' => 'active',
                'billing_cycle' => 'monthly',
                'base_price' => $basePrice,
                'total_amount' => $basePrice,
                'discount_amount' => 0,
            ]);

            // Send Telegram Notification
            $telegram->sendRegistrationAlert($tenant, $user, '(Google Login)');

            DB::commit();

            // Clear Google session data
            session()->forget('google_user');

            // Login the user
            Auth::login($user, true);

            // Set session data for success page
            session([
                'registration_success' => true,
                'tenant_domain' => $subdomain,
                'admin_email' => $googleData['email'],
                'center_name' => $request->center_name,
                'billing_cycle' => 'monthly',
                'base_price' => $basePrice,
                'total_amount' => $basePrice,
                'registration_hmac' => hash_hmac('sha256', $tenant->id . '|' . $user->id, config('app.key')),
            ]);

            return redirect()->route('registration.success');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Google Registration Error: ' . $e->getMessage());
            return back()->withErrors(['center_name' => __('Registration failed. Please try again.')])->withInput();
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
