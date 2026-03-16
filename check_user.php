<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Tenant;

$email = 'teacher1@example.com';
$user = User::withoutGlobalScopes()->where('email', $email)->first();

if ($user) {
    echo "User found!\n";
    echo "ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Tenant ID: " . $user->tenant_id . "\n";
    
    if ($user->tenant_id) {
        $tenant = Tenant::find($user->tenant_id);
        echo "Tenant Domain: " . ($tenant ? $tenant->domain : 'Not found') . "\n";
    }
} else {
    echo "User NOT found: $email\n";
    
    echo "Total users: " . User::withoutGlobalScopes()->count() . "\n";
    echo "Last 5 users:\n";
    foreach (User::withoutGlobalScopes()->latest()->take(5)->get() as $u) {
        echo "- {$u->email} (Tenant: {$u->tenant_id})\n";
    }
}
