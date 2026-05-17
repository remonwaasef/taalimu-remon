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

        $throttleKey = 'admin_login.' . \Illuminate\Support\Str::lower($request->input('email')) . '|' . $request->ip();

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

        if (Auth::attempt($credentials)) {
            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            // Check if user is admin (Super Admin)
            if (!in_array(auth()->user()->role, ['super_admin', 'admin'])) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'You do not have access to this area.',
                ]);
            }

            return redirect()->intended(route('admin.dashboard'));
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
