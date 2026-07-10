<?php

namespace Modules\Center\Http\Controllers;

use App\Helpers\PhoneHelper;
use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

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
            $data = Cache::pull('login_token_'.$token);

            if ($data) {
                $tenantMatches = app()->bound('tenant') && isset($data['tenant_id']) && (int) $data['tenant_id'] === (int) app('tenant')->id;

                if ($tenantMatches && isset($data['user_id'])) {
                    Auth::loginUsingId($data['user_id']);
                    $request->session()->regenerate();

                    if (isset($data['locale'])) {
                        session(['locale' => $data['locale']]);
                    }

                    $user = auth()->user();

                    if ($user && $user->tenant_id === app('tenant')->id) {
                        Log::info('Unified Login: Success', ['user_id' => $user->id]);

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
                    Log::warning('Unified Login: Tenant Mismatch or Invalid Data', [
                        'token_tenant' => $data['tenant_id'] ?? 'null',
                        'current_tenant' => app('tenant')->id ?? 'null',
                    ]);
                }
            } else {
                Log::warning('Unified Login: Token Expired or Invalid', ['token' => substr($token, 0, 10).'...']);
            }
        }

        return redirect()->route('center.login', ['tenant' => app('tenant')->domain])->withErrors(['email' => 'الرابط منتهي الصلاحية أو غير صالح.']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $throttleKey = 'login.' . Str::lower($request->input('email')) . '|' . $request->ip();
        $maxAttempts = is_relaxed_throttle_env() ? 100 : 5;

        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'email' => __('auth.throttle', ['seconds' => $seconds]),
            ])->onlyInput('email');
        }

        $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $user = null;
        $tenantId = app('tenant')->id;

        if ($loginField === 'email') {
            $user = User::where('email', $request->email)
                ->where('tenant_id', $tenantId)
                ->first();
        } else {
            $phoneVariations = PhoneHelper::getVariations($request->email);

            if (empty($phoneVariations)) {
                return back()->withErrors(['email' => __('auth.failed')])->onlyInput('email');
            }

            // 1. Try Students
            $student = Student::where('tenant_id', $tenantId)
                ->whereIn('phone', $phoneVariations)
                ->first();

            if ($student && $student->user_id) {
                $user = User::find($student->user_id);
            }

            // 2. Try Instructors
            if (! $user) {
                $instructor = Instructor::where('tenant_id', $tenantId)
                    ->whereIn('phone', $phoneVariations)
                    ->first();

                if ($instructor && $instructor->user_id) {
                    $user = User::find($instructor->user_id);
                }
            }

            // 3. Try User table directly
            if (! $user) {
                $user = User::where('tenant_id', $tenantId)
                    ->whereIn('phone', $phoneVariations)
                    ->first();
            }

            // 4. Try tenant phone match
            if (! $user) {
                $tenant = app('tenant');
                if ($tenant && $tenant->phone) {
                    $tenantVariations = PhoneHelper::getVariations($tenant->phone);
                    $inputVariations = $phoneVariations;

                    if (array_intersect($tenantVariations, $inputVariations)) {
                        $user = User::where('tenant_id', $tenantId)
                            ->where('role', 'center_admin')
                            ->orderBy('id', 'asc')
                            ->first();
                    }
                }
            }
        }

        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->tenant_id !== $tenantId) {
                RateLimiter::hit($throttleKey);

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
            // Uses the platform-wide policy defined in AppServiceProvider
            // (min 8 + mixed case + numbers + symbols + uncompromised in production).
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
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
