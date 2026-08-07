<?php

use Illuminate\Support\Facades\Route;
use Modules\Api\Http\Middleware\ApiTenantMiddleware;
use Modules\Tenancy\Services\TenantResolver;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your module. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

// Public API routes
Route::get('/ping', function () {
    return response()->json(['success' => true, 'message' => 'API is running!']);
});

// Tenant-protected API routes
Route::middleware([ApiTenantMiddleware::class])->group(function () {

    Route::get('/tenant/info', function () {
        $tenant = TenantResolver::get();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'domain' => $tenant->domain,
            ],
        ]);
    });

});
