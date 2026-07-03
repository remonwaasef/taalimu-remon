<?php

namespace Tests\Feature;

use App\Models\Grade;
use App\Models\Stage;
use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use App\Services\StudentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\LazyCollection;
use Tests\TestCase;

class StudentExportTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $admin;

    protected $studentService;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Tenant
        $this->tenant = Tenant::create(['domain' => 'test_export', 'name' => 'Test Export Center']);
        app()->instance('tenant', $this->tenant);

        // Setup Service
        $this->studentService = app(StudentService::class);

        // Setup common data
        $stage = Stage::create(['name' => 'Stage 1', 'tenant_id' => $this->tenant->id]);
        $grade = Grade::create(['name' => 'Grade 1', 'stage_id' => $stage->id, 'tenant_id' => $this->tenant->id]);

        // Create 20 students
        Student::factory()->count(20)->create([
            'tenant_id' => $this->tenant->id,
            'grade_id' => $grade->id,
            'grade_level' => '1',
            'status' => 'active',
        ]);
    }

    /** @test */
    public function it_can_retrieve_export_data_as_lazy_collection()
    {
        $data = $this->studentService->getExportData();

        $this->assertInstanceOf(LazyCollection::class, $data);
        $this->assertCount(20, $data);

        // Check structure of first item
        $firstItem = $data->first();
        $this->assertIsArray($firstItem);
        $this->assertCount(8, $firstItem); // ID, Name, Email, Phone, Grade, School, Section, Status
    }

    /** @test */
    public function it_exports_data_correctly()
    {
        // Mock authentication
        $user = User::factory()->create(['tenant_id' => $this->tenant->id]);
        $this->actingAs($user);

        // Mock permission if needed, but StudentService::getExportData()
        // doesn't check permissions itself, only the controller does.
        // For service test, we don't need to mock tenant features.

        // Manually trigger the controller method logic (simulated) or route if possible.
        // For unit testing the service, we focus on the service method return.

        $data = $this->studentService->getExportData();
        $arrayData = $data->toArray();

        $student = Student::first();

        // Find the student in the export data
        $exportedStudent = collect($arrayData)->first(function ($item) use ($student) {
            return $item[0] == $student->id;
        });

        $this->assertNotNull($exportedStudent);
        $this->assertEquals($student->name, $exportedStudent[1]);
        $this->assertEquals($student->email, $exportedStudent[2]);
    }
}
