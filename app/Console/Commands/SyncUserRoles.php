<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class SyncUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:sync-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Spatie roles based on the role column in users table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting role synchronization...');

        User::chunk(100, function ($users) use (&$count) {
            foreach ($users as $user) {
                if (! $user->role) {
                    continue;
                }

                // Map database roles to Spatie roles if names differ, otherwise use direct mapping
                // In CenterRolesSeeder: 'center_admin', 'secretary', 'accountant', 'staff'
                // In SuperAdminSeeder: 'super_admin' (via custom logic)

                $roleName = $user->role;

                // Handle special mapping if needed
                if ($roleName === 'admin' && $user->tenant_id === null) {
                    $roleName = 'super_admin';
                }

                // Check if role exists in the guard
                $role = Role::where('name', $roleName)->first();

                if ($role) {
                    if (! $user->hasRole($roleName)) {
                        $user->assignRole($roleName);
                        $this->info("Assigned role [$roleName] to user: {$user->email}");
                        $count++;
                    }
                } else {
                    $this->warn("Role [$roleName] not found for user: {$user->email}");
                }
            }
        });

        $this->info("Synchronization complete. Updated $count users.");
    }
}
