<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Package;

$updates = [
    'basic' => [
        'price' => 450,
        'term_price' => 1450,
        'yearly_price' => 2500,
        'regional_prices' => [
            'EG' => ['amount' => 450, 'currency' => 'EGP', 'term_price' => 1450, 'yearly_price' => 2500],
        ]
    ],
    'pro' => [
        'price' => 950,
        'term_price' => 3450,
        'yearly_price' => 6000,
        'regional_prices' => [
            'EG' => ['amount' => 950, 'currency' => 'EGP', 'term_price' => 3450, 'yearly_price' => 6000],
        ]
    ],
    'enterprise' => [
        'price' => 1950,
        'term_price' => 6950,
        'yearly_price' => 12000,
        'regional_prices' => [
            'EG' => ['amount' => 1950, 'currency' => 'EGP', 'term_price' => 6950, 'yearly_price' => 12000],
        ]
    ]
];

foreach ($updates as $slug => $data) {
    $package = Package::where('slug', $slug)->first();
    if ($package) {
        $package->update($data);
        echo "Updated $slug\n";
    } else {
        echo "Package $slug not found\n";
    }
}
echo "Done.\n";
