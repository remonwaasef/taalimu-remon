<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin::auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $throttleKey = 'admin_login.'.\Illuminate\Support\Str::lower($request->input('email')).'|'.$request->ip();

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

        // Global admins (tenant_id = null) are hidden by TenantScope whenever a
        // tenant is bound to the request (e.g. u.taalimu.com). Look the admin up
        // without the tenant scope so super-admins can sign in from any host.
        $user = \App\Models\User::withoutGlobalScope(\App\Scopes\TenantScope::class)
            ->where('email', $credentials['email'])
            ->first();

        if ($user
            && $user->tenant_id === null
            && in_array($user->role, ['super_admin', 'admin'])
            && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);

            // 2FA challenge: password is valid but we must verify the TOTP
            // before the session is authenticated.
            if ($user->google2fa_enabled) {
                $request->session()->put('admin_2fa_pending', $user->id);
                $request->session()->regenerate();

                return redirect()->route('admin.login.2fa');
            }

            Auth::login($user);
            $request->session()->regenerate();
            session(['tenant_id' => $user->tenant_id]);

            $intended = redirect()->getIntendedUrl();
            if ($intended && str_contains($intended, '/admin')) {
                return redirect()->intended(route('admin.dashboard'));
            }

            $request->session()->forget('url.intended');

            return redirect()->route('admin.dashboard');
        }

        \Illuminate\Support\Facades\RateLimiter::hit($throttleKey);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showTwoFactorForm(Request $request)
    {
        $pendingId = $request->session()->get('admin_2fa_pending');

        if (! $pendingId) {
            return redirect()->route('admin.login');
        }

        $user = \App\Models\User::withoutGlobalScope(\App\Scopes\TenantScope::class)->find($pendingId);

        if (! $user || ! $user->google2fa_enabled) {
            $request->session()->forget('admin_2fa_pending');

            return redirect()->route('admin.login');
        }

        return view('admin::auth.2fa', ['email' => $user->email]);
    }

    public function verifyTwoFactor(Request $request)
    {
        $pendingId = $request->session()->get('admin_2fa_pending');

        if (! $pendingId) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'one_time_password' => ['required', 'digits:6'],
        ]);

        $user = \App\Models\User::withoutGlobalScope(\App\Scopes\TenantScope::class)->find($pendingId);

        if (! $user || ! $user->google2fa_enabled) {
            $request->session()->forget('admin_2fa_pending');

            return redirect()->route('admin.login');
        }

        if (! \PragmaRX\Google2FALaravel\Facade::verifyKey($user->google2fa_secret, $request->one_time_password)) {
            return back()->withErrors(['one_time_password' => __('Invalid OTP code.')])->onlyInput('one_time_password');
        }

        $request->session()->forget('admin_2fa_pending');
        Auth::login($user);
        $request->session()->regenerate();
        session(['tenant_id' => $user->tenant_id]);
        session(['2fa_verified' => true]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
