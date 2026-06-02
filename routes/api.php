<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Health Check Endpoint — Network Monitor
|--------------------------------------------------------------------------
| Lightweight endpoint for real connectivity validation.
| Equivalent to DNS lookup in Flutter's network_info.dart.
| Returns 204 No Content (minimal payload for speed).
|
| Supports HEAD and GET methods. HEAD is preferred (zero body).
| Rate limited to prevent abuse.
|--------------------------------------------------------------------------
*/
Route::match(['get', 'head'], '/health-check', function () {
    return response()->noContent();
})->middleware('throttle:120,1');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum', 'throttle:60,1']);
