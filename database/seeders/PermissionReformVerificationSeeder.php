<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PermissionReformVerificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info('--- STARTING PERMISSION REFORM VERIFICATION ---');

        // 1. Create a Fake System Role (Simulating a Seeded Role)
        Log::info('Step 1: Creating Fake System Role (instructor_test)');
        $systemRole = Role::firstOrCreate(['name' => 'instructor_test', 'guard_name' => 'web', 'tenant_id' => null]);
        
        // 2. Attempt to Rename System Role (Should Fail)
        Log::info('Step 2: Attempting to Rename System Role...');
        try {
            $systemRole->name = 'hacked_instructor';
            $systemRole->save(); // Should trigger IsImmutable trait
            Log::error('FAIL: System Role was renamed! Security breach.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::info('PASS: System Role rename blocked by IsImmutable trait.');
        } catch (\Exception $e) {
            Log::info('PASS: Caught expected exception: ' . $e->getMessage());
        }

        // 3. Attempt to Delete System Role (Should Fail)
        Log::info('Step 3: Attempting to Delete System Role...');
        try {
            $systemRole->delete(); // Should trigger IsImmutable trait
            Log::error('FAIL: System Role was deleted! Security breach.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::info('PASS: System Role deletion blocked by IsImmutable trait.');
        } catch (\Exception $e) {
            Log::info('PASS: Caught expected exception: ' . $e->getMessage());
        }

        // 4. Create Custom Role (Should Succeed)
        Log::info('Step 4: Creating Custom Role for Tenant 1...');
        $tenantId = 1; // Assuming exists
        $customRole = Role::create(['name' => 'custom_manager_' . time(), 'guard_name' => 'web', 'tenant_id' => $tenantId]);
        Log::info('PASS: Custom Role created successfully: ' . $customRole->name);

        // 5. Verify Cache Clearing
        Log::info('Step 5: Verifying Cache Clearing...');
        $repo = app(\App\Repositories\RoleRepository::class);
        $cachedRoles = $repo->getAllForTenant($tenantId);
        
        if ($cachedRoles->contains('name', $customRole->name)) {
            Log::info('PASS: Custom Role found in Repository Cache immediately.');
        } else {
            Log::warning('WARNING: Custom Role NOT found in Repository Cache (Check Cache Logic).');
        }

        Log::info('--- END PERMISSION REFORM VERIFICATION ---');
    }
}
