<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Dispatch job to sync user to Klaviyo
        if (config('services.klaviyo.key')) {
            \App\Jobs\SyncUserToKlaviyo::dispatch($user);
        }

        // Keep Spatie roles in sync with the legacy `role` column so the two
        // role systems can never drift apart (Spatie is the source of truth
        // for permissions; the column is kept as a fast read-only shortcut).
        $this->syncSpatieRole($user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if ($user->wasChanged('role')) {
            $this->syncSpatieRole($user, $user->getOriginal('role'));
        }
    }

    /**
     * Mirror the `role` column into Spatie permissions (team-scoped).
     * Mirrors the mapping used by the users:sync-roles command.
     */
    private function syncSpatieRole(User $user, ?string $previousRole = null): void
    {
        if (! $user->role) {
            return;
        }

        try {
            $registrar = app(\Spatie\Permission\PermissionRegistrar::class);
            $originalTeamId = $registrar->getPermissionsTeamId();
            $registrar->setPermissionsTeamId($user->tenant_id);

            try {
                $roleName = ($user->role === 'admin' && $user->tenant_id === null) ? 'super_admin' : $user->role;

                if (\Spatie\Permission\Models\Role::where('name', $roleName)->exists()) {
                    if ($previousRole && $previousRole !== $roleName && $user->hasRole($previousRole)) {
                        $user->removeRole($previousRole);
                    }
                    if (! $user->hasRole($roleName)) {
                        $user->assignRole($roleName);
                    }
                }
            } finally {
                $registrar->setPermissionsTeamId($originalTeamId);
            }
        } catch (\Throwable $e) {
            // Never let role mirroring break a user save.
            \Illuminate\Support\Facades\Log::warning('UserObserver: role sync failed for user '.$user->id.': '.$e->getMessage());
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
