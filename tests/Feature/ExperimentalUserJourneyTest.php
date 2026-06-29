<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExperimentalUserJourneyTest extends TestCase
{
    // No RefreshDatabase here because we want to test existing experimental data

    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        // Override the testing environment to use the real database
        // We MUST do this before the application is fully booted if possible, 
        // or just rely on .env if we run with specific flags.
        // Easier way: Use .env.testing or just set config at runtime.
        
        return $app;
    }
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Force config AFTER parent setup to ensure it overrides everything
        config(['database.default' => 'mysql']);
        config(['database.connections.mysql.database' => 'u548254849_taalimu']);
        
        // Ensure no other connection is used
        config(['database.connections.sqlite.database' => 'u548254849_taalimu']); 
    }

    public function test_experimental_data_user_journey()
    {
        // This test requires a real MySQL database with pre-seeded experimental data.
        // Skip if the MySQL host ('db') is not reachable.
        try {
            \DB::connection('mysql')->getPdo();
        } catch (\Exception $e) {
            $this->markTestSkipped('MySQL database is not available. Skipping experimental user journey test.');
        }

        // 1. Identify the 'exp-smart-center' tenant
        $tenant = Tenant::where('domain', 'exp-smart-center')->first();
        if (!$tenant) {
            $this->fail("The experimental center 'exp-smart-center' was not found. Please run the seeder.");
        }

        // 2. Identify the Admin User
        $admin = User::where('email', "admin@exp-smart-center.com")->where('tenant_id', $tenant->id)->first();
        if (!$admin) {
            $this->fail("The admin user for 'exp-smart-center' was not found.");
        }

        $this->actingAs($admin);

        // 3. User Journey: Visit Dashboard
        $response = $this->get("http://exp-smart-center.localhost:8000/dashboard");
        $response->assertStatus(200);
        // Obstacle Check: Is dashboard showing core elements?
        $response->assertSee("مركز الذكاء التعليمي");
        
        // 4. User Journey: Visit Students Page (Obstacle: Pagination, Empty State?)
        $response = $this->get("http://exp-smart-center.localhost:8000/students");
        $response->assertStatus(200);
        $response->assertSee("Students"); // Assuming English fallback or Arabic translation key
        // Verify at least one student is listed (latest one to ensure it's on page 1)
        $studentName = User::where('tenant_id', $tenant->id)->where('role', 'student')->latest()->first()->name;
        $response->assertSee($studentName);

        // 5. User Journey: Visit Instructors Page
        $response = $this->get("http://exp-smart-center.localhost:8000/instructors");
        $response->assertStatus(200);
        
        // 6. User Journey: Visit Courses Page
        $response = $this->get("http://exp-smart-center.localhost:8000/courses");
        $response->assertStatus(200);

        // 7. Simulating deeper interaction: View a specific student profile
        $student = \App\Models\Student::where('tenant_id', $tenant->id)->first();
        if ($student) {
            $response = $this->get("http://exp-smart-center.localhost:8000/students/" . $student->id);
            // Obstacle Check: 500 error on profile page?
            if ($response->status() !== 200) {
                 $this->fail("Obstacle Found! Visiting student profile returned status " . $response->status());
            }
            $response->assertStatus(200);
            $response->assertSee($student->name);
        }
    }
}
