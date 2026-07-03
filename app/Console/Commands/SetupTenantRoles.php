<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class SetupTenantRoles extends Command
{
    protected $signature = 'permissions:setup-tenant-roles';

    protected $description = 'Create standard roles for all existing tenants';

    public function handle()
    {
        $this->info('Setting up roles for tenants...');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Get Global Roles (Center Admin, Instructor, etc)
        $globalRoles = Role::whereNull('tenant_id')
            ->where('name', '!=', 'super_admin')
            ->with('permissions') // Eager load permissions
            ->get();

        Tenant::chunk(50, function ($tenants) {
            foreach ($tenants as $tenant) {
                $this->info("Processing Tenant: {$tenant->domain} (ID: {$tenant->id})");

                // We no longer create per-tenant roles.
                // Instead, we just verify that the global roles map correctly if we were to assign them.
                // This command effectively becomes a check or we can deprecate its 'creation' logic.

                $this->line(' - Using Global Roles for this tenant. No local roles created.');
            }
        });

        $this->info('Tenant roles setup completed.');
    }
}
