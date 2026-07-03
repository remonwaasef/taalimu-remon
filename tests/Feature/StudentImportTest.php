<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class StudentImportTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Tenant & Bind
        $this->tenant = Tenant::create(['domain' => 'test', 'name' => 'Test Center', 'status' => 'active', 'onboarding_status' => 'completed']);
        app()->instance('tenant', $this->tenant);
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($this->tenant->id);

        // Mock SubscriptionService if necessary, or ensure defaults work
        // For now, let's assume the tenant has 'max_students' feature by default or we need to add it to settings
        $this->tenant->update(['settings' => ['features' => ['max_students' => true]]]);

        // Setup Permissions
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'view students', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'create students', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'edit students', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'delete students', 'guard_name' => 'web']);

        // Setup Subscription
        $package = Package::create([
            'name' => 'Pro Plan',
            'slug' => 'pro',
            'stripe_price_id' => 'price_pro',
            'price' => 99,
            'duration_in_days' => 30,
        ]);

        $feature = \App\Models\Feature::firstOrCreate([
            'code' => 'max_students',
        ], [
            'name' => 'Max Students',
            'type' => 'limit',
            'value' => 100, // Ensure value is set in DB if needed (though pivot handles it)
        ]);

        // Fix: attach to package with explicit value
        $package->features()->attach($feature->id, ['value' => 100]);

        Subscription::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'main',
            'stripe_id' => 'sub_test',
            'stripe_status' => 'active',
            'stripe_price' => 'price_pro',
            'ends_at' => now()->addDays(30),
            'status' => 'active',
        ]);

        // Setup Admin User with correct Role logic
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'tenant_id' => $this->tenant->id,
            'role' => 'center_admin',
            'must_change_password' => false,
        ]);

        // Setup Role properly for multi-tenancy
        $role = \App\Models\Role::firstOrCreate(['name' => 'center_admin', 'guard_name' => 'web', 'tenant_id' => $this->tenant->id]);
        $role->givePermissionTo(['view students', 'create students', 'edit students', 'delete students']);
        $this->admin->assignRole($role);

        // Setup Grade & Stage for Import Resolution
        $stage = \App\Models\Stage::firstOrCreate(['name' => 'Stage 1', 'tenant_id' => $this->tenant->id]);
        \App\Models\Grade::create(['name' => '10', 'tenant_id' => $this->tenant->id, 'stage_id' => $stage->id]);
        \App\Models\Grade::create(['name' => '11', 'tenant_id' => $this->tenant->id, 'stage_id' => $stage->id]);
        \App\Models\Grade::create(['name' => '12', 'tenant_id' => $this->tenant->id, 'stage_id' => $stage->id]);
    }

    public function test_admin_can_view_import_page()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('center.students.import', ['tenant' => $this->tenant->domain]));

        $response->assertStatus(200);
        $response->assertSee('استيراد الطلاب');
    }

    public function test_admin_can_import_students_via_csv()
    {
        \Illuminate\Support\Facades\Queue::fake();
        $this->actingAs($this->admin);

        // Create CSV Content
        $header = 'name,email,phone,grade_level';
        $row1 = 'John Doe,john@test.com,123456,10';
        $row2 = 'Jane Doe,jane@test.com,654321,11';
        $content = implode("\n", [$header, $row1, $row2]);

        $file = UploadedFile::fake()->createWithContent('students.csv', $content);

        $response = $this->post(route('center.students.import.post', ['tenant' => $this->tenant->domain]), [
            'file' => $file,
        ]);

        if ($response->status() !== 302) {
            dump($response->getContent());
        }
        if (session('errors')) {
            dump(session('errors')->all());
        }

        $response->assertRedirect(route('center.students.index', ['tenant' => $this->tenant->domain]));
        $response->assertSessionHas('success'); // "Import started..."

        // Assert Job Pushed
        \Illuminate\Support\Facades\Queue::assertPushed(\App\Jobs\ImportStudentsJob::class);
    }

    public function test_import_job_logic_handles_duplicates()
    {
        // Integration test for the Job logic
        \Illuminate\Support\Facades\Notification::fake();

        // 1. Setup Data
        User::factory()->create(['email' => 'john@test.com', 'tenant_id' => $this->tenant->id]);

        $header = 'name,email,phone,grade_level';
        $row1 = 'John Doe,john@test.com,123456,10'; // Duplicate
        $row2 = 'New Student,new@test.com,999999,12'; // New
        $content = implode("\n", [$header, $row1, $row2]);

        // Save file manually as the job expects it in storage
        $path = 'test_import.csv';
        \Illuminate\Support\Facades\Storage::put($path, $content);

        // 2. Create Job Instance
        $job = new \App\Jobs\ImportStudentsJob($path, $this->tenant->id, $this->admin->id);

        // 3. Run Handle
        $studentService = app(\App\Services\StudentService::class);
        $job->handle($studentService);

        // 4. Verify Database
        $this->assertDatabaseHas('users', ['email' => 'new@test.com']);

        // 5. Verify Notification contains success AND errors
        \Illuminate\Support\Facades\Notification::assertSentTo(
            [$this->admin],
            \App\Notifications\GeneralNotification::class,
            function ($notification) {
                // Check if internal message suggests partial success
                // Note: The job currently sends "Import Success: X students".
                // It doesn't explicitly list errors in the notification body based on my previous read,
                // but for 500k scale we usually don't want 1000 lines of errors in notification.
                // It should ideally point to a report.
                // For now, let's verify it says "Imported 1" (since 1 failed).
                return str_contains($notification->toArray($this->admin)['message'], 'تم استيراد 1 طالب');
            }
        );
    }
}
