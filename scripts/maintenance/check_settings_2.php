<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $t = \App\Models\Tenant::first();
    echo "Tenant ID: " . $t->id . "\n";
    $settings = $t->settings['email_templates'] ?? [];
    echo "notif_group_enrollment_enabled: " . var_export($settings['notif_group_enrollment_enabled'] ?? null, true) . "\n";
    echo "notif_payment_confirmed_enabled: " . var_export($settings['notif_payment_confirmed_enabled'] ?? null, true) . "\n";
    echo "notif_payment_reminder_enabled: " . var_export($settings['notif_payment_reminder_enabled'] ?? null, true) . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
