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
            
            // Store token in cache (valid for 60 seconds)
            // We removed IP and UA checks to prevent issues with mobile networks and proxies regarding IP changes
            \Illuminate\Support\Facades\Cache::put('login_token_' . $token, [
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'locale' => $currentLocale,
            ], now()->addSeconds(60));
            
            \Illuminate\Support\Facades\Log::info('Unified Login: Token generated', [
                'user_id' => $user->id, 
                'tenant' => $tenant->domain
            ]);
            
            // Redirect to tenant login with token
            $loginUrl = tenant_url('login?token=' . $token, $tenant);
            
            \Illuminate\Support\Facades\Log::info('Unified Login: Redirecting', [
                'user_id' => $user->id,
                'target_url' => $loginUrl,
                'is_secure' => request()->isSecure(),
                'app_url' => config('app.url'),
                'tenant_domain' => config('app.tenant_domain'),
            ]);

            return redirect($loginUrl);
        }

        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ])->onlyInput('email');
    }
}
