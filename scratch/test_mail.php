<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('Hallo Remon! This is a test email from Taalimu platform to confirm SMTP is working.', function ($message) {
        $message->to('reemmoo20022@gmail.com')->subject('Taalimu Test Email');
    });
    echo 'Success: Email sent successfully!';
} catch (\Exception $e) {
    echo 'Error: '.$e->getMessage();
}
