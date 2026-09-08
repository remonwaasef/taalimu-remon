<?php

use Illuminate\Support\Facades\Route;

// PWA Offline Route
Route::get('/offline', function () {
    return view('offline');
});

// Central domains list
$centralDomains = array_unique(array_filter([
    config('app.tenant_domain'),
    'localhost',
    '127.0.0.1',
    'taalimu.com',
    'www.taalimu.com',
    parse_url(config('app.url'), PHP_URL_HOST)
]));

$mainRoutes = function () {
    Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('home');

    // Taalimu Design System 1.0 Showcase
    Route::get('/design-system', function () {
        return view('design-system.index');
    })->name('design-system.index');

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
                $currentHost = request()->getHost();
                $targetUrl = ($user->role === 'instructor' || $tenant->type === 'instructor')
                    ? tenant_url('instructor', $tenant)
                    : tenant_url('dashboard', $tenant);
                $targetHost = parse_url($targetUrl, PHP_URL_HOST);

                if ($currentHost === $targetHost) {
                    return redirect()->route('center.dashboard');
                }

                return redirect()->away($targetUrl);
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

    // Zoom webhooks (signature-verified inside the controller)
    Route::post('/webhooks/zoom', [App\Http\Controllers\ZoomWebhookController::class, 'handle'])
        ->middleware('throttle:120,1')
        ->name('webhooks.zoom');

    // Demo Payment Routes (for testing without Stripe)
    Route::get('/payment/demo', [App\Http\Controllers\PaymentController::class, 'demo'])->name('payment.demo');
    Route::get('/payment/demo/success', [App\Http\Controllers\PaymentController::class, 'demoSuccess'])
        ->middleware('throttle:60,1')
        ->name('payment.demo.success');

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
        ->middleware('throttle:login')
        ->name('unified.login.submit');

    // SEC-04: password reset flow
    Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLink'])
        ->middleware('throttle:6,1')
        ->name('password.email');
    Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])
        ->middleware('throttle:6,1')
        ->name('password.update');

    Route::post('logout', [App\Http\Controllers\UnifiedAuthController::class, 'logout'])->name('logout');

    // Social Auth Routes
    Route::get('auth/google', [App\Http\Controllers\SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [App\Http\Controllers\SocialAuthController::class, 'handleGoogleCallback']);
    Route::get('auth/google/complete', [App\Http\Controllers\SocialAuthController::class, 'showCompleteRegistration'])->name('google.complete-registration');
    Route::post('auth/google/complete', [App\Http\Controllers\SocialAuthController::class, 'completeRegistration'])->name('google.complete-registration.post');

    // =========================================================================
    // GROWTH NETWORK — Public Identity Routes (No Auth Required)
    // =========================================================================
    Route::get('/t/{slug}', [\App\Http\Controllers\PublicProfileController::class, 'showTeacher'])
        ->middleware('throttle:120,1')
        ->name('growth.teacher.show');
    Route::get('/c/{slug}', [\App\Http\Controllers\PublicProfileController::class, 'showCenter'])
        ->middleware('throttle:120,1')
        ->name('growth.center.show');

    // =========================================================================
    // GROWTH NETWORK — Public Program Routes (No Auth Required)
    // =========================================================================
    Route::get('/p/{slug}/programs', [\App\Http\Controllers\Growth\ProgramController::class, 'index'])
        ->middleware('throttle:120,1')
        ->name('growth.programs.index');
    Route::get('/p/{profileSlug}/programs/{courseSlug}', [\App\Http\Controllers\Growth\ProgramController::class, 'show'])
        ->middleware('throttle:120,1')
        ->name('growth.programs.show');

    // =========================================================================
    // GROWTH NETWORK — Public Waitlist (No Auth Required)
    // =========================================================================
    Route::post('/p/{profileSlug}/programs/{courseSlug}/waitlist', [\App\Http\Controllers\Growth\WaitlistController::class, 'store'])
        ->middleware('throttle:30,1')
        ->name('growth.waitlist.store');

    // =========================================================================
    // GROWTH NETWORK — Public Demand Request (No Auth Required)
    // =========================================================================
    Route::get('/p/{slug}/demand', [\App\Http\Controllers\Growth\DemandController::class, 'show'])
        ->middleware('throttle:120,1')
        ->name('growth.demand.show');
    Route::post('/p/{slug}/demand', [\App\Http\Controllers\Growth\DemandController::class, 'store'])
        ->middleware('throttle:30,1')
        ->name('growth.demand.store');

    // GROWTH NETWORK — Authenticated Profile Settings
    Route::middleware(['auth', \App\Http\Middleware\EnsureGrowthTenant::class])->prefix('growth')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\Growth\ProfileSettingsController::class, 'edit'])
            ->name('growth.profile.edit');
        Route::put('/profile', [\App\Http\Controllers\Growth\ProfileSettingsController::class, 'update'])
            ->name('growth.profile.update');
        Route::post('/profile/publish', [\App\Http\Controllers\Growth\ProfileSettingsController::class, 'publish'])
            ->name('growth.profile.publish');
        Route::post('/profile/unpublish', [\App\Http\Controllers\Growth\ProfileSettingsController::class, 'unpublish'])
            ->name('growth.profile.unpublish');

        // Growth Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Growth\GrowthDashboardController::class, 'index'])
            ->name('growth.dashboard');
        Route::get('/insights', [\App\Http\Controllers\Growth\GrowthInsightsController::class, 'index'])
            ->middleware('throttle:30,1')
            ->name('growth.insights');
        Route::get('/notifications', [\App\Http\Controllers\Growth\GrowthDashboardController::class, 'notifications'])
            ->name('growth.notifications');
        Route::post('/notifications/{id}/read', [\App\Http\Controllers\Growth\GrowthDashboardController::class, 'markNotificationRead'])
            ->name('growth.notification.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\Growth\GrowthDashboardController::class, 'markAllNotificationsRead'])
            ->name('growth.notifications.read-all');

        // Phase 5 — Referrals
        Route::get('/referrals', [\App\Http\Controllers\Growth\ReferralController::class, 'index'])
            ->name('growth.referrals');

        // Phase 5 — Marketplace (create/manage)
        Route::get('/marketplace/create', [\App\Http\Controllers\Growth\MarketplaceController::class, 'create'])
            ->name('growth.marketplace.create');
        Route::post('/marketplace', [\App\Http\Controllers\Growth\MarketplaceController::class, 'store'])
            ->name('growth.marketplace.store');
        Route::post('/marketplace/{id}/close', [\App\Http\Controllers\Growth\MarketplaceController::class, 'close'])
            ->name('growth.marketplace.close');
    });

    // Phase 5 — Public Discovery (no auth required)
    Route::get('/discover/teachers', [\App\Http\Controllers\Growth\DiscoveryController::class, 'teachers'])
        ->middleware('throttle:60,1')
        ->name('growth.discover.teachers');
    Route::get('/discover/centers', [\App\Http\Controllers\Growth\DiscoveryController::class, 'centers'])
        ->middleware('throttle:60,1')
        ->name('growth.discover.centers');
    Route::get('/discover/listings', [\App\Http\Controllers\Growth\MarketplaceController::class, 'index'])
        ->middleware('throttle:60,1')
        ->name('growth.discover.listings');

    // Phase 5 — Reviews (public + authenticated)
    Route::get('/p/{slug}/reviews', [\App\Http\Controllers\Growth\ReviewController::class, 'index'])
        ->name('growth.reviews.index');
    Route::post('/p/{slug}/reviews', [\App\Http\Controllers\Growth\ReviewController::class, 'store'])
        ->middleware(['auth', 'verified'])
        ->name('growth.reviews.store');

    // Inertia Demo Route
    Route::get('/inertia-demo', function () {
        $user = auth()->user() ?: (object)['name' => 'أستاذنا الافتراضي'];
        $stats = [
            'activeStudents' => 1248,
            'activeCourses' => 18,
            'monthlyRevenue' => '$4,850',
            'attendanceRate' => 94,
        ];
        $leaderboard = [
            ['name' => 'أحمد محمد', 'points' => 950],
            ['name' => 'سارة أحمد', 'points' => 880],
            ['name' => 'محمود علي', 'points' => 820],
            ['name' => 'فاطمة عمر', 'points' => 790],
            ['name' => 'خالد وليد', 'points' => 750],
        ];

        return inertia('DemoDashboard', [
            'user' => $user,
            'stats' => $stats,
            'leaderboard' => $leaderboard
        ]);
    })->middleware('inertia')->name('inertia.demo');
};

$centralDomain = config('app.tenant_domain') ?: parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';

Route::middleware(['web', 'throttle:global'])->domain($centralDomain)->group($mainRoutes);

// Global Language Switcher (Accessible from any domain)
Route::get('lang/{locale}', function ($locale) {
    $activeLocales = ['ar'];

    if (in_array($locale, $activeLocales)) {
        session(['locale' => $locale]);

        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }
    }

    return redirect()->back();
})->middleware('throttle:60,1')->name('lang.switch');
