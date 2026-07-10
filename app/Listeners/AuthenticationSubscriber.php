<?php

namespace App\Listeners;

use App\Services\TelegramService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Models\Activity;

class AuthenticationSubscriber
{
    public function __construct(
        protected TelegramService $telegramService
    ) {}

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

        if ($event->user->role === 'super_admin' || $event->user->email === config('app.admin_email', 'admin@taalimu.com')) {
            try {
                $this->telegramService->sendLoginAlert($event->user, request()->ip());
            } catch (\Throwable $e) {
                Log::warning('AuthenticationSubscriber (Login Alert): Telegram notification failed. Error: '.$e->getMessage());
            }
        }
    }

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

    public function handleUserLoginFailed($event): void
    {
        activity('auth')
            ->withProperties([
                'email' => $event->credentials['email'] ?? 'unknown',
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Login Attempt Failed');

        $email = $event->credentials['email'] ?? 'unknown';
        if (str_contains($email, 'admin')) {
            try {
                $this->telegramService->sendAdminNotification("<b>🚨 فشل تسجيل دخول حساب إداري!</b>\n\n<b>البريد:</b> <code>{$email}</code>\n<b>IP:</b> <code>".request()->ip().'</code>');
            } catch (\Throwable $e) {
                Log::warning('AuthenticationSubscriber (Login Failed Alert): Telegram notification failed. Error: '.$e->getMessage());
            }
        }
    }

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

        try {
            $this->telegramService->sendAdminNotification("<b>🚫 تم قفل حساب مستخدم (Lockout)</b>\n\n<b>البريد:</b> <code>{$email}</code>\n<b>IP:</b> <code>".request()->ip().'</code>');
        } catch (\Throwable $e) {
            Log::warning('AuthenticationSubscriber (Lockout Alert): Telegram notification failed. Error: '.$e->getMessage());
        }
    }

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
