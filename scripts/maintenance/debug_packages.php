<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$packages = App\Models\Package::all();
foreach ($packages as $p) {
    echo 'Slug: '.$p->slug."\n";
    echo 'Name: '.$p->name."\n";
    echo 'Regional Prices: '.json_encode($p->regional_prices)."\n";
    echo "-------------------\n";
}
