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
    }

    /**
     * Handle user lockout events.
     */
    public function handleUserLockout($event): void
    {
        activity('auth')
            ->withProperties([
                'email' => $event->request->email ?? 'unknown',
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('User Account Locked Out');
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
