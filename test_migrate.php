<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();
$exitCode = $kernel->call('migrate', ['--force' => true]);
echo "Exit code: $exitCode";