<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignRolesToUsers extends Command
{
    protected $signature = 'permissions:assign-roles';

    protected $description = 'Assign Spatie roles to users based on their role column';

    public function handle()
    {
        $this->info('Assigning roles to users...');

        // Clear permissions cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Set team ID to null for global roles
        setPermissionsTeamId(null);

        $count = 0;

        foreach (User::cursor() as $user) {
            $roleName = $user->role;

            if (empty($roleName)) {
                $this->line("Skipping user {$user->name} - no role set");

                continue;
            }

            // Map legacy role names if needed
            $roleMapping = [
                'admin' => 'center_admin',
                'super_admin' => 'super_admin',
                'center_admin' => 'center_admin',
                'instructor' => 'instructor',
                'student' => 'student',
                'secretary' => 'secretary',
                'accountant' => 'accountant',
                'staff' => 'staff',
            ];

            $mappedRole = $roleMapping[$roleName] ?? $roleName;

            // Find global role
            $role = Role::where('name', $mappedRole)
                ->whereNull('tenant_id')
                ->first();

            if (! $role) {
                $this->warn("Role '{$mappedRole}' not found for user {$user->name}");

                continue;
            }

            // Check if user already has this role
            if (! $user->hasRole($mappedRole)) {
                // Remove any existing roles and assign the correct one
                $user->syncRoles([$role]);
                $this->info("Assigned role '{$mappedRole}' to user {$user->name}");
                $count++;
            } else {
                $this->line("User {$user->name} already has role '{$mappedRole}'");
            }
        }

        $this->info("Done! Assigned roles to {$count} users.");

        // Clear cache again
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return 0;
    }
}
