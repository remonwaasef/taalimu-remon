<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tenants = \App\Models\Tenant::whereIn('domain', ['remonq', 'remonj', 'ra3y'])->get();
foreach ($tenants as $tenant) {
    echo "Domain: " . $tenant->domain . "\n";
    echo "Created At: " . $tenant->created_at . "\n";
    $sub = $tenant->activeSubscription();
    if ($sub) {
        echo "Sub Created: " . $sub->created_at . "\n";
        echo "Sub Ends: " . $sub->ends_at . "\n";
        echo "Sub Cycle: " . $sub->billing_cycle . "\n";
    }
    echo "-------------------\n";
}
