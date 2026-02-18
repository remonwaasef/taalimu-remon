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

        $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $credentials = [
            $loginField => $request->email,
            'password' => $request->password,
        ];

        // Try to authenticate
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user is super admin (admin role and no tenant_id)
            if ($user->role === 'admin' && is_null($user->tenant_id)) {
                Auth::logout();
                return redirect()->route('admin.login')
                    ->with('info', 'يرجى استخدام صفحة تسجيل دخول المشرف.');
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
            \Illuminate\Support\Facades\Cache::put('login_token_' . $token, [
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'locale' => $currentLocale,
                'ua' => hash('sha256', (string) $request->userAgent()),
                'ip' => hash('sha256', $request->ip()),
            ], now()->addSeconds(30));
            
            
            // Redirect to tenant login with token
            $loginUrl = tenant_url('login?token=' . $token, $tenant);
            
            return redirect($loginUrl);
        }

        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ])->onlyInput('email');
    }
}
