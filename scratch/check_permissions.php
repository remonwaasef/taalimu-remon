<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'remonwaseff4@gmail.com')->first();
if ($user) {
    echo json_encode([
        'id' => $user->id,
        'role' => $user->role,
        'tenant_id' => $user->tenant_id,
        'spatie_roles' => $user->roles->pluck('name'),
        'spatie_perms' => $user->permissions->pluck('name'),
        'db_perms' => \Illuminate\Support\Facades\DB::table('model_has_permissions')->where('model_id', $user->id)->get(),
    ], JSON_PRETTY_PRINT);
} else {
    echo 'User not found';
}
