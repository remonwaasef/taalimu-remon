<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Assignment;
use App\Models\Instructor;
use App\Models\Package;
use App\Models\Subscription;

class AssignmentSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $instructorUser;
    protected $instructorProfile;
    protected $student;
    protected $course;
    protected $section;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Tenant
        $this->tenant = $this->createTenant(['domain' => 'test', 'name' => 'Test Center']);
        app()->instance('tenant', $this->tenant);
        
        // Setup Subscription
        $package = Package::create([
            'name' => 'Pro Plan',
            'slug' => 'pro',
            'price' => 99,
            'duration_in_days' => 30,
            'stripe_price_id' => 'price_pro',
        ]);

        Subscription::create([
            'tenant_id' => $this->tenant->id,
            'stripe_price' => $package->stripe_price_id,
            'name' => 'main',
            'stripe_id' => 'sub_test',
            'stripe_status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
            'status' => 'active',
        ]);

        // Setup Spatie Team ID
        app()[\Spatie\Permission\PermissionRegistrar::class]->setPermissionsTeamId($this->tenant->id);

        // Setup Instructor Profile first so we can link it
        $this->instructorProfile = Instructor::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Dr. Instructor',
            'email' => 'instructor@test.com',
        ]);

        // Setup Roles and Permissions
        $role = \App\Models\Role::firstOrCreate(['name' => 'instructor', 'guard_name' => 'web']);
        $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'edit courses', 'guard_name' => 'web']);
        $role->givePermissionTo($permission);

        // Setup Users
        $this->instructorUser = User::factory()->create([
            'email' => 'instructor@test.com', 
            'tenant_id' => $this->tenant->id, 
            'role' => 'instructor',
            'instructor_id' => $this->instructorProfile->id
        ]);
        $this->instructorUser->assignRole($role);
        
        $this->student = User::factory()->create(['email' => 'student@test.com', 'tenant_id' => $this->tenant->id]);

        // Setup Course
        $this->course = Course::create([
            'tenant_id' => $this->tenant->id,
            'instructor_id' => $this->instructorProfile->id,
            'title' => 'Test Course',
            'slug' => 'test-course',
            'description' => 'Test Description',
            'price' => 100,
        ]);

        $this->section = $this->course->sections()->create(['title' => 'Section 1', 'sort_order' => 1]);
    }

    public function test_instructor_can_create_assignment()
    {
        $this->actingAs($this->instructorUser);

        $lesson = Lesson::create([
            'section_id' => $this->section->id,
            'title' => 'Assignment Lesson',
            'type' => 'text',
            'sort_order' => 1,
        ]);

        $response = $this->post(route('center.assignments.store', ['tenant' => $this->tenant->domain, 'lesson' => $lesson->id]), [
            'title' => 'My Assignment',
            'max_score' => 100,
            'due_date' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('assignments', ['title' => 'My Assignment', 'max_score' => 100]);
    }

    public function test_student_can_submit_assignment()
    {
        $this->actingAs($this->student);
        Storage::fake('local');

        $lesson = Lesson::create([
            'section_id' => $this->section->id,
            'title' => 'Assignment Lesson',
            'type' => 'text',
            'sort_order' => 1,
        ]);

        $assignment = Assignment::create([
            'lesson_id' => $lesson->id,
            'title' => 'My Assignment',
            'max_score' => 100,
        ]);

        $file = UploadedFile::fake()->create('homework.pdf', 100);

        $response = $this->post(route('center.assignments.submit', ['tenant' => $this->tenant->domain, 'assignment' => $assignment->id]), [
            'file' => $file,
        ]);

        $response->assertRedirect();
        
        // Assert file exists in storage
        $submission = $assignment->submissions()->where('user_id', $this->student->id)->first();
        $this->assertNotNull($submission);
        Storage::disk('local')->assertExists($submission->file_path);
    }

    public function test_instructor_can_grade_submission()
    {
        $this->actingAs($this->instructorUser);

        $lesson = Lesson::create([
            'section_id' => $this->section->id,
            'title' => 'Assignment Lesson',
            'type' => 'text',
            'sort_order' => 1,
        ]);

        $assignment = Assignment::create([
            'lesson_id' => $lesson->id,
            'title' => 'My Assignment',
            'max_score' => 100,
        ]);

        $submission = $assignment->submissions()->create([
            'user_id' => $this->student->id,
            'file_path' => 'dummy/path.pdf',
            'submitted_at' => now(),
        ]);

        $response = $this->post(route('center.assignments.grade', ['tenant' => $this->tenant->domain, 'submission' => $submission->id]), [
            'grade' => 90,
            'feedback' => 'Good job!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('assignment_submissions', [
            'id' => $submission->id,
            'grade' => 90,
            'feedback' => 'Good job!',
        ]);
    }

    public function test_secure_download_access()
    {
        Storage::fake('local');
        
        $lesson = Lesson::create([
            'section_id' => $this->section->id,
            'title' => 'Assignment Lesson',
            'type' => 'text',
            'sort_order' => 1,
        ]);

        $assignment = Assignment::create([
            'lesson_id' => $lesson->id,
            'title' => 'My Assignment',
            'max_score' => 100,
        ]);

        // Create a file
        $file = UploadedFile::fake()->create('homework.pdf', 100);
        $path = $file->store('assignments/' . $assignment->id);

        $submission = $assignment->submissions()->create([
            'user_id' => $this->student->id,
            'file_path' => $path,
            'submitted_at' => now(),
        ]);

        $this->actingAs($this->student);
        $response = $this->get(route('center.assignments.download', ['tenant' => $this->tenant->domain, 'submission' => $submission->id]));
        $response->assertStatus(200);

        // 2. Instructor can download
        $this->actingAs($this->instructorUser);
        $response = $this->get(route('center.assignments.download', ['tenant' => $this->tenant->domain, 'submission' => $submission->id]));
        $response->assertStatus(200);

        // 3. Another Student cannot download
        $otherStudent = User::factory()->create(['email' => 'other@test.com', 'tenant_id' => $this->tenant->id]);
        $this->actingAs($otherStudent);
        $response = $this->get(route('center.assignments.download', ['tenant' => $this->tenant->domain, 'submission' => $submission->id]));
        $response->assertStatus(403);
    }
}
