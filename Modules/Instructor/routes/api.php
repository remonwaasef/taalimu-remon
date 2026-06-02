<?php

use Illuminate\Support\Facades\Route;
use Modules\Instructor\Http\Controllers\InstructorController;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('v1')->group(function () {
    Route::apiResource('instructors', InstructorController::class)->names('instructor');
});
