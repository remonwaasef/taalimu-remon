<?php

namespace Tests\Unit\Services;

use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use App\Services\AdminNotificationService;
use App\Services\StudentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StudentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $studentService;

    protected $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        // Create tenant
        $this->tenant = Tenant::create([
            'name' => 'Test Center',
            'domain' => 'test-center',
            'email' => 'admin@test.com',
        ]);

        // Bind tenant
        app()->instance('tenant', $this->tenant);

        // Use Laravel container to resolve StudentService with all new dependencies
        $this->studentService = app(StudentService::class);

        // Mock the notification service if it's still used internally by one of the sub-services
        $notificationService = Mockery::mock(AdminNotificationService::class);
        $notificationService->shouldReceive('notifyAdmins')->andReturn(true);
        app()->instance(AdminNotificationService::class, $notificationService);
    }

    #[Test]
    public function it_generates_unique_email_for_new_students()
    {
        // Create first student with proper hash
        $user1 = User::create([
            'name' => 'Student 1',
            'email' => 'student1@local.edu',
            'password' => Hash::make('password'),
            'role' => 'student',
            'tenant_id' => $this->tenant->id,
        ]);

        // Use reflection to test protected method
        $reflection = new \ReflectionClass($this->studentService);
        $method = $reflection->getMethod('generateUniqueEmail');
        $method->setAccessible(true);

        $email = $method->invoke($this->studentService);

        $this->assertEquals('std2.test-center@taalimu.com', $email);
    }

    #[Test]
    public function it_exports_data_using_lazy_collection()
    {
        // Create test students
        for ($i = 1; $i <= 5; $i++) {
            Student::create([
                'name' => "Student {$i}",
                'email' => "student{$i}@test.com",
                'phone' => "0100000000{$i}",
                'tenant_id' => $this->tenant->id,
                'status' => 'active',
            ]);
        }

        $exportData = $this->studentService->getExportData();

        // Verify it's a lazy collection (Generator-based)
        $this->assertInstanceOf(\Illuminate\Support\LazyCollection::class, $exportData);

        // Verify correct data count
        $this->assertEquals(5, $exportData->count());
    }

    #[Test]
    public function it_validates_csv_injection_in_import()
    {
        $creator = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'tenant_id' => $this->tenant->id,
        ]);

        auth()->login($creator);

        // Attempt to import with dangerous characters
        $csvData = [
            ['=HYPERLINK("evil.com")', 'test@test.com', '01000000001', 'Grade 1'],
            ['+IMPORT()', 'test2@test.com', '01000000002', 'Grade 2'],
            ['@SUM(1+1)', 'test3@test.com', '01000000003', 'Grade 3'],
        ];

        $result = $this->studentService->importStudents($csvData);

        // All should be rejected due to CSV injection patterns
        $this->assertEquals(0, $result['success_count']);
        $this->assertCount(3, $result['errors']);

        foreach ($result['errors'] as $error) {
            $this->assertStringContainsString('Unsafe characters', $error);
        }
    }

    #[Test]
    public function it_handles_bulk_import_with_batch_processing()
    {
        $creator = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'tenant_id' => $this->tenant->id,
        ]);

        auth()->login($creator);

        // Create test data
        $csvData = [];
        for ($i = 1; $i <= 10; $i++) {
            $csvData[] = ["Student {$i}", "bulk{$i}@test.com", "0100000000{$i}", null];
        }

        $result = $this->studentService->importStudents($csvData);

        $this->assertEquals(10, $result['success_count']);
        $this->assertEmpty($result['errors']);

        // Verify students were created
        $this->assertEquals(10, Student::count());
        $this->assertEquals(11, User::count()); // 10 students + 1 admin
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
