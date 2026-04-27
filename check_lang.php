<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Current Locale: " . app()->getLocale() . "\n";
echo "Fallback Locale: " . config('app.fallback_locale') . "\n";

$key = 'center::analytics.discounts_granted';
echo "Translating '$key': " . __($key) . "\n";

$path = module_path('Center', 'resources/lang/ar/analytics.php');
echo "Checking path: $path\n";
if (file_exists($path)) {
    echo "File exists!\n";
    $content = include $path;
    echo "Has 'discounts_granted'? " . (isset($content['discounts_granted']) ? 'Yes' : 'No') . "\n";
} else {
    echo "File DOES NOT exist!\n";
}

$namespaces = app('translator')->getLoader()->namespaces();
echo "Namespaces registered: " . json_encode($namespaces) . "\n";
