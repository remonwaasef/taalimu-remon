<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
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

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

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

        $this->info("User {$email} has been promoted to Super Admin successfully.");
        return 0;
    }
}
