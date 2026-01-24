<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use Spatie\Activitylog\Models\Activity;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        // Setup Tenant
        $this->tenant = Tenant::create(['domain' => 'test', 'name' => 'Test Center']);
        
        // Setup Admin
        $this->admin = User::factory()->create(['email' => 'admin@test.com', 'tenant_id' => $this->tenant->id]);
        $role = \Spatie\Permission\Models\Role::where('name', 'super_admin')->first();
        $this->admin->roles()->attach($role->id, ['tenant_id' => $this->tenant->id]);
    }

    public function test_user_creation_is_logged()
    {
        $this->actingAs($this->admin);

        $user = User::factory()->create([
            'name' => 'New User',
            'email' => 'new@test.com',
            'tenant_id' => $this->tenant->id,
            'role' => 'student'
        ]);

        $this->assertDatabaseHas('activity_log', [
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'event' => 'created',
            'causer_id' => $this->admin->id,
        ]);
    }

    public function test_user_update_is_logged()
    {
        $this->actingAs($this->admin);

        $user = User::factory()->create([
            'name' => 'Old Name',
            'tenant_id' => $this->tenant->id,
        ]);

        $user->update(['name' => 'New Name']);

        $this->assertDatabaseHas('activity_log', [
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'event' => 'updated',
        ]);

        $activity = Activity::where('subject_id', $user->id)->where('event', 'updated')->first();
        $this->assertArrayHasKey('name', $activity->changes['attributes']);
        $this->assertEquals('New Name', $activity->changes['attributes']['name']);
        $this->assertEquals('Old Name', $activity->changes['old']['name']);
    }

    public function test_admin_can_view_activity_logs()
    {
        $this->actingAs($this->admin);

        // Create some activity
        User::factory()->create(['tenant_id' => $this->tenant->id]);

        $response = $this->get(route('admin.activity-logs.index'));

        $response->assertStatus(200);
        $response->assertSee('Activity Logs');
        $response->assertSee('Created');
    }
}
