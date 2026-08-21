<?php

namespace Tests\Feature;

use App\Models\Grade;
use App\Models\Stage;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentBulkActionsTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;
    protected $grade;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = $this->createTenant(['domain' => 'bulk-test']);
        app()->instance('tenant', $this->tenant);

        $stage = Stage::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Stage 1',
            'order' => 1,
        ]);
        $this->grade = Grade::create([
            'tenant_id' => $this->tenant->id,
            'stage_id' => $stage->id,
            'name' => 'Grade 1',
            'order' => 1,
        ]);

        $this->user = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'center_admin',
        ]);

        $this->actingAs($this->user);
    }

    private function createStudents(int $count, array $attributes = [])
    {
        return collect(range(1, $count))->map(function ($i) use ($attributes) {
            $user = User::factory()->create([
                'tenant_id' => $this->tenant->id,
                'role' => 'student',
            ]);

            return Student::factory()->create(array_merge([
                'tenant_id' => $this->tenant->id,
                'user_id' => $user->id,
                'grade_id' => $this->grade->id,
            ], $attributes));
        });
    }

    public function test_bulk_status_change_updates_students()
    {
        $students = $this->createStudents(3, ['status' => 'active']);

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
        $students = $this->createStudents(3);

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
        $students = $this->createStudents(3);

        $response = $this->post(route('center.students.bulk-export', ['tenant' => $this->tenant->domain]), [
            'student_ids' => $students->pluck('id')->toArray(),
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('Content-Type'));
    }
}
