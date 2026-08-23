<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Execute daily at 10:00 AM
Schedule::command('finance:remind-debts')->dailyAt('10:00')->withoutOverlapping();

// Payment Reminders: Email + WhatsApp (Daily at 08:00 AM)
Schedule::command('reminders:send-payment')->dailyAt('08:00')->withoutOverlapping();

// Payment Reminders: free WhatsApp via Telegram click-to-send links (Daily at 08:05 AM)
Schedule::command('reminders:send-wa-links')->dailyAt('08:05')->withoutOverlapping();

// Telegram Subscription Reminders (Daily)
Schedule::command('app:send-subscription-reminders')->dailyAt('09:00')->withoutOverlapping();

// Telegram Daily Platform Report (Daily at end of day)
Schedule::command('app:send-daily-telegram-report')->dailyAt('23:55')->withoutOverlapping();

// Telegram Weekly Platform Report (Every Sunday)
Schedule::command('app:send-weekly-telegram-report')->weeklyOn(0, '08:00')->withoutOverlapping();

// Telegram Inactivity Check (Daily)
Schedule::command('app:check-inactive-tenants')->dailyAt('11:00')->withoutOverlapping();

// Daily Issue Digest (Daily at 08:30 AM)
Schedule::call(fn () => app(\App\Services\IssueNotifier::class)->sendDailyDigest())
    ->dailyAt('08:30')
    ->name('daily-issue-digest')
    ->withoutOverlapping();

// Onboarding Emails Sequence (Draft mode, controlled by env ENABLE_ONBOARDING_EMAILS)
Schedule::command('onboarding:send-emails')->dailyAt('10:00')->withoutOverlapping();

// Automated Backups (Database + Files)
Schedule::command('backup:clean')->dailyAt('01:00')->withoutOverlapping();
Schedule::command('backup:run')->dailyAt('01:30')->withoutOverlapping();

// Backup health checks — alert when the newest backup is older than 1 day
Schedule::command('backup:monitor')->dailyAt('02:00')->withoutOverlapping();

// Database size monitoring (weekly, triggers scaling decisions per docs/37_DATABASE_SCALING.md)
Schedule::command('db:monitor-sizes')->weeklyOn(0, '07:00')->withoutOverlapping();

// SEO: regenerate the sitemap nightly
Schedule::command('sitemap:generate')->dailyAt('03:30')->withoutOverlapping();

// Online Classes: T-15min student reminders (runs every minute, idempotent via reminder_sent_at)
Schedule::command('online-classes:send-reminders')->everyMinute()->withoutOverlapping();
