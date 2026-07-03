<?php

use App\Models\Tenant;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Starting Verification...\n";

// --- 1. Test Rate Limiting ---
echo "\nTesting Rate Limiting...\n";
$key = 'login.test@example.com|127.0.0.1';
RateLimiter::clear($key);

for ($i = 1; $i <= 6; $i++) {
    if (RateLimiter::tooManyAttempts($key, 5)) {
        echo "Attempt $i: BLOCKED (Success)\n";
    } else {
        RateLimiter::hit($key);
        echo "Attempt $i: Allowed\n";
    }
}

// --- 2. Test Cached Counters ---
echo "\nTesting Cached Counters...\n";
$tenant = Tenant::first();

if (! $tenant) {
    echo "No tenant found. Creating dummy tenant...\n";
    $tenant = Tenant::factory()->create();
}

echo "Tenant ID: {$tenant->id}\n";
$service = new SubscriptionService;
$feature = 'max_students';
$cacheKey = "tenant_{$tenant->id}_usage_{$feature}";

// Clear cache first
Cache::forget($cacheKey);

// Initial Check (Should run Query)
$reflection = new ReflectionClass($service);
$method = $reflection->getMethod('getUsage');
$method->setAccessible(true);
$count = $method->invoke($service, $tenant, $feature);
echo "Initial Usage Count: $count\n";

echo "Creating new student user...\n";
$user = User::factory()->create([
    'tenant_id' => $tenant->id,
    'role' => 'student',
    'email' => 'test_student_'.time().'@example.com',
]);

// Check Cache Key consistency
$cachedValue = Cache::get($cacheKey);
echo 'Cached Value after creation: '.($cachedValue ?? 'NULL')."\n";

echo "Deleting student user...\n";
$user->delete();

$cachedValueAfterDelete = Cache::get($cacheKey);
echo 'Cached Value after delete: '.($cachedValueAfterDelete ?? 'NULL')."\n";

echo "\nVerification script finished.\n";
