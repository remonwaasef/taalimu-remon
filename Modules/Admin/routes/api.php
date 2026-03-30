<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;

Route::middleware(['auth:sanctum', \App\Http\Middleware\CheckAdminRole::class])->prefix('v1')->group(function () {
    Route::apiResource('admins', AdminController::class)->names('admin');
});
