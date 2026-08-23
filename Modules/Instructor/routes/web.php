<?php

use Illuminate\Support\Facades\Route;
use Modules\Instructor\Http\Controllers\AttendanceController;
use Modules\Instructor\Http\Controllers\ClassRecordingController;
use Modules\Instructor\Http\Controllers\GroupController;
use Modules\Instructor\Http\Controllers\InstructorController;
use Modules\Instructor\Http\Controllers\OnlineClassController;
use Modules\Instructor\Http\Controllers\OnlineClassSessionController;
use Modules\Instructor\Http\Controllers\ScheduleController;
use Modules\Instructor\Http\Controllers\SettingsController;
use Modules\Instructor\Http\Controllers\StudentController;

$instructorRoutes = function () {
    Route::middleware(['auth', '2fa', 'verified', 'subscription', 'instructor.role'])->prefix('instructor')->group(function () {
        Route::get('/', [InstructorController::class, 'index'])->name('instructor.dashboard');
        Route::get('/set-locale/{locale}', [SettingsController::class, 'setLocale'])->name('instructor.set-locale');
        Route::get('/scanner/{course}', [InstructorController::class, 'scanner'])->name('instructor.scanner');
        Route::post('/scan/{course}', [InstructorController::class, 'scan'])->name('instructor.scan');
        Route::get('/billing', [InstructorController::class, 'billing'])->name('instructor.billing');
        Route::post('/mark-paid', [InstructorController::class, 'markPaid'])->name('instructor.students.mark-paid');
        Route::get('/students-list', [StudentController::class, 'index'])->name('instructor.students.list');
        Route::get('/students-export', [StudentController::class, 'export'])->name('instructor.students.export');
        Route::get('/groups-list', [GroupController::class, 'index'])->name('instructor.groups.list');
        Route::get('/groups/create', [GroupController::class, 'create'])->name('instructor.groups.create');
        Route::post('/groups', [GroupController::class, 'store'])->name('instructor.groups.store');
        Route::get('/groups/{course}/edit', [GroupController::class, 'edit'])->name('instructor.groups.edit');
        Route::put('/groups/{course}', [GroupController::class, 'update'])->name('instructor.groups.update');
        Route::post('/groups/{course}/rotate-link', [GroupController::class, 'rotateLink'])->name('instructor.groups.rotate-link');
        Route::post('/groups/{course}/duplicate', [GroupController::class, 'duplicate'])->name('instructor.groups.duplicate');
        Route::delete('/groups/{course}', [GroupController::class, 'destroy'])->name('instructor.groups.destroy');

        Route::get('/students-create', [StudentController::class, 'create'])->name('instructor.students.create');
        Route::post('/students-store', [StudentController::class, 'store'])->name('instructor.students.store');
        Route::get('/students/{student}', [StudentController::class, 'show'])->name('instructor.students.show');
        Route::post('/students/import', [StudentController::class, 'import'])->name('instructor.students.import');
        Route::post('/students/{student}/toggle-status', [StudentController::class, 'toggleStatus'])->name('instructor.students.toggle-status');
        Route::post('/students/{student}/update-notes', [StudentController::class, 'updateNotes'])->name('instructor.students.update-notes');
        Route::post('/students/{student}/transfer', [StudentController::class, 'transfer'])->name('instructor.students.transfer');
        Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('instructor.students.destroy');
        Route::post('/students/{student}/send-email', [StudentController::class, 'sendEmail'])->name('instructor.students.send-email');

        // Settings Dashboard
        Route::get('/settings', [SettingsController::class, 'index'])->name('instructor.settings');
        Route::post('/settings/update-general', [SettingsController::class, 'updateGeneral'])->name('instructor.settings.update-general');

        // WhatsApp Settings
        Route::get('/whatsapp', [SettingsController::class, 'whatsapp'])->name('instructor.whatsapp.settings');
        Route::post('/whatsapp/update', [SettingsController::class, 'updateWhatsApp'])->name('instructor.whatsapp.update');

        // Payment Reminders Settings
        Route::post('/reminders/update', [SettingsController::class, 'updateReminders'])->name('instructor.reminders.update');
        Route::post('/students/{student}/update-payment', [StudentController::class, 'updatePayment'])->name('instructor.students.update-payment');

        // Email Templates Settings
        Route::post('/email-templates/update', [SettingsController::class, 'updateEmailTemplates'])->name('instructor.email-templates.update');
        Route::post('/email-templates/reset', [SettingsController::class, 'resetEmailTemplates'])->name('instructor.email-templates.reset');

        // Auto-clear cache route (Temporary helper)
        if (app()->environment('local')) {
            Route::get('/clear-cache', function () {
            \Illuminate\Support\Facades\Artisan::call('view:clear');

            return 'تم مسح الكاش بنجاح! يمكنك الآن الرجوع للصفحة الرئيسية وتحديثها لترى التعديلات.';
            });
        }
    });

    // Public Phone Check
    Route::get('/instructor/check-phone', [StudentController::class, 'checkPhone'])
        ->middleware('throttle:20,1')
        ->name('instructor.students.check-phone');

    Route::middleware(['auth', '2fa', 'verified', 'subscription', 'instructor.role'])->prefix('instructor')->group(function () {

        // Online Classes
        Route::resource('online-classes', OnlineClassController::class)->names('instructor.online_classes');

        // Live Session Controls (AJAX)
        Route::prefix('online-classes/{onlineClass}')->group(function () {
            Route::post('start', [OnlineClassSessionController::class, 'start'])->name('instructor.online_classes.start');
            Route::post('end', [OnlineClassSessionController::class, 'end'])->name('instructor.online_classes.end');
            Route::get('join-token', [OnlineClassSessionController::class, 'joinToken'])->name('instructor.online_classes.join_token');
        });

        // Recordings & Analytics
        Route::prefix('recordings')->name('instructor.recordings.')->group(function () {
            Route::get('/', [ClassRecordingController::class, 'index'])->name('index');
            Route::get('{recording}', [ClassRecordingController::class, 'show'])->name('show');
        });

        // Instructor Schedule Management
        Route::get('/schedules', [ScheduleController::class, 'index'])->name('instructor.schedules.index');
        Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('instructor.schedules.create');
        Route::post('/schedules', [ScheduleController::class, 'store'])->name('instructor.schedules.store');
        Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('instructor.schedules.edit');
        Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('instructor.schedules.update');
        Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('instructor.schedules.destroy');
        Route::post('/classrooms/store', [ScheduleController::class, 'storeClassroom'])->name('instructor.classrooms.store');

        // Instructor Attendance
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('instructor.attendance.index');
        Route::get('/attendance/schedule/{schedule}', [AttendanceController::class, 'show'])->name('instructor.attendance.show');
        Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('instructor.attendance.store');
        Route::post('/attendance/bulk-absent/{schedule}', [AttendanceController::class, 'bulkAbsent'])->name('instructor.attendance.bulkAbsent');

        // Reports
        Route::get('/reports', function () {
            return redirect()->route('instructor.reports.students');
        })->name('instructor.reports');
        Route::get('/reports/students', [InstructorController::class, 'studentReports'])->name('instructor.reports.students');
        Route::get('/reports/payments', [InstructorController::class, 'paymentReports'])->name('instructor.reports.payments');

    });

    // Public Student Portal (Accessible via QR Link)
    Route::get('/s/{identifier}', [\Modules\Instructor\Http\Controllers\StudentPortalController::class, 'index'])
        ->middleware('signed')
        ->name('student.portal');
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

    $domains = array_unique(array_filter([
        $domain == 'localhost' ? '{tenant}.localhost' : '{tenant}.'.$domain,
        '{tenant}.localhost',
        '{tenant}.taalimu.com'
    ]));

    foreach ($domains as $d) {
        Route::domain($d)
            ->middleware([\App\Http\Middleware\IdentifyTenant::class])
            ->group($instructorRoutes);
    }
}
