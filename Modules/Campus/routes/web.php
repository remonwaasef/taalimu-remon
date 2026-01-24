<?php

use Illuminate\Support\Facades\Route;
use Modules\Campus\Http\Controllers\CampusController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::domain(config('app.tenant_domain') == 'localhost' ? '{tenant}.localhost' : '{tenant}.' . config('app.tenant_domain'))
    ->middleware([\App\Http\Middleware\IdentifyTenant::class, 'auth'])
    ->group(function () {
    Route::prefix('campus')->name('campus.')->group(function() {
        Route::get('/', [CampusController::class, 'index'])->name('index');
        Route::get('/schedule', [CampusController::class, 'schedule'])->name('schedule');
        Route::get('/finances', [CampusController::class, 'finances'])->name('finances');
        Route::get('/attendance', [CampusController::class, 'attendance'])->name('attendance');
        Route::get('/profile', [CampusController::class, 'profile'])->name('profile');
        Route::get('/courses', [CampusController::class, 'courses'])->name('courses.index');
        Route::get('/certificates/{certificate}/download', [CampusController::class, 'downloadCertificate'])->name('certificates.download');
    });
});
