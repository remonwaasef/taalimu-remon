<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Execute daily at 10:00 AM
Schedule::command('finance:remind-debts')->dailyAt('10:00');

// Telegram Subscription Reminders (Daily)
Schedule::command('app:send-subscription-reminders')->dailyAt('09:00');

// Telegram Weekly Platform Report (Every Sunday)
Schedule::command('app:send-weekly-telegram-report')->weeklyOn(0, '08:00');
