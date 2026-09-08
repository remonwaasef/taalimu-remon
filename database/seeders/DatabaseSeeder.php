<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RolesAndPermissionsSeeder::class,
            \Database\Seeders\CenterRolesSeeder::class, // Ensure Center Roles are seeded
            \Modules\Admin\Database\Seeders\AdminDatabaseSeeder::class, // Admin Panel Specific Roles
            SuperAdminSeeder::class,
            PackageSeeder::class,
            GrowthFeaturesSeeder::class,
            FullSystemDemoSeeder::class,
        ]);
    }
}
