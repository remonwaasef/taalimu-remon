<?php

use Illuminate\Support\Facades\Route;
use Modules\Instructor\Http\Controllers\InstructorController;

$instructorRoutes = function () {
    Route::middleware(['auth', 'verified'])->prefix('instructor')->group(function () {
        Route::get('/', [InstructorController::class, 'index'])->name('instructor.dashboard');
        Route::get('/scanner/{course}', [InstructorController::class, 'scanner'])->name('instructor.scanner');
        Route::post('/scan/{course}', [InstructorController::class, 'scan'])->name('instructor.scan');
        Route::get('/billing', [InstructorController::class, 'billing'])->name('instructor.billing');
        Route::post('/mark-paid', [InstructorController::class, 'markPaid'])->name('instructor.mark-paid');
        Route::resource('students', InstructorController::class)->names('instructor.students');
    });

    // Public Student Portal (Accessible via QR Link)
    Route::get('/s/{identifier}', [\Modules\Instructor\Http\Controllers\StudentPortalController::class, 'index'])->name('student.portal');
};

// Register routes based on tenancy mode (Supporting both path and subdomain)
$mode = config('app.tenancy_mode', 'subdomain');

if ($mode === 'path') {
    Route::prefix('c/{tenant}')
        ->middleware([\App\Http\Middleware\IdentifyTenant::class])
        ->group($instructorRoutes);
}

if ($mode === 'path' || $mode === 'subdomain') {
    $domain = config('app.tenant_domain');
    $appUrlHost = parse_url(config('app.url'), PHP_URL_HOST);
    if (($domain === 'localhost' || empty($domain)) && $appUrlHost && $appUrlHost !== 'localhost') {
        $domain = $appUrlHost;
    }

    Route::domain($domain == 'localhost' ? '{tenant}.localhost' : '{tenant}.' . $domain)
        ->group($instructorRoutes);
}
