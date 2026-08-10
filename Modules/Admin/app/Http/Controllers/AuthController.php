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
            Auth::login($user);
            $request->session()->regenerate();

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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
