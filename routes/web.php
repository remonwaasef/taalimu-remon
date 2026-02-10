<?php

use Illuminate\Support\Facades\Route;

// Main domain routes (without tenant subdomain)
// Main domain routes
// Main domain routes
Route::middleware(['web', 'throttle:global'])->domain(env('TENANT_DOMAIN', 'localhost'))->group(function () {
    Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('home');

    Route::get('/register', [App\Http\Controllers\RegistrationController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\RegistrationController::class, 'register'])
        ->middleware('throttle:registration')
        ->name('register.submit');
    
    Route::get('/registration-success', function() {
        if (!session('registration_success')) {
            return redirect()->route('register');
        }
        return view('auth.registration-success');
    })->name('registration.success');

    Route::get('/register/setup', [App\Http\Controllers\CenterTypeController::class, 'show'])->name('register.setup');
    Route::post('/register/setup', [App\Http\Controllers\CenterTypeController::class, 'store'])->name('register.setup.store');

    // Payment Routes
    Route::get('/payment/success', [App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');
    
    // Demo Payment Routes (for testing without Stripe)
    Route::get('/payment/demo', [App\Http\Controllers\PaymentController::class, 'demo'])->name('payment.demo');
    Route::get('/payment/demo/success', [App\Http\Controllers\PaymentController::class, 'demoSuccess'])->name('payment.demo.success');

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
    Route::get('/login', [App\Http\Controllers\UnifiedAuthController::class, 'showLoginForm'])->name('login.portal');
    Route::post('/login', [App\Http\Controllers\UnifiedAuthController::class, 'login'])
        ->middleware('throttle:login') // Uses the 'login' rate limiter defined in AppServiceProvider
        ->name('unified.login.submit');
});

// Global Language Switcher (Accessible from any domain)
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'fr'])) {
        session(['locale' => $locale]);
        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }
    }
    return redirect()->back();
})->name('lang.switch');

// Debug routes removed for security - uncomment only in development if needed
// if (app()->environment('local')) { ... }

