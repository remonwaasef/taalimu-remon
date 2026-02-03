<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Student;
use App\Models\Instructor;
use Illuminate\Support\Facades\DB;

class ExperimentalDataCleanupSeeder extends Seeder
{
    public function run()
    {
        $this->command->warn("Searching for experimental centers (prefix: exp-)...");

        $tenants = Tenant::where('domain', 'like', 'exp-%')->get();

        if ($tenants->isEmpty()) {
            $this->command->info("No experimental centers found.");
            return;
        }

        foreach ($tenants as $tenant) {
            $this->command->info("Cleaning up center: {$tenant->name} ({$tenant->domain})...");

            // Since we have a multi-tenant system with tenant_id in most models,
            // and we used the 'exp-' prefix for domains, we can clean up effectively.
            
            // Delete related users first (to avoid orphan records if soft deletes are used)
            User::where('tenant_id', $tenant->id)->delete();
            
            // Many other records (Students, Instructors, Courses, etc.) are linked via tenant_id.
            // We'll delete them explicitly to be safe, or rely on cascade if configured.
            // Given the complexity, let's target the main tables.
            
            DB::table('students')->where('tenant_id', $tenant->id)->delete();
            DB::table('instructors')->where('tenant_id', $tenant->id)->delete();
            DB::table('courses')->where('tenant_id', $tenant->id)->delete();
            DB::table('classrooms')->where('tenant_id', $tenant->id)->delete();
            DB::table('sales')->where('tenant_id', $tenant->id)->delete();
            DB::table('payments')->where('tenant_id', $tenant->id)->delete();
            DB::table('enrollments')->where('tenant_id', $tenant->id)->delete();
            DB::table('attendances')->where('tenant_id', $tenant->id)->delete();
            DB::table('subscriptions')->where('tenant_id', $tenant->id)->delete();
            
            // Finally delete the tenant
            $tenant->delete();
            
            $this->command->info("✅ Center {$tenant->domain} deleted successfully.");
        }

        $this->command->info("✅ All experimental data cleaned up!");
    }
}
