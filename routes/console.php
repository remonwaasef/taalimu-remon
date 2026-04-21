<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Execute daily at 10:00 AM
Schedule::command('finance:remind-debts')->dailyAt('10:00');

// Payment Reminders: Email + WhatsApp (Daily at 08:00 AM)
Schedule::command('reminders:send-payment')->dailyAt('08:00');

// Telegram Subscription Reminders (Daily)
Schedule::command('app:send-subscription-reminders')->dailyAt('09:00');

// Telegram Daily Platform Report (Daily at end of day)
Schedule::command('app:send-daily-telegram-report')->dailyAt('23:55');

// Telegram Weekly Platform Report (Every Sunday)
Schedule::command('app:send-weekly-telegram-report')->weeklyOn(0, '08:00');

// Telegram Inactivity Check (Daily)
Schedule::command('app:check-inactive-tenants')->dailyAt('11:00');
// Daily Issue Digest (Daily at 08:30 AM)
Schedule::call(fn() => app(\App\Services\IssueNotifier::class)->sendDailyDigest())->dailyAt('08:30');
