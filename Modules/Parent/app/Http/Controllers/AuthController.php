<?php

namespace Modules\Parent\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('parent::auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $throttleKey = 'parent_login.'.\Illuminate\Support\Str::lower($request->input('email')).'|'.$request->ip();

        // Environment-based only — request IPs must never relax throttling (spoofable via XFF).
        $maxAttempts = is_relaxed_throttle_env() ? 100 : 5;

        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'email' => __('auth.throttle', ['seconds' => $seconds]),
            ])->onlyInput('email');
        }

        // Parents are tenant-scoped users; find within the bound tenant only.
        $user = \App\Models\User::where('tenant_id', app('tenant')->id)
            ->where('email', $request->input('email'))
            ->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            // Must actually be a parent (linked guardian account)
            if (! $user->guardian) {
                RateLimiter::hit($throttleKey);

                return back()->withErrors([
                    'email' => __('auth.failed'),
                ])->onlyInput('email');
            }

            RateLimiter::clear($throttleKey);
            Auth::login($user);
            $request->session()->regenerate();
            session(['tenant_id' => $user->tenant_id]);

            return redirect()->route('parent.index', ['tenant' => app('tenant')->domain]);
        }

        RateLimiter::hit($throttleKey);

        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->onlyInput('email');
    }
}
