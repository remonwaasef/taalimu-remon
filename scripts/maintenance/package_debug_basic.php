<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = \App\Models\Package::where('slug', 'basic')->first();

echo 'SLUG: '.$p->slug."\n";
echo 'REGIONAL: '.json_encode($p->regional_prices, JSON_PRETTY_PRINT)."\n";
