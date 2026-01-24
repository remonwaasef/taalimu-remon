<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GlobalRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_standard_roles_are_global_and_not_duplicated()
    {
        // 1. Run the seeder to create roles
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        // 2. Assert Global Roles Exist
        $roles = ['center_admin', 'instructor', 'student', 'secretary'];
        foreach ($roles as $roleName) {
            $this->assertDatabaseHas('roles', [
                'name' => $roleName,
                'tenant_id' => null,
                'guard_name' => 'web'
            ]);
            
            // Assert NO tenant-specific role exists yet
            $this->assertDatabaseMissing('roles', [
                'name' => $roleName,
                'tenant_id' => 1, // Hypothetical tenant ID
            ]);
        }
    }

    public function test_users_in_different_tenants_share_same_global_role()
    {
        // 1. Setup
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
        
        $tenant1 = Tenant::create(['domain' => 't1.test', 'name' => 'Tenant 1']);
        $tenant2 = Tenant::create(['domain' => 't2.test', 'name' => 'Tenant 2']);

        // 2. Create Users in different tenants
        $user1 = User::create([
            'name' => 'User 1',
            'email' => 'u1@test.com',
            'password' => 'password',
            'tenant_id' => $tenant1->id
        ]);
        
        $user2 = User::create([
            'name' => 'User 2',
            'email' => 'u2@test.com',
            'password' => 'password',
            'tenant_id' => $tenant2->id
        ]);

        // 3. Assign Roles using helper that should check global scope
        // We simulate what specific seeders or controllers do
        setPermissionsTeamId($tenant1->id);
        $studentRole = Role::where('name', 'student')->whereNull('tenant_id')->first();
        $user1->assignRole($studentRole);

        setPermissionsTeamId($tenant2->id);
        $user2->assignRole($studentRole);

        // 4. Assertions
        
        // Context: Tenant 1
        setPermissionsTeamId($tenant1->id);
        $this->assertTrue($user1->hasRole('student'), 'User 1 should have student role in Tenant 1');
        
        // Context: Tenant 2
        setPermissionsTeamId($tenant2->id);
        $this->assertTrue($user2->hasRole('student'), 'User 2 should have student role in Tenant 2');

        // Check validation directly in pivot table
        $this->assertDatabaseHas('model_has_roles', [
            'model_id' => $user1->id,
            'role_id' => $studentRole->id,
            'tenant_id' => $tenant1->id // Spatie saves team_id in pivot
        ]);

        $this->assertDatabaseHas('model_has_roles', [
            'model_id' => $user2->id,
            'role_id' => $studentRole->id,
            'tenant_id' => $tenant2->id
        ]);

        // CRITICAL: Ensure NO new role was created
        $this->assertEquals(1, Role::where('name', 'student')->count(), 'Should only be ONE student role in the entire system');
    }
}
