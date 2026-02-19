<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class ForceFixRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:force-fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Definitively fix user role assignments by syncing the user table role column to Spatie roles for their tenant';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('--- Starting Force Fix Roles ---');

        // 1. Ensure Global Roles exist by running the seeder
        $this->info('Step 1: Running RolesAndPermissionsSeeder...');
        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder', '--force' => true]);
        $this->info(Artisan::output());

        // 2. Clear Spatie Cache
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // 3. Loop through all users and ensure their Spatie role matches their table 'role'
        $users = User::all();
        $this->info("Step 2: Syncing roles for {$users->count()} users...");

        // Map legacy/common role names to the ones in our seeder
        $roleMapping = [
            'admin' => 'super_admin',
            'administrator' => 'super_admin',
            // Add other mappings if discovered
        ];

        $fixedCount = 0;
        foreach ($users as $user) {
            if (!$user->role) {
                continue;
            }

            // Normalization & Mapping
            $roleName = strtolower($user->role);
            if (isset($roleMapping[$roleName])) {
                $roleName = $roleMapping[$roleName];
            }
            
            // Set Spatie Team context to the user's tenant
            if ($user->tenant_id) {
                app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($user->tenant_id);
            } else {
                app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(null);
            }

            // Check if role exists before assigning
            $roleExists = Role::where('name', $roleName)->exists();
            if (!$roleExists) {
                $this->warn("Role '{$roleName}' does not exist in guard 'web'. Skipping user {$user->email}.");
                continue;
            }

            // Check if user already has the role in Spatie for this tenant
            if (!$user->hasRole($roleName)) {
                $this->line("Fixing user {$user->email}: Assigning role '{$roleName}' for tenant " . ($user->tenant_id ?? 'GLOBAL'));
                
                // Assign role
                $user->assignRole($roleName);
                $fixedCount++;
            }
        }

        // 4. Final Cleanup
        $this->info('Step 3: Resetting permission cache...');
        Artisan::call('permission:cache-reset');
        
        $this->info("Success! Fixed assignments for {$fixedCount} users.");
        $this->info('--- Force Fix Completed ---');
    }
}
