<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MigrateTeamsPermissions extends Command
{
    protected $signature = 'permissions:migrate-teams';
    protected $description = 'Migrate existing role assignments to support Teams Mode (populate tenant_id)';

    public function handle()
    {
        $this->info('Starting Teams Mode migration...');

        $users = User::whereNotNull('tenant_id')->get();

        $count = 0;
        foreach ($users as $user) {
            // Update model_has_roles table directly to set tenant_id
            $updated = DB::table('model_has_roles')
                ->where('model_id', $user->id)
                ->where('model_type', User::class)
                ->update(['tenant_id' => $user->tenant_id]);

            if ($updated) {
                $count++;
                $this->line("Updated roles for user: {$user->name} (Tenant: {$user->tenant_id})");
            }
        }
        
        // Also update roles table? 
        // No, roles themselves are usually global in this setup (defined in seeder), 
        // OR if roles are tenant-specific, they need tenant_id.
        // In our seeder, we checked "guard_name" but we didn't set "tenant_id" for the roles definitions themselves.
        // If roles are GLOBAL (shared across tenants like "center_admin"), they should have tenant_id = NULL.
        // If roles are CUSTOM per tenant, they have tenant_id.
        // Spatie Teams mode usually expects the assignment to have the team_id.
        
        // Let's ensure global roles have NULL tenant_id (which they should already)
        
        $this->info("Migration completed. Updated {$count} users.");
    }
}
