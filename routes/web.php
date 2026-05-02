<?php

use Illuminate\Support\Facades\Route;

// Main domain routes (without tenant subdomain)
Route::middleware(['web', 'throttle:global'])->domain(config('app.tenant_domain', 'localhost'))->group(function () {
    Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('home');

    Route::get('/register', [App\Http\Controllers\RegistrationController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\RegistrationController::class, 'register'])
        ->middleware('throttle:registration')
        ->name('register.submit');

    // Email Verification Routes (Disabled in favor of WhatsApp OTP)
    /*
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->middleware('auth')->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard'); // or wherever you want to redirect
    })->middleware(['auth', 'signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
    */
    
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

    
    Route::get('/registration-success', function() {
        if (!session('registration_success')) {
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

    Route::view('/offline', 'offline');

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
    Route::post('/api/cookie-consent', function(\Illuminate\Http\Request $request) {
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
    })->middleware('throttle:30,1');

    Route::get('/api/coupons/validate', [App\Http\Controllers\CouponApiController::class, 'validateCoupon'])
        ->middleware('throttle:coupons')
        ->name('api.coupons.validate');
        
    // Subdomain Validation API
    Route::get('/api/validate-subdomain', [App\Http\Controllers\SubdomainController::class, 'validateSubdomain'])
        ->middleware('throttle:60,1')
        ->name('api.subdomain.validate');
    Route::get('/login', [App\Http\Controllers\UnifiedAuthController::class, 'showLoginForm'])->name('login.portal');
    Route::post('/login', [App\Http\Controllers\UnifiedAuthController::class, 'login'])
        ->middleware('throttle:login') // Uses the 'login' rate limiter defined in AppServiceProvider
        ->name('unified.login.submit');
    
    Route::post('logout', [App\Http\Controllers\UnifiedAuthController::class, 'logout'])->name('logout');

    // Social Auth Routes
    Route::get('auth/google', [App\Http\Controllers\SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [App\Http\Controllers\SocialAuthController::class, 'handleGoogleCallback']);
    Route::get('auth/google/complete', [App\Http\Controllers\SocialAuthController::class, 'showCompleteRegistration'])->name('google.complete-registration');
    Route::post('auth/google/complete', [App\Http\Controllers\SocialAuthController::class, 'completeRegistration'])->name('google.complete-registration');
});

// Global Language Switcher (Accessible from any domain) — rate limited to prevent locale flooding
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en', 'fr'])) {
        session(['locale' => $locale]);
        
        // Refresh suggested currency based on new language
        $geoIP = app(\App\Services\GeoIPService::class);
        $countryCode = session('user_country_code') ?: $geoIP->getCountryCode(request()->ip());
        $currency = $geoIP->getCurrencyFromLocale($locale, $countryCode);
        session(['suggested_currency' => $currency]);

        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);

            // Auto-apply French education system when switching to French
            if ($locale === 'fr' && auth()->user()->tenant_id) {
                try {
                    $tenant = \App\Models\Tenant::find(auth()->user()->tenant_id);
                    if ($tenant) {
                        $hasStages = \App\Models\Stage::where('tenant_id', $tenant->id)->exists();
                        if (!$hasStages) {
                            $settingsService = app(\Modules\Center\Services\SettingsService::class);
                            $settingsService->applyTemplate($tenant, 'french_system');
                        }
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning(
                        'Auto-apply French education system failed: ' . $e->getMessage()
                    );
                }
            }
        }
    }
    return redirect()->back();
})->middleware('throttle:60,1')->name('lang.switch');

// Temporary route to fix storage permissions automatically
Route::get('/fix-storage', function () {
    try {
        $paths = [
            storage_path('framework/views'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('logs'),
            base_path('bootstrap/cache')
        ];
        
        $messages = [];
        foreach ($paths as $path) {
            if (!is_dir($path)) {
                mkdir($path, 0775, true);
                $messages[] = "Created directory: $path";
            } else {
                chmod($path, 0775);
                $messages[] = "Updated permissions: $path";
            }
        }
        
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        
        return "تم إصلاح مجلدات التخزين بنجاح والتنظيف! يمكنك الآن تحديث صفحة المركز (قم بالعودة للصفحة السابقة). <br><br>" . implode("<br>", $messages);
    } catch (\Exception $e) {
        return "حدث خطأ أثناء الإصلاح التلقائي: " . $e->getMessage();
    }
});
