<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$data = [
    'all_settings' => \App\Models\SiteSetting::all()->pluck('value', 'key')->toArray(),
    'packages' => \App\Models\Package::all(['id', 'slug', 'price', 'regional_prices'])->toArray()
];

echo json_encode($data, JSON_PRETTY_PRINT);
