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

        $duplicates = Role::whereIn('name', $systemRoles)
            ->whereNotNull('tenant_id')
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('No duplicate roles found.');
            return;
        }

        $this->info("Found {$duplicates->count()} duplicate roles. Starting merge...");

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
