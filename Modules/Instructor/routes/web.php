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
        Route::get('/students-list', [InstructorController::class, 'students'])->name('instructor.students.list');
        Route::get('/groups-list', [InstructorController::class, 'groups'])->name('instructor.groups.list');
        Route::get('/groups/create', [InstructorController::class, 'createGroup'])->name('instructor.groups.create');
        Route::post('/groups', [InstructorController::class, 'storeGroup'])->name('instructor.groups.store');
        Route::resource('students', InstructorController::class)->names('instructor.students');

        // Instructor Schedule Management
        Route::get('/schedules', [InstructorController::class, 'schedules'])->name('instructor.schedules.index');
        Route::get('/schedules/create', [InstructorController::class, 'createSchedule'])->name('instructor.schedules.create');
        Route::post('/schedules', [InstructorController::class, 'storeSchedule'])->name('instructor.schedules.store');
        Route::get('/schedules/{schedule}/edit', [InstructorController::class, 'editSchedule'])->name('instructor.schedules.edit');
        Route::put('/schedules/{schedule}', [InstructorController::class, 'updateSchedule'])->name('instructor.schedules.update');
        Route::delete('/schedules/{schedule}', [InstructorController::class, 'destroySchedule'])->name('instructor.schedules.destroy');

        // Instructor Attendance
        Route::get('/attendance', [InstructorController::class, 'attendance'])->name('instructor.attendance.index');
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
        ->middleware([\App\Http\Middleware\IdentifyTenant::class])
        ->group($instructorRoutes);
}
