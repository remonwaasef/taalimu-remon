<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tenant = DB::table('tenants')->where('domain', 'akadmy-rymon')->first();
if (!$tenant) {
    echo "Tenant not found\n";
    exit;
}

echo "Tenant: " . $tenant->name . " (ID: " . $tenant->id . ")\n";

$instructors = DB::table('instructors')->where('tenant_id', $tenant->id)->get();
echo "Instructors: " . $instructors->count() . "\n";
foreach ($instructors as $instructor) {
    echo " - " . $instructor->name . " (Rate: " . $instructor->commission_rate . " " . $instructor->commission_type . ")\n";
}

$sales = DB::table('sales')->where('tenant_id', $tenant->id)->count();
echo "Sales: " . $sales . "\n";

$commissions = DB::table('commissions')->where('tenant_id', $tenant->id)->count();
echo "Commissions in DB: " . $commissions . "\n";
