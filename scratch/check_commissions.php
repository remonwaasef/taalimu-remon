<?php

use App\Models\Commission;
use App\Models\Tenant;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = DB::table('commissions')->count();
echo "Total commissions in DB: " . $count . "\n";

$tenants = DB::table('tenants')->get();
foreach ($tenants as $tenant) {
    $cCount = DB::table('commissions')->where('tenant_id', $tenant->id)->count();
    echo "Tenant: " . $tenant->name . " (ID: " . $tenant->id . ") - Commissions: " . $cCount . "\n";
}
