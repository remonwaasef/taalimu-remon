<?php

use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\TenantController;
use Modules\Admin\Http\Controllers\AuthController;
use Modules\Admin\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('admin')->name('admin.')->group(function() {
    // Guest Routes
    Route::middleware('guest')->group(function() {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:login')
            ->name('login.submit');
    });

    // Protected Routes
    Route::middleware(['auth', 'role:super_admin'])->group(function() {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::resource('tenants', TenantController::class);
        Route::post('tenants/{tenant}/toggle-status', [TenantController::class, 'toggleStatus'])->name('tenants.toggle-status');
        Route::get('tenants/{tenant}/impersonate', [TenantController::class, 'impersonate'])->name('tenants.impersonate');
        Route::get('impersonate/stop', [TenantController::class, 'stopImpersonating'])->name('impersonate.stop');
        Route::post('tenants/{tenant}/notes', [TenantController::class, 'updateNotes'])->name('tenants.notes');
        Route::post('tenants/{tenant}/reset-password', [TenantController::class, 'resetPassword'])->name('tenants.reset-password');
        
        // Support Ticket Routes
        Route::get('tickets', [\Modules\Admin\Http\Controllers\TicketController::class, 'index'])->name('tickets.index');
        Route::get('tickets/{ticket}', [\Modules\Admin\Http\Controllers\TicketController::class, 'show'])->name('tickets.show');
        Route::post('tickets/{ticket}/reply', [\Modules\Admin\Http\Controllers\TicketController::class, 'reply'])->name('tickets.reply');
        Route::post('tickets/{ticket}/close', [\Modules\Admin\Http\Controllers\TicketController::class, 'close'])->name('tickets.close');

        // Subscriptions
        Route::get('subscriptions', [\Modules\Admin\Http\Controllers\SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::get('subscriptions/{id}/edit', [\Modules\Admin\Http\Controllers\SubscriptionController::class, 'edit'])->name('subscriptions.edit');
        Route::put('subscriptions/{id}', [\Modules\Admin\Http\Controllers\SubscriptionController::class, 'update'])->name('subscriptions.update');
        Route::delete('subscriptions/{id}', [\Modules\Admin\Http\Controllers\SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

        // Activity Logs
        Route::get('activity-logs', [\Modules\Admin\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');

        // Settings
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
        
        // Package Management
        Route::post('settings/packages', [SettingsController::class, 'storePackage'])->name('settings.packages.store');
        Route::delete('settings/packages/{id}', [SettingsController::class, 'destroyPackage'])->name('settings.packages.destroy');

        // Feature Management
        Route::post('settings/features', [SettingsController::class, 'storeFeature'])->name('settings.features.store');
        Route::put('settings/features/{id}', [SettingsController::class, 'updateFeature'])->name('settings.features.update');
        Route::delete('settings/features/{id}', [SettingsController::class, 'destroyFeature'])->name('settings.features.destroy');

        // Coupons
        Route::post('coupons', [\Modules\Admin\Http\Controllers\CouponController::class, 'store'])->name('coupons.store');
        Route::put('coupons/{coupon}', [\Modules\Admin\Http\Controllers\CouponController::class, 'update'])->name('coupons.update');
        Route::delete('coupons/{coupon}', [\Modules\Admin\Http\Controllers\CouponController::class, 'destroy'])->name('coupons.destroy');
        // Roles & Permissions
        Route::resource('roles', \Modules\Admin\Http\Controllers\RoleController::class);

    });
});
