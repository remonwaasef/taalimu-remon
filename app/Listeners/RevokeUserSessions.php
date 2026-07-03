<?php

namespace App\Listeners;

use App\Events\UserRoleChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RevokeUserSessions implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRoleChanged $event): void
    {
        $user = $event->user;

        \Illuminate\Support\Facades\Log::warning("SECURITY: Role Changed for User Use ID: {$user->id}. Initiating Session Kill Switch.");

        // 1. Revoke all Sanctum/Passport Tokens
        if (method_exists($user, 'tokens')) {
            $user->tokens()->delete();
        }

        // 2. Kill Web Sessions (Database Driver)
        try {
            \Illuminate\Support\Facades\DB::table('sessions')
                ->where('user_id', $user->id)
                ->delete();
        } catch (\Exception $e) {
            // Ignore if sessions table doesn't exist or using another driver
            \Illuminate\Support\Facades\Log::warning('Could not clear database sessions: '.$e->getMessage());
        }

        // 3. Invalidate specific caches if keyed by user ID
        // Cache::forget('user_permissions_' . $user->id);
    }
}
