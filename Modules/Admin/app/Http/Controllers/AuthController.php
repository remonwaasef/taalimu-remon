<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected function guard()
    {
        return Auth::guard('admin');
    }

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

        // SEC-AUTH-2: rate-limit relaxation must never depend on the request's
        // Host header (attacker-controlled) — environment only.
        $isLocal = app()->environment('local', 'testing');
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

            // SEC-AUTH-3 (relaxed): 2FA is optional for global admin accounts.
            // Accounts with an enrolled secret are challenged on the
            // verification page; accounts without one (or flagged with
            // google2fa_bypass) go straight to the dashboard. Enrollment on
            // the 2FA page remains available for in-flight sessions and
            // future re-enabling.
            if ($user->google2fa_enabled && ! (bool) $user->google2fa_bypass) {
                $request->session()->put('admin_2fa_pending', $user->id);
                $request->session()->regenerate();

                return redirect()->route('admin.login.2fa');
            }

            $this->guard()->login($user);
            $request->session()->regenerate();
            session(['tenant_id' => $user->tenant_id]);

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

        if (! $user || ! in_array($user->role, ['super_admin', 'admin'], true) || $user->tenant_id !== null) {
            $request->session()->forget('admin_2fa_pending');

            return redirect()->route('admin.login');
        }

        if ($user->google2fa_enabled) {
            return view('admin::auth.2fa', [
                'email' => $user->email,
                'setup' => false,
                'secret' => null,
                'qr' => null,
            ]);
        }

        // SEC-AUTH-3 enrollment: mint a secret, keep it in the session until a
        // valid OTP confirms it (same pattern as the tenant TwoFactorController).
        $secret = $request->session()->get('admin_2fa_pending_secret');

        if (! $secret) {
            $secret = \PragmaRX\Google2FALaravel\Facade::generateSecretKey();
            $request->session()->put('admin_2fa_pending_secret', $secret);
        }

        $qr = \PragmaRX\Google2FALaravel\Facade::getQRCodeInline(
            'Taalimu Admin',
            $user->email,
            $secret
        );

        return view('admin::auth.2fa', [
            'email' => $user->email,
            'setup' => true,
            'secret' => $secret,
            'qr' => $qr,
        ]);
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

        if (! $user || ! in_array($user->role, ['super_admin', 'admin'], true) || $user->tenant_id !== null) {
            $request->session()->forget('admin_2fa_pending');

            return redirect()->route('admin.login');
        }

        // Enrollment or challenge — the active secret is either the stored one
        // (challenge) or the session-held one (first-time setup).
        $secret = $user->google2fa_enabled
            ? $user->google2fa_secret
            : $request->session()->get('admin_2fa_pending_secret');

        if (! $secret) {
            $request->session()->forget('admin_2fa_pending');

            return redirect()->route('admin.login');
        }

        if (! \PragmaRX\Google2FALaravel\Facade::verifyKey($secret, $request->one_time_password)) {
            return back()->withErrors(['one_time_password' => __('Invalid OTP code.')])->onlyInput('one_time_password');
        }

        // First valid OTP confirms the enrollment and persists the secret.
        if (! $user->google2fa_enabled) {
            $user->google2fa_secret = $secret;
            $user->google2fa_enabled = true;
            $user->save();
        }

        $request->session()->forget(['admin_2fa_pending', 'admin_2fa_pending_secret']);
        $this->guard()->login($user);
        $request->session()->regenerate();
        session(['tenant_id' => $user->tenant_id]);
        session(['2fa_verified' => true]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
