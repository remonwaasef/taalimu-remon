<?php

use Illuminate\Support\Facades\Route;
use Modules\Tenancy\Http\Controllers\TenancyController;

Route::middleware([
    'auth:sanctum',
    \Modules\Api\Http\Middleware\ApiTenantMiddleware::class,
    'throttle:60,1',
])->prefix('v1')->group(function () {
    Route::apiResource('tenancies', TenancyController::class)->names('tenancy');
});
