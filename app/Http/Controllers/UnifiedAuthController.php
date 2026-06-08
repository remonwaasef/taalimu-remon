<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tenant;

class UnifiedAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.unified-login');
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Rate Limiting Key: IP + Email
        $throttleKey = 'unified_login.' . \Illuminate\Support\Str::lower($request->input('email')) . '|' . $request->ip();

        // Increase rate limit for local development/testing to prevent locking out developers
        $host = $request->getHost();
        $isLocal = app()->environment('local') || 
                   in_array($host, ['localhost', '127.0.0.1', '::1']) || 
                   str_contains($host, '.localhost') || 
                   str_contains($host, '192.168.');
        $maxAttempts = $isLocal ? 100 : 5;

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => __('auth.throttle', ['seconds' => $seconds]),
            ])->onlyInput('email');
        }

        $isEmail = filter_var($request->email, FILTER_VALIDATE_EMAIL);
        $user = null;

        if ($isEmail) {
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
            }
        } else {
            // Phone-based login logic
            $input = $request->email;
            $cleanPhone = preg_replace('/[^0-9]/', '', $input);
            
            // 1. Try variations of the phone number in the User table directly
            $phoneVariations = [
                $input,
                $cleanPhone,
                '0' . $cleanPhone,
                substr($cleanPhone, 1)
            ];

            foreach (array_unique($phoneVariations) as $phone) {
                if (empty($phone)) continue;
                
                $query = User::where('phone', $phone);
                if (app()->bound('tenant')) {
                    $query->where('tenant_id', app('tenant')->id);
                }
                $potentialUser = $query->first();
                if ($potentialUser && \Illuminate\Support\Facades\Hash::check($request->password, $potentialUser->password)) {
                    if (Auth::attempt(['email' => $potentialUser->email, 'password' => $request->password])) {
                        $user = Auth::user();
                        break;
                    }
                }
            }

            // 2. Fallback: Search in Student table if user not found via synced phone
            if (!$user) {
                $studentQuery = \App\Models\Student::where(function($q) use ($input, $cleanPhone) {
                    $q->where('phone', $input)
                      ->orWhere('phone', $cleanPhone)
                      ->orWhere('phone', '0' . $cleanPhone)
                      ->orWhere('phone', substr($cleanPhone, 1));
                });
                
                if (app()->bound('tenant')) {
                    $studentQuery->where('tenant_id', app('tenant')->id);
                }
                
                $student = $studentQuery->first();

                if ($student && $student->user && \Illuminate\Support\Facades\Hash::check($request->password, $student->user->password)) {
                    if (Auth::attempt(['email' => $student->user->email, 'password' => $request->password])) {
                        $user = Auth::user();
                    }
                }
            }
        }

        // If authenticated
        if ($user) {
            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);
            // Check if user is super admin (super_admin role and no tenant_id)
            if ($user->role === 'super_admin' && is_null($user->tenant_id)) {
                Auth::logout();
                return redirect()->route('admin.login')
                    ->with('info', __('messages.msg_001'));
            }
            
            // Get user's tenant
            $tenant = Tenant::find($user->tenant_id);
            
            if (!$tenant) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'لا يمكن العثور على المركز الخاص بك.',
                ]);
            }
            
            // Capture current locale
            $currentLocale = session('locale', config('app.locale'));
            
            // Logout from main domain
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Restore locale
            session(['locale' => $currentLocale]);
            
            // Generate distinct token for cross-domain login
            $token = \Illuminate\Support\Str::random(64);
            
            // Store token in cache (valid for 60 seconds)
            \Illuminate\Support\Facades\Cache::put('login_token_' . $token, [
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'locale' => $currentLocale,
            ], now()->addSeconds(60));
            
            \Illuminate\Support\Facades\Log::info('Unified Login: Token generated', [
                'user_id' => $user->id, 
                'tenant' => $tenant->domain
            ]);
            
            // Redirect to tenant login via auto-submitting POST form
            $loginUrl = tenant_url('login/sso', $tenant);
            
            return response()->setContent("
                <html>
                <body onload='document.forms[0].submit()'>
                    <p style='text-align:center; margin-top:20vh; font-family:sans-serif;'>جاري تحويلك إلى لوحة التحكم...</p>
                    <form method='POST' action='{$loginUrl}' style='display:none;'>
                        <input type='hidden' name='token' value='{$token}'>
                        <noscript><button type='submit'>Click here to continue</button></noscript>
                    </form>
                </body>
                </html>
            ");
        }

        \Illuminate\Support\Facades\RateLimiter::hit($throttleKey);

        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
