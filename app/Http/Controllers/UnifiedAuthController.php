<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnifiedAuthController extends Controller
{
    protected $authService;

    public function __construct(\App\Services\UnifiedAuthService $authService)
    {
        $this->authService = $authService;
    }

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
        $throttleKey = 'unified_login.'.\Illuminate\Support\Str::lower($request->input('email')).'|'.$request->ip();

        // Increase rate limit for local development/testing to prevent locking out developers.
        // Environment-based only — request IPs must never relax throttling (spoofable via XFF).
        $maxAttempts = is_relaxed_throttle_env() ? 100 : 5;

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'email' => __('auth.throttle', ['seconds' => $seconds]),
            ])->onlyInput('email');
        }

        $user = $this->authService->authenticate($request->email, $request->password);

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

            if (! $tenant) {
                Auth::logout();

                return back()->withErrors([
                    'email' => __('auth.center_not_found'),
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
            \Illuminate\Support\Facades\Cache::put('login_token_'.$token, [
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'locale' => $currentLocale,
            ], now()->addSeconds(60));

            \Illuminate\Support\Facades\Log::info('Unified Login: Token generated', [
                'user_id' => $user->id,
                'tenant' => $tenant->domain,
            ]);

            // Redirect to tenant login via auto-submitting POST form
            $signature = hash_hmac('sha256', $token, config('app.key'));
            $loginUrl = tenant_url('login/sso', $tenant);

            $safeLoginUrl = e($loginUrl);
            $safeToken = e($token);
            $safeSignature = e($signature);
            $csrfToken = e(csrf_token());

            return response("
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Redirecting...</title>
                </head>
                <body>
                    <p style='text-align:center; margin-top:20vh; font-family:sans-serif;'>جاري تحويلك إلى لوحة التحكم...</p>
                    <form id='ssoForm' method='POST' action='{$safeLoginUrl}' style='display:none;'>
                        <input type='hidden' name='_token' value='{$csrfToken}'>
                        <input type='hidden' name='token' value='{$safeToken}'>
                        <input type='hidden' name='signature' value='{$safeSignature}'>
                        <noscript><button type='submit'>Click here to continue</button></noscript>
                    </form>
                    <script type='text/javascript'>
                        document.getElementById('ssoForm').submit();
                    </script>
                </body>
                </html>
            ");
        }

        \Illuminate\Support\Facades\RateLimiter::hit($throttleKey);

        return back()->withErrors([
            'email' => __('auth.invalid_credentials'),
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
