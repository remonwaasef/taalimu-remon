<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class SyncUsersToGlobalRoles extends Command
{
    protected $signature = 'permissions:sync-to-global-roles';
    protected $description = 'Sync user role assignments to use global roles (tenant_id = null)';

    public function handle()
    {
        $this->info('Syncing users to global roles...');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Get all global roles
        $globalRoles = Role::whereNull('tenant_id')->get()->keyBy('name');
        
        if ($globalRoles->isEmpty()) {
            $this->error('No global roles found! Run: php artisan db:seed --class=RolesAndPermissionsSeeder');
            return 1;
        }

        $this->info("Found {$globalRoles->count()} global roles.");

        $updated = 0;

        foreach (User::cursor() as $user) {
            // Get current role assignments
            $currentAssignments = DB::table('model_has_roles')
                ->where('model_id', $user->id)
                ->where('model_type', User::class)
                ->get();

            foreach ($currentAssignments as $assignment) {
                // Get the role
                $oldRole = DB::table('roles')->where('id', $assignment->role_id)->first();
                
                if (!$oldRole) continue;

                // Find corresponding global role
                $globalRole = $globalRoles->get($oldRole->name);
                
                if ($globalRole && $oldRole->id != $globalRole->id) {
                    // Update to use global role
                    DB::table('model_has_roles')
                        ->where('role_id', $oldRole->id)
                        ->where('model_id', $user->id)
                        ->where('model_type', User::class)
                        ->update(['role_id' => $globalRole->id]);
                    
                    $this->line(" - User {$user->name}: switched '{$oldRole->name}' (ID: {$oldRole->id}) -> Global (ID: {$globalRole->id})");
                    $updated++;
                }
            }
        }

        // Also update tenant_id in model_has_roles to null for global roles
        DB::table('model_has_roles')
            ->whereIn('role_id', $globalRoles->pluck('id'))
            ->update(['tenant_id' => null]);

        $this->info("Updated {$updated} role assignments to use global roles.");
        $this->info('Clearing permission cache...');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $this->info('Done!');
        return 0;
    }
}
