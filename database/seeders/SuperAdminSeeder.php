<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'tenant_id' => null, // Super admin doesn't belong to a tenant
            ]
        );

        // Assign Spatie role if needed, though AuthController checks the column
        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('super_admin');
        }
    }
}
