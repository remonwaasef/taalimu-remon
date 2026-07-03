<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (\App\Models\Package::all() as $p) {
    echo 'ID: '.$p->id.' | Name: '.$p->name.' | Slug: '.$p->slug."\n";
    echo 'PRICE: '.$p->price."\n";
    echo 'REGIONAL: '.json_encode($p->regional_prices, JSON_PRETTY_PRINT)."\n";
    echo "-------------------\n";
}
