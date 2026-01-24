<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class SwitchToTenantRoles extends Command
{
    protected $signature = 'permissions:switch-to-tenant-roles';
    protected $description = 'Switch user assignments from global roles to tenant-specific roles';

    public function handle()
    {
        $this->info('Switching users to tenant roles...');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $users = User::whereNotNull('tenant_id')->get();

        foreach ($users as $user) {
            $this->info("Processing User: {$user->name} (Tenant: {$user->tenant_id})");
            
            // Get current roles (raw query to avoid scope filtering)
            $assignedRoles = DB::table('model_has_roles')
                ->where('model_id', $user->id)
                ->where('model_type', User::class)
                ->get();
                
            foreach ($assignedRoles as $assignment) {
                // Find global role name
                $globalRole = DB::table('roles')->where('id', $assignment->role_id)->first();
                
                if (!$globalRole) continue;
                
                // Find equivalent tenant role
                $tenantRole = Role::where('name', $globalRole->name)
                    ->where('tenant_id', $user->tenant_id)
                    ->first();
                    
                if ($tenantRole && $tenantRole->id != $globalRole->id) {
                    $this->line(" - Switching role '{$globalRole->name}' from Global ID {$globalRole->id} to Tenant ID {$tenantRole->id}");
                    
                    // Update valid pivot directly
                    // Note: We update instead of attach/detach to preserve extra Pivot data if any (e.g. valid_until)
                    // And to avoid unique constraint issues if any.
                    DB::table('model_has_roles')
                        ->where('role_id', $globalRole->id)
                        ->where('model_id', $user->id)
                        ->where('model_type', User::class)
                        ->update(['role_id' => $tenantRole->id]);
                } else {
                    $this->line(" - Role '{$globalRole->name}' already correct or no tenant equivalent found.");
                }
            }
        }
        
        $this->info('Role switching completed.');
    }
}
