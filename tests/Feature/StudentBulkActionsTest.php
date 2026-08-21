<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentBulkActionsTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = $this->createTenant(['domain' => 'bulk-test']);
        $this->user = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'center_admin',
        ]);

        $this->actingAs($this->user);
        app()->instance('tenant', $this->tenant);
    }

    public function test_bulk_status_change_updates_students()
    {
        $students = Student::factory()->count(3)->create([
            'tenant_id' => $this->tenant->id,
            'status' => 'active',
        ]);

        $response = $this->post(route('center.students.bulk-status', ['tenant' => $this->tenant->domain]), [
            'student_ids' => $students->pluck('id')->toArray(),
            'status' => 'frozen',
        ]);

        $response->assertStatus(302);

        foreach ($students as $student) {
            $this->assertDatabaseHas('students', [
                'id' => $student->id,
                'status' => 'frozen',
            ]);
        }
    }

    public function test_bulk_delete_soft_deletes_students()
    {
        $students = Student::factory()->count(3)->create([
            'tenant_id' => $this->tenant->id,
        ]);

        $response = $this->post(route('center.students.bulk-delete', ['tenant' => $this->tenant->domain]), [
            'student_ids' => $students->pluck('id')->toArray(),
        ]);

        $response->assertStatus(302);

        foreach ($students as $student) {
            $this->assertSoftDeleted('students', [
                'id' => $student->id,
            ]);
        }
    }

    public function test_bulk_export_returns_csv()
    {
        $students = Student::factory()->count(3)->create([
            'tenant_id' => $this->tenant->id,
        ]);

        $response = $this->post(route('center.students.bulk-export', ['tenant' => $this->tenant->domain]), [
            'student_ids' => $students->pluck('id')->toArray(),
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
    }
}
