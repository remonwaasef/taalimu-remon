<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Public Path: " . public_path() . "\n";
echo "Storage Path: " . storage_path() . "\n";
echo "Physical storage/bug-reports exists: " . (is_dir(public_path('storage/bug-reports')) ? "YES" : "NO") . "\n";
