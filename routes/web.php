<?php

use Illuminate\Support\Facades\Route;

// PWA Offline Route
Route::get('/offline', function () {
    return view('offline');
});

// Main domain routes (without tenant subdomain)
Route::middleware(['web', 'throttle:global'])->domain(config('app.tenant_domain', 'localhost'))->group(function () {
    Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('home');

    Route::get('/register', [App\Http\Controllers\RegistrationController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\RegistrationController::class, 'register'])
        ->middleware('throttle:registration')
        ->name('register.submit');

    // Protected Dashboard Route
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user && $user->tenant_id) {
            $tenant = \App\Models\Tenant::find($user->tenant_id);
            if ($tenant) {
                // Redirect instructors to the instructor dashboard
                if ($user->role === 'instructor' || $tenant->type === 'instructor') {
                    return redirect()->away(tenant_url('instructor', $tenant));
                }

                return redirect()->away(tenant_url('dashboard', $tenant));
            }
        }

        return redirect()->route('login.portal');
    })->middleware(['auth'])->name('dashboard');

    Route::get('/registration-success', function () {
        if (! session('registration_success')) {
            return redirect()->route('register');
        }

        return view('auth.registration-success');
    })->name('registration.success');

    // Payment Routes
    Route::get('/payment/success', [App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');

    // PayPal specific routes
    Route::get('/payment/paypal/success', [App\Http\Controllers\PaymentController::class, 'paypalSuccess'])->name('payment.paypal.success');
    Route::post('/webhooks/paypal', [App\Http\Controllers\PayPalWebhookController::class, 'handle'])->name('webhooks.paypal');

    // Paymob specific routes
    Route::get('/payment/paymob/callback', [App\Http\Controllers\PaymentController::class, 'paymobCallback'])->name('payment.paymob.callback');
    Route::post('/webhooks/paymob', [App\Http\Controllers\PaymobWebhookController::class, 'handle'])->name('webhooks.paymob');

    // Demo Payment Routes (for testing without Stripe)
    Route::get('/payment/demo', [App\Http\Controllers\PaymentController::class, 'demo'])->name('payment.demo');
    Route::get('/payment/demo/success', [App\Http\Controllers\PaymentController::class, 'demoSuccess'])
        ->middleware('throttle:60,1')
        ->name('payment.demo.success');

    // PWA offline view is handled globally at the top of this file

    // Policy Pages
    Route::get('/privacy', [App\Http\Controllers\PolicyController::class, 'privacy'])->name('privacy');
    Route::get('/terms', [App\Http\Controllers\PolicyController::class, 'terms'])->name('terms');
    Route::get('/cookies', [App\Http\Controllers\PolicyController::class, 'cookies'])->name('cookies');
    Route::get('/gdpr', [App\Http\Controllers\PolicyController::class, 'gdpr'])->name('gdpr');

    // Cookie Consent Reporting (Admin only - requires authentication)
    Route::middleware(['auth', 'role:super_admin'])->group(function () {
        Route::get('/admin/consent-report', [App\Http\Controllers\ConsentReportController::class, 'index'])->name('consent.report');
        Route::get('/admin/consent-export', [App\Http\Controllers\ConsentReportController::class, 'export'])->name('consent.export');

        // Bug Reports Admin
        Route::get('/admin/bug-reports', [App\Http\Controllers\AdminBugReportController::class, 'index'])->name('admin.bug_reports.index');
        Route::put('/admin/bug-reports/{bugReport}/status', [App\Http\Controllers\AdminBugReportController::class, 'updateStatus'])->name('admin.bug_reports.status');
        Route::get('/admin/bug-reports/{bugReport}/screenshot', [App\Http\Controllers\AdminBugReportController::class, 'showScreenshot'])->name('admin.bug_reports.screenshot');
    });

    // API endpoint for saving cookie consent
    Route::post('/api/cookie-consent', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'analytics' => 'nullable|boolean',
            'marketing' => 'nullable|boolean',
        ]);

        \Illuminate\Support\Facades\DB::table('user_consents')->insert([
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
            'ip_address' => $request->ip(),
            'analytics_consent' => (bool) ($validated['analytics'] ?? false),
            'marketing_consent' => (bool) ($validated['marketing'] ?? false),
            'consent_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true]);
    })->middleware(['auth', 'throttle:30,1']);

    Route::get('/api/coupons/validate', [App\Http\Controllers\CouponApiController::class, 'validateCoupon'])
        ->middleware('throttle:coupons')
        ->name('api.coupons.validate');

    // Subdomain Validation API
    Route::get('/api/validate-subdomain', [App\Http\Controllers\SubdomainController::class, 'validateSubdomain'])
        ->middleware('throttle:60,1')
        ->name('api.subdomain.validate');

    // Phone OTP Verification (Pre-Registration)
    Route::post('/api/phone/send-otp', [App\Http\Controllers\PhoneVerificationController::class, 'sendOtp'])
        ->middleware('throttle:10,5')
        ->name('api.phone.send-otp');
    Route::post('/api/phone/verify-otp', [App\Http\Controllers\PhoneVerificationController::class, 'verifyOtp'])
        ->middleware('throttle:20,5')
        ->name('api.phone.verify-otp');

    Route::get('/login', [App\Http\Controllers\UnifiedAuthController::class, 'showLoginForm'])->name('login.portal');
    Route::post('/login', [App\Http\Controllers\UnifiedAuthController::class, 'login'])
        ->middleware('throttle:login') // Uses the 'login' rate limiter defined in AppServiceProvider
        ->name('unified.login.submit');

    Route::post('logout', [App\Http\Controllers\UnifiedAuthController::class, 'logout'])->name('logout');

    // Social Auth Routes
    Route::get('auth/google', [App\Http\Controllers\SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [App\Http\Controllers\SocialAuthController::class, 'handleGoogleCallback']);
    Route::get('auth/google/complete', [App\Http\Controllers\SocialAuthController::class, 'showCompleteRegistration'])->name('google.complete-registration');
    Route::post('auth/google/complete', [App\Http\Controllers\SocialAuthController::class, 'completeRegistration'])->name('google.complete-registration.post');
});

// Global Language Switcher (Accessible from any domain) — rate limited to prevent locale flooding
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en', 'fr'])) {
        session(['locale' => $locale]);

        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);

            // Auto-apply French education system when switching to French
            if ($locale === 'fr' && auth()->user()->tenant_id) {
                try {
                    $tenant = \App\Models\Tenant::find(auth()->user()->tenant_id);
                    if ($tenant) {
                        $hasStages = \App\Models\Stage::where('tenant_id', $tenant->id)->exists();
                        if (! $hasStages) {
                            $settingsService = app(\App\Services\SettingsService::class);
                            $settingsService->applyTemplate($tenant, 'french_system');
                        }
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning(
                        'Auto-apply French education system failed: '.$e->getMessage()
                    );
                }
            }
        }
    }

    return redirect()->back();
})->middleware('throttle:60,1')->name('lang.switch');
