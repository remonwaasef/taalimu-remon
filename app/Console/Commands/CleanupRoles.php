<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class CleanupRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge tenant-specific role clones into global roles and cleanup duplicates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $systemRoles = ['super_admin', 'center_admin', 'instructor', 'student', 'secretary', 'accountant', 'staff'];
        
        $this->info('Starting Role Cleanup...');

        // --- STEP 1: Fix Orphaned Assignments (Zombies) ---
        // These are model_has_roles entries where the role_id no longer exists in roles table
        // This happens if RolesAndPermissionsSeeder deleted tenant roles but didn't reassign users.
        $this->info('Checking for orphaned role assignments (Zombie links)...');
        $orphans = DB::table('model_has_roles')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('roles')
                    ->whereColumn('roles.id', 'model_has_roles.role_id');
            })
            ->get();

        $orphanedFixedCount = 0;
        foreach ($orphans as $orphan) {
            // Find user's intended role from the 'role' column in users table
            $user = DB::table('users')->where('id', $orphan->model_id)->first();
            if ($user && $user->role) {
                // Find the new global role that replaced the deleted tenant-specific one
                $globalRole = Role::where('name', $user->role)->whereNull('tenant_id')->first();
                if ($globalRole) {
                    DB::table('model_has_roles')
                        ->where('model_id', $orphan->model_id)
                        ->where('role_id', $orphan->role_id)
                        ->update(['role_id' => $globalRole->id]);
                    $orphanedFixedCount++;
                }
            }
        }
        if ($orphanedFixedCount > 0) {
            $this->info("Fixed {$orphanedFixedCount} orphaned role assignments.");
        }

        // --- STEP 2: Merge Existing Duplicate Roles ---
        $duplicates = Role::whereIn('name', $systemRoles)
            ->whereNotNull('tenant_id')
            ->get();

        if ($duplicates->isEmpty() && $orphanedFixedCount === 0) {
            $this->info('No duplicate or orphaned roles found.');
            return;
        }

        if (!$duplicates->isEmpty()) {
            $this->info("Found {$duplicates->count()} duplicate roles. Starting merge...");
        }

        foreach ($duplicates as $duplicate) {
            $globalRole = Role::where('name', $duplicate->name)
                ->whereNull('tenant_id')
                ->first();

            if (!$globalRole) {
                $this->warn("Global role '{$duplicate->name}' not found. Skipping merge for this role.");
                continue;
            }

            $this->line("Merging duplicate '{$duplicate->name}' (Tenant: {$duplicate->tenant_id}) into global role...");

            // 1. Reassign users in model_has_roles
            DB::table('model_has_roles')
                ->where('role_id', $duplicate->id)
                ->update(['role_id' => $globalRole->id]);

            // 1.1 Update users table role column for consistency
            DB::table('users')
                ->where('role', $duplicate->name)
                ->where('tenant_id', $duplicate->tenant_id) // Be specific to avoid cross-tenant issues if role names match
                ->update(['role' => $globalRole->name]);

            // 2. Reassign permissions in role_has_permissions (optional, but safer to keep global permissions)
            // Note: In our current architecture, global roles should be the source of truth.
            
            // 3. Delete the duplicate role
            $duplicate->delete();
        }

        $this->info('Role Cleanup completed successfully.');
    }
}
