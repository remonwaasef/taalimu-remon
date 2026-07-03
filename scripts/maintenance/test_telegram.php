<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\TelegramService;

$telegram = new TelegramService;
echo "Testing Telegram Notification...\n";
echo 'Token: '.(config('services.telegram.bot_token') ? 'Loaded' : 'NOT LOADED')."\n";
echo 'Chat ID: '.(config('services.telegram.admin_chat_id') ? 'Loaded' : 'NOT LOADED')."\n";

$result = $telegram->sendAdminNotification('🔔 <b>اختبار الاتصال:</b> إذا وصلت هذه الرسالة، فهذا يعني أن الإعدادات صحيحة!');

if ($result) {
    echo "SUCCESS: Message sent to Telegram!\n";
} else {
    echo "FAILED: Could not send message. Check storage/logs/laravel.log for details.\n";
}
