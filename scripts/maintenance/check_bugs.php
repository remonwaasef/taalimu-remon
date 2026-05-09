<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$bugs = \App\Models\BugReport::latest()->take(5)->get();
foreach ($bugs as $bug) {
    echo "ID: " . $bug->id . " | Screenshot: " . $bug->screenshot . " | Created: " . $bug->created_at . "\n";
}
