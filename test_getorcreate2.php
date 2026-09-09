<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "Testing getOrCreateForCenter step by step...\n";

$tenant = \App\Models\Tenant::first();
echo "Tenant: {$tenant->name} (ID: {$tenant->id})\n";

$service = app(\App\Services\NetworkIdentityService::class);

// First, clean up any existing identities for this tenant
\App\Models\NetworkIdentity::where('profilable_type', \App\Models\Tenant::class)
    ->where('profilable_id', $tenant->id)
    ->delete();

echo "Cleaned up existing identities\n";

// Check what getOrCreateForCenter does
echo "\n--- First call to getOrCreateForCenter ---\n";
$identity1 = $service->getOrCreateForCenter($tenant);
echo "Result: {$identity1->public_slug} (ID: {$identity1->id})\n";

// Check database
$count = \App\Models\NetworkIdentity::where('profilable_type', \App\Models\Tenant::class)
    ->where('profilable_id', $tenant->id)
    ->count();
echo "Database count after first call: $count\n";

// Check what the query in getOrCreateForCenter would return
$existing = \App\Models\NetworkIdentity::where('profilable_type', \App\Models\Tenant::class)
    ->where('profilable_id', $tenant->id)
    ->first();
echo "Direct query result: " . ($existing ? "found (ID: {$existing->id})" : "not found") . "\n";

echo "\n--- Second call to getOrCreateForCenter ---\n";
try {
    $identity2 = $service->getOrCreateForCenter($tenant);
    echo "Result: {$identity2->public_slug} (ID: {$identity2->id})\n";
    echo "Same identity: " . ($identity1->id === $identity2->id ? 'YES' : 'NO') . "\n";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

$count = \App\Models\NetworkIdentity::where('profilable_type', \App\Models\Tenant::class)
    ->where('profilable_id', $tenant->id)
    ->count();
echo "Database count after second call: $count\n";