<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Events\Dispatcher;
use Spatie\Activitylog\Models\Activity;

class AuthenticationSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleUserLogin($event): void
    {
        activity('auth')
            ->performedOn($event->user)
            ->causedBy($event->user)
            ->withProperties([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Successful Login');

        // Notify Admin for Super Admin login
        if ($event->user->role === 'super_admin' || $event->user->email === config('app.admin_email', 'admin@taalimu.com')) {
            try {
                app(\App\Services\TelegramService::class)->sendLoginAlert($event->user, request()->ip());
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("AuthenticationSubscriber (Login Alert): Telegram notification failed. Error: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle user logout events.
     */
    public function handleUserLogout($event): void
    {
        if ($event->user) {
            activity('auth')
                ->performedOn($event->user)
                ->causedBy($event->user)
                ->withProperties([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log('User Logout');
        }
    }

    /**
     * Handle user login failure events.
     */
    public function handleUserLoginFailed($event): void
    {
        activity('auth')
            ->withProperties([
                'email' => $event->credentials['email'] ?? 'unknown',
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Login Attempt Failed');

        // Notify Admin on Telegram for suspicious activity
        $email = $event->credentials['email'] ?? 'unknown';
        if (str_contains($email, 'admin')) {
            try {
                app(\App\Services\TelegramService::class)->sendAdminNotification("<b>🚨 فشل تسجيل دخول حساب إداري!</b>\n\n<b>البريد:</b> <code>{$email}</code>\n<b>IP:</b> <code>" . request()->ip() . "</code>");
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("AuthenticationSubscriber (Login Failed Alert): Telegram notification failed. Error: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle user lockout events.
     */
    public function handleUserLockout($event): void
    {
        $email = $event->request->email ?? 'unknown';
        activity('auth')
            ->withProperties([
                'email' => $email,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('User Account Locked Out');

        // Notify Admin on Telegram
        try {
            app(\App\Services\TelegramService::class)->sendAdminNotification("<b>🚫 تم قفل حساب مستخدم (Lockout)</b>\n\n<b>البريد:</b> <code>{$email}</code>\n<b>IP:</b> <code>" . request()->ip() . "</code>");
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("AuthenticationSubscriber (Lockout Alert): Telegram notification failed. Error: " . $e->getMessage());
        }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            Login::class,
            [AuthenticationSubscriber::class, 'handleUserLogin']
        );

        $events->listen(
            Logout::class,
            [AuthenticationSubscriber::class, 'handleUserLogout']
        );

        $events->listen(
            Failed::class,
            [AuthenticationSubscriber::class, 'handleUserLoginFailed']
        );

        $events->listen(
            Lockout::class,
            [AuthenticationSubscriber::class, 'handleUserLockout']
        );
    }
}
