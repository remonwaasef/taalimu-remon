<?php

use Illuminate\Support\Facades\Route;
use Modules\Center\Http\Controllers\CenterController;

Route::middleware([
    'auth:sanctum',
    \Modules\Api\Http\Middleware\ApiTenantMiddleware::class,
    'throttle:60,1',
])->prefix('v1')->group(function () {
    Route::apiResource('centers', CenterController::class)->names('center');
});
