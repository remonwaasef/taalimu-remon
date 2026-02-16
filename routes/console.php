<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Load all console commands from app/Console/Commands
$commandDir = app_path('Console/Commands');
if (is_dir($commandDir)) {
    foreach (glob($commandDir . '/*.php') as $filename) {
        $class = 'App\\Console\\Commands\\' . basename($filename, '.php');
        if (class_exists($class)) {
            Artisan::starting(function ($artisan) use ($class) {
                $artisan->resolve($class);
            });
        }
    }
}
