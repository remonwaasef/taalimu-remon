<?php

use Illuminate\Support\Facades\Route;
use Modules\Campus\Http\Controllers\CampusController;
use Modules\Campus\Http\Controllers\StudentClassController;

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

$campusDomains = array_unique(array_filter([
    config('app.tenant_domain') == 'localhost' ? '{tenant}.localhost' : '{tenant}.'.config('app.tenant_domain'),
    '{tenant}.localhost',
    '{tenant}.taalimu.com'
]));

foreach ($campusDomains as $d) {
    Route::domain($d)
        ->middleware([\App\Http\Middleware\IdentifyTenant::class, 'auth', '2fa', 'feature:student_portal'])
        ->group(function () {
            Route::prefix('campus')->name('campus.')->group(function () {
                Route::get('/', [CampusController::class, 'index'])->name('index');
                Route::get('/schedule', [CampusController::class, 'schedule'])->name('schedule');
                Route::get('/finances', [CampusController::class, 'finances'])->name('finances');
                Route::get('/attendance', [CampusController::class, 'attendance'])->name('attendance');
                Route::get('/profile', [CampusController::class, 'profile'])->name('profile');
                Route::get('/courses', [CampusController::class, 'courses'])->name('courses.index');
                Route::get('/certificates/{certificate}/download', [CampusController::class, 'downloadCertificate'])->name('certificates.download');

                // Online Classes & Recordings
                Route::get('/classes', [StudentClassController::class, 'index'])->name('classes.index');
                Route::get('/classes/{onlineClass}/join', [StudentClassController::class, 'join'])->name('classes.join');
                Route::get('/recordings/{recording}', [StudentClassController::class, 'watch'])->name('recordings.watch');
                Route::post('/recordings/{recording}/token', [StudentClassController::class, 'token'])->name('recordings.token');
                Route::post('/recordings/{recording}/progress', [StudentClassController::class, 'progress'])->name('recordings.progress');
            });
        });
}
