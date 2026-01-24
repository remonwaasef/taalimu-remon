<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;

Route::middleware(['auth:sanctum', 'role:super_admin'])->prefix('v1')->group(function () {
    Route::apiResource('admins', AdminController::class)->names('admin');
});
