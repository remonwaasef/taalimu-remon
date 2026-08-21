<?php

use Illuminate\Support\Facades\Route;
use Modules\Parent\Http\Controllers\AuthController;
use Modules\Parent\Http\Controllers\ParentController;

/*
|--------------------------------------------------------------------------
| Web Routes — Parent Portal (بوابة ولي الأمر)
|--------------------------------------------------------------------------
| Tenant subdomains only, protected by auth + parent_portal feature.
*/

$parentDomains = array_unique(array_filter([
    config('app.tenant_domain') == 'localhost' ? '{tenant}.localhost' : '{tenant}.'.config('app.tenant_domain'),
    '{tenant}.localhost',
    '{tenant}.taalimu.com'
]));

foreach ($parentDomains as $d) {
    Route::domain($d)
        ->middleware([\App\Http\Middleware\IdentifyTenant::class])
        ->group(function () {
            // Guest Routes — dedicated parent login page
            Route::middleware(['guest', 'prevent-back-history'])->group(function () {
                Route::get('parent/login', [AuthController::class, 'showLoginForm'])->name('parent.login');
                Route::post('parent/login', [AuthController::class, 'login'])
                    ->middleware('throttle:login')
                    ->name('parent.login.submit');
            });

            Route::middleware(['auth', '2fa', 'feature:parent_portal'])->group(function () {
                Route::prefix('parent')->name('parent.')->group(function () {
                    Route::get('/', [ParentController::class, 'index'])->name('index');
                    Route::get('/courses', [ParentController::class, 'courses'])->name('courses');
                    Route::get('/schedule', [ParentController::class, 'schedule'])->name('schedule');
                    Route::get('/attendance', [ParentController::class, 'attendance'])->name('attendance');
                    Route::get('/finances', [ParentController::class, 'finances'])->name('finances');
                    Route::get('/profile', [ParentController::class, 'profile'])->name('profile');
                });
            });
        });
}