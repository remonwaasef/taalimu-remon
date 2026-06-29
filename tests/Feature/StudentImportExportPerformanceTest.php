<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Tenant;
use App\Models\Grade;
use App\Models\Stage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Services\StudentService;

class StudentImportExportPerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $admin;
    protected $grade;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup Tenant
        $this->tenant = Tenant::create(['domain' => 'test_school', 'name' => 'Test School']);
        app()->instance('tenant', $this->tenant);
        
        // Setup Admin
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => 'password',
            'tenant_id' => $this->tenant->id,
            'role' => 'admin'
        ]);
        
        // Setup Grade
        $stage = Stage::create(['name' => 'Primary', 'tenant_id' => $this->tenant->id]);
        $this->grade = Grade::create(['name' => 'Grade 1', 'stage_id' => $stage->id, 'tenant_id' => $this->tenant->id]);
    }

    public function test_bulk_import_logic_is_correct()
    {
        // Use the service container to properly resolve StudentService with all its dependencies
        $service = app(StudentService::class);
        
        $this->actingAs($this->admin);

        // Prepare CSV Data (10 students)
        $csvData = [];
        for ($i = 1; $i <= 10; $i++) {
            $csvData[] = [
                "Student $i",
                "student$i@example.com",
                "123456789$i",
                "Grade 1"
            ];
        }

        // Run Import
        $result = $service->importStudents($csvData);

        // Assertions
        $this->assertEquals(10, $result['success_count']);
        $this->assertEmpty($result['errors']);
        
        $this->assertDatabaseCount('users', 11); // 1 Admin + 10 Students
        $this->assertDatabaseCount('students', 10);
        
        // Precise check
        $this->assertDatabaseHas('students', [
            'email' => 'student1@example.com',
            'grade_id' => $this->grade->id
        ]);
    }

    public function test_import_handles_duplicates_efficiently()
    {
        $service = app(StudentService::class);
        
        $this->actingAs($this->admin);

        // Create one existing student
        User::create([
            'name' => 'Existing',
            'email' => 'student1@example.com',
            'password' => 'password',
            'tenant_id' => $this->tenant->id
        ]);

        $csvData = [
            ["New Student", "student2@example.com", "12345", "Grade 1"],
            ["Duplicate Student", "student1@example.com", "67890", "Grade 1"], // Should fail - email already exists
        ];

        $result = $service->importStudents($csvData);

        $this->assertEquals(1, $result['success_count']);
        $this->assertCount(1, $result['errors']);
        $this->assertStringContainsString('already exists', $result['errors'][0]);
    }

    public function test_export_query_optimization()
    {
        $service = app(StudentService::class);
        $this->actingAs($this->admin);

        // Create 20 students linked to grades
        Student::factory()->count(20)->create([
            'tenant_id' => $this->tenant->id,
            'grade_id' => $this->grade->id
        ]);

        DB::enableQueryLog();

        // Run Export
        $data = $service->getExportData();
        
        // Iterate to trigger the cursor
        foreach($data as $row) {
            // just iterate
        }

        $queries = DB::getQueryLog();
        
        // Expected Queries:
        // 1. Select Students
        // 2. Select Grades (Eager load)
        // 3. Select Stages (Nested Eager load)
        // Total should be constant (around 3), not 20+
        
        $this->assertLessThan(5, count($queries));
    }
}
