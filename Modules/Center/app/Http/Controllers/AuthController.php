<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        // Token logic moved to ssoLogin (POST) for security

        return view('center::auth.login');
    }

    public function ssoLogin(Request $request)
    {
        if ($request->has('token')) {
            $token = $request->input('token');
            $data = \Illuminate\Support\Facades\Cache::pull('login_token_'.$token);

            if ($data) {
                // Check if the token belongs to this tenant
                $tenantMatches = app()->bound('tenant') && isset($data['tenant_id']) && (int) $data['tenant_id'] === (int) app('tenant')->id;

                if ($tenantMatches && isset($data['user_id'])) {
                    Auth::loginUsingId($data['user_id']);
                    $request->session()->regenerate();

                    if (isset($data['locale'])) {
                        session(['locale' => $data['locale']]);
                    }

                    $user = auth()->user();

                    if ($user && $user->tenant_id === app('tenant')->id) {
                        \Illuminate\Support\Facades\Log::info('Unified Login: Success', ['user_id' => $user->id]);

                        if ($user->role === 'center_admin') {
                            return redirect()->route('center.dashboard', ['tenant' => app('tenant')->domain]);
                        } elseif ($user->role === 'student') {
                            return redirect()->route('campus.index', ['tenant' => app('tenant')->domain]);
                        }
                    }

                    Auth::logout();

                    return redirect()->route('center.login', ['tenant' => app('tenant')->domain])
                        ->withErrors(['email' => __('auth.failed')]);
                } else {
                    \Illuminate\Support\Facades\Log::warning('Unified Login: Tenant Mismatch or Invalid Data', [
                        'token_tenant' => $data['tenant_id'] ?? 'null',
                        'current_tenant' => app('tenant')->id ?? 'null',
                    ]);
                }
            } else {
                \Illuminate\Support\Facades\Log::warning('Unified Login: Token Expired or Invalid', ['token' => substr($token, 0, 10).'...']);
            }
        }

        return redirect()->route('center.login', ['tenant' => app('tenant')->domain])->withErrors(['email' => 'الرابط منتهي الصلاحية أو غير صالح.']);
    }

    public function login(Request $request)
    {
        // Flexible validation: accept email or phone
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Rate Limiting Key: IP + Email
        $throttleKey = 'login.'.\Illuminate\Support\Str::lower($request->input('email')).'|'.$request->ip();

        // Increase rate limit for local development/testing to prevent locking out developers
        $ip = $request->ip();
        $isLocal = app()->environment('local') ||
                   in_array($ip, ['127.0.0.1', '::1']) ||
                   str_starts_with($ip, '192.168.');
        $maxAttempts = $isLocal ? 100 : 5;

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'email' => __('auth.throttle', ['seconds' => $seconds]),
            ])->onlyInput('email');
        }

        // Determine if input is email or phone
        $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // Find user by email or phone
        $user = null;

        if ($loginField === 'email') {
            $user = \App\Models\User::where('email', $request->email)
                ->where('tenant_id', app('tenant')->id)
                ->first();
        } else {
            // Find student or instructor by phone
            $phone = preg_replace('/[^0-9]/', '', $request->email);

            // 1. Try Students
            $student = \App\Models\Student::where('tenant_id', app('tenant')->id)
                ->where(function ($q) use ($request, $phone) {
                    $q->where('phone', $request->email) // Exact as typed
                        ->orWhere('phone', $phone)         // Cleaned
                        ->orWhere('phone', '0'.$phone)   // Common local variations
                        ->orWhere('phone', substr($phone, 1));
                })->first();

            if ($student && $student->user_id) {
                $user = \App\Models\User::find($student->user_id);
            } else {
                // 2. Try Instructors
                $instructor = \App\Models\Instructor::where('tenant_id', app('tenant')->id)
                    ->where(function ($q) use ($request, $phone) {
                        $q->where('phone', $request->email)
                            ->orWhere('phone', $phone)
                            ->orWhere('phone', '0'.$phone)
                            ->orWhere('phone', substr($phone, 1));
                    })->first();

                if ($instructor && $instructor->user_id) {
                    $user = \App\Models\User::find($instructor->user_id);
                } else {
                    // 3. Try User table directly (for center_admin or others without separate profiles)
                    $user = \App\Models\User::where('tenant_id', app('tenant')->id)
                        ->where(function ($q) use ($request, $phone) {
                            $q->where('phone', $request->email)
                                ->orWhere('phone', $phone)
                                ->orWhere('phone', '0'.$phone)
                                ->orWhere('phone', substr($phone, 1));
                        })->first();

                    if (! $user) {
                        // 4. Try Center Admin (using tenant phone)
                        $tenant = app('tenant');
                        if ($tenant && $tenant->phone) {
                            $tenantPhone = preg_replace('/[^0-9]/', '', $tenant->phone);
                            $inputPhone = preg_replace('/[^0-9]/', '', $request->email);

                            if ($tenantPhone === $inputPhone ||
                                '0'.$tenantPhone === $inputPhone ||
                                $tenantPhone === '0'.$inputPhone ||
                                substr($tenantPhone, 1) === $inputPhone ||
                                $tenantPhone === substr($inputPhone, 1)) {

                                // Find the primary center admin
                                $user = \App\Models\User::where('tenant_id', $tenant->id)
                                    ->where('role', 'center_admin')
                                    ->orderBy('id', 'asc')
                                    ->first();
                            }
                        }
                    }
                }
            }
        }

        // Attempt authentication
        if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            if (app()->bound('tenant') && $user->tenant_id !== app('tenant')->id) {
                \Illuminate\Support\Facades\RateLimiter::hit($throttleKey);

                return back()->withErrors([
                    'email' => __('auth.failed'),
                ])->onlyInput('email');
            }

            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);
            Auth::login($user);
            $request->session()->regenerate();

            // Redirect based on role
            if ($user->role === 'student') {
                return redirect()->route('campus.index', ['tenant' => app('tenant')->domain]);
            }

            // All other roles (center_admin, instructor, staff, secretary, accountant, etc.)
            // go to the main center dashboard.
            return redirect()->route('center.dashboard', ['tenant' => app('tenant')->domain]);
        }

        \Illuminate\Support\Facades\RateLimiter::hit($throttleKey);

        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('center.login', ['tenant' => app('tenant')->domain]);
    }

    public function magicLogin(Request $request, \App\Models\Student $student)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'رابط الدخول غير صالح أو انتهت صلاحيته.');
        }

        if ($student->tenant_id !== app('tenant')->id) {
            abort(403, 'Invalid tenant.');
        }

        if ($request->isMethod('get')) {
            return view('center::auth.magic_login', compact('student'));
        }

        if ($student->user) {
            Auth::login($student->user);
            $request->session()->regenerate();

            return redirect()->route('campus.index', ['tenant' => app('tenant')->domain])
                ->with('success', __('Welcome back, :name!', ['name' => $student->name]));
        }

        return redirect()->route('center.login')
            ->withErrors(['email' => __('Login failed. Student has no user account.')]);
    }

    public function showChangePasswordForm()
    {
        return view('center::auth.passwords.change', ['tenant' => app('tenant')]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        // Redirect based on role
        if ($user->role === 'student') {
            return redirect()->route('campus.index', ['tenant' => app('tenant')->domain])
                ->with('success', __('Password updated successfully.'));
        }

        return redirect()->route('center.dashboard', ['tenant' => app('tenant')->domain])
            ->with('success', __('Password updated successfully.'));
    }
}
