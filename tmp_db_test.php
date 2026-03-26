<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $db = \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "Connected successfully to " . \Illuminate\Support\Facades\DB::getDatabaseName() . "\n";
} catch (\Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
