<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$subs = \App\Models\Subscription::where('tenant_id', 6)->latest()->get();
foreach ($subs as $s) {
    echo "ID: {$s->id} | Price: {$s->stripe_price} | Ends: {$s->ends_at} | Status: {$s->status}\n";
}
