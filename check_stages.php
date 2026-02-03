<?php

use App\Models\Tenant;
use App\Models\Stage;
use App\Models\Grade;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$domain = 'elra3y-2-1';
$tenant = Tenant::where('domain', $domain)->first();

if (!$tenant) {
    die("Tenant not found with domain: {$domain}\n");
}

app()->instance('tenant', $tenant);

echo "Tenant Found: {$tenant->name} (ID: {$tenant->id})\n";

$stages = Stage::withoutGlobalScopes()->where('tenant_id', $tenant->id)->get();
echo "Found " . $stages->count() . " stages for this tenant.\n";

foreach ($stages as $stage) {
    echo "Stage: [{$stage->id}] {$stage->name}\n";
    $grades = Grade::withoutGlobalScopes()->where('stage_id', $stage->id)->get();
    foreach ($grades as $grade) {
        echo "  - Grade: [{$grade->id}] {$grade->name}\n";
    }
}

$allStages = Stage::withoutGlobalScopes()->get();
echo "\nTotal Stages in System: " . $allStages->count() . "\n";
foreach($allStages->unique('tenant_id') as $s) {
    echo "Tenant ID: {$s->tenant_id} has stages.\n";
}
