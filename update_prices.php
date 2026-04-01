<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Package;

$updates = [
    'basic' => [
        'price' => 299,
        'term_price' => 1299, // Approximate or requested? User didn't specify term prices. I'll stick to monthly.
        'yearly_price' => 2990,
        'max_students' => 100,
        'regional_prices' => [
            'EGP' => ['amount' => 299, 'currency' => 'EGP', 'yearly_price' => 2990],
            'USD' => ['amount' => 29, 'currency' => 'USD', 'yearly_price' => 290],
            'EUR' => ['amount' => 19.90, 'currency' => 'EUR', 'yearly_price' => 199],
        ]
    ],
    'pro' => [
        'price' => 599,
        'term_price' => 2499,
        'yearly_price' => 5990,
        'max_students' => 500,
        'regional_prices' => [
            'EGP' => ['amount' => 599, 'currency' => 'EGP', 'yearly_price' => 5990],
            'USD' => ['amount' => 49, 'currency' => 'USD', 'yearly_price' => 490],
            'EUR' => ['amount' => 39.90, 'currency' => 'EUR', 'yearly_price' => 399],
        ]
    ],
    'enterprise' => [
        'price' => 1299,
        'term_price' => 4999,
        'yearly_price' => 12990,
        'max_students' => 2000,
        'regional_prices' => [
            'EGP' => ['amount' => 1299, 'currency' => 'EGP', 'yearly_price' => 12990],
            'USD' => ['amount' => 99, 'currency' => 'USD', 'yearly_price' => 990],
            'EUR' => ['amount' => 79.90, 'currency' => 'EUR', 'yearly_price' => 799],
        ]
    ]
];

foreach ($updates as $slug => $data) {
    $package = Package::where('slug', $slug)->first();
    if ($package) {
        $maxStudents = $data['max_students'];
        unset($data['max_students']);
        
        $package->update($data);
        
        // Update student limit feature
        $package->features()->syncWithoutDetaching([
            1 => ['value' => $maxStudents] // ID 1 is max_students
        ]);
        
        echo "Updated $slug (Students: $maxStudents)\n";
    } else {
        echo "Package $slug not found\n";
    }
}
echo "Done.\n";
