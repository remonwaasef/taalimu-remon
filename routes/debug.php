<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// LOCAL-ONLY QA helper endpoints (loaded from bootstrap/app.php when app.env=local).
// GET-only so they work cross-origin (tenant subdomain ↔ main domain) without CSRF/CORS.
// NEVER deploy these to production: they expose password-reset KPI and mutate users.

Route::prefix('_qa/db')->group(function () {
    Route::get('password_reset', function () {
        $record = DB::table('password_reset_tokens')->where('email', 'admin@demo.com')->first();

        return [
            'ok' => $record !== null,
            'hashed' => $record ? (str_starts_with($record->token, '$2y$') || str_starts_with($record->token, '$argon')) : false,
            'plaintext' => $record ? preg_match('/^[A-Za-z0-9]{64}$/', $record->token) : false,
        ];
    });

    Route::get('set-must-change', function (\Illuminate\Http\Request $request) {
        $user = \App\Models\User::where('email', $request->input('email'))->first();
        if (! $user) { return ['ok' => false]; }
        $user->must_change_password = (int) $request->input('value', 1);
        $user->save();

        return ['ok' => true];
    });

    Route::get('get-must-change', function (\Illuminate\Http\Request $request) {
        $user = \App\Models\User::where('email', $request->input('email'))->first();

        return $user ? ['value' => (bool) $user->must_change_password] : ['value' => null];
    });

    Route::get('reset-demo-password', function (\Illuminate\Http\Request $request) {
        $user = \App\Models\User::where('email', $request->input('email'))->first();
        if (! $user) { return ['ok' => false]; }
        $user->password = Hash::make('password');
        $user->must_change_password = 0;
        $user->save();

        return ['ok' => true];
    });
});