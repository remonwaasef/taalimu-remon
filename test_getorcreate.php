<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "Testing getOrCreateForCenter...\n";

$tenant = \App\Models\Tenant::first();
echo "Tenant: {$tenant->name} (ID: {$tenant->id})\n";

$service = app(\App\Services\NetworkIdentityService::class);

// First, clean up any existing identities for this tenant
\App\Models\NetworkIdentity::where('profilable_type', \App\Models\Tenant::class)
    ->where('profilable_id', $tenant->id)
    ->delete();

echo "Cleaned up existing identities\n";

// Test getOrCreate for center (should create new)
$identity1 = $service->getOrCreateForCenter($tenant);
echo "First getOrCreate: {$identity1->public_slug} (ID: {$identity1->id})\n";

// Test getOrCreate for center again (should return same)
$identity2 = $service->getOrCreateForCenter($tenant);
echo "Second getOrCreate: {$identity2->public_slug} (ID: {$identity2->id})\n";

echo "Same identity: " . ($identity1->id === $identity2->id ? 'YES' : 'NO') . "\n";

// Check database directly
$count = \App\Models\NetworkIdentity::where('profilable_type', \App\Models\Tenant::class)
    ->where('profilable_id', $tenant->id)
    ->count();
echo "Database count: $count\n";

echo "\nDone\n";