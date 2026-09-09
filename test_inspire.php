<?php
ini_set('memory_limit', '4G');
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();
$exitCode = $kernel->call('inspire');
echo "Exit code: $exitCode";