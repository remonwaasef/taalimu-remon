<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class PromoteUserToAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:promote {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promote a user to Super Admin by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email {$email} not found.");

            return 1;
        }

        // Display user details for confirmation
        $this->table(
            ['ID', 'Name', 'Email', 'Current Role', 'Tenant ID'],
            [[$user->id, $user->name, $user->email, $user->role ?? 'N/A', $user->tenant_id ?? 'NULL']]
        );

        $this->warn('⚠️  This will promote the user to Super Admin with GLOBAL access (tenant_id = NULL).');

        if (! $this->confirm("Are you sure you want to promote '{$user->name}' ({$email}) to Super Admin?")) {
            $this->info('Operation cancelled.');

            return 0;
        }

        // Double confirmation for safety
        $confirmName = $this->ask('Type the user email to confirm');
        if ($confirmName !== $email) {
            $this->error('Email mismatch. Operation cancelled.');

            return 1;
        }

        $previousRole = $user->role;
        $previousTenantId = $user->tenant_id;

        // 1. Set the role column (used by AuthController and some legacy checks)
        $user->role = 'admin';
        $user->tenant_id = null; // Super Admins should be global
        $user->save();

        // 2. Ensure the Spatie role exists
        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // 3. Assign the Spatie role
        $user->assignRole($role);

        // 4. Reset cache to ensure immediate access
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // 5. Audit log
        \Illuminate\Support\Facades\Log::critical('ADMIN PROMOTION', [
            'promoted_user_id' => $user->id,
            'promoted_user_email' => $user->email,
            'previous_role' => $previousRole,
            'previous_tenant_id' => $previousTenantId,
            'promoted_by' => get_current_user().'@'.gethostname(),
            'timestamp' => now()->toIso8601String(),
        ]);

        $this->info("✅ User {$email} has been promoted to Super Admin successfully.");
        $this->warn('This action has been logged for auditing purposes.');

        return 0;
    }
}
