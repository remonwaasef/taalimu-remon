<?php

namespace Tests\Feature;

use App\Models\Instructor;
use App\Models\PublicProfile;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GrowthDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function createTeacherWithProfile(): array
    {
        $tenant = $this->createTenant(['domain' => 'growth-dash-' . uniqid()]);

        // Bind tenant context so BelongsToTenant trait can auto-set tenant_id
        app()->instance('tenant', $tenant);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Test Teacher',
            'email' => 'teacher_' . uniqid() . '@test.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
        ]);

        $instructor = Instructor::create([
            'user_id' => $user->id,
            'name' => 'Test Teacher',
            'email' => 'instructor_' . uniqid() . '@test.com',
            'specialization' => 'Mathematics',
            'status' => 'active',
        ]);

        $profile = PublicProfile::create([
            'profilable_type' => Instructor::class,
            'profilable_id' => $instructor->id,
            'slug' => 'test-teacher-' . uniqid(),
            'title' => 'Test Teacher',
            'headline' => 'Math Teacher',
            'published' => true,
            'visibility' => ['name' => true],
        ]);

        return compact('tenant', 'user', 'instructor', 'profile');
    }

    public function test_growth_dashboard_returns_200(): void
    {
        ['tenant' => $tenant, 'user' => $user] = $this->createTeacherWithProfile();

        $this->actingAs($user);
        app()->instance('tenant', $tenant);

        $response = $this->get(route('growth.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Growth Dashboard');
        $response->assertSee('Growth Score');
    }

    public function test_growth_dashboard_requires_auth(): void
    {
        $response = $this->get(route('growth.dashboard'));

        $response->assertRedirect();
    }

    public function test_referral_code_is_auto_generated(): void
    {
        ['profile' => $profile] = $this->createTeacherWithProfile();

        $this->assertNotNull($profile->referral_code);
        $this->assertEquals(8, strlen($profile->referral_code));
    }

    public function test_referral_link_appears_in_profile_settings(): void
    {
        ['tenant' => $tenant, 'user' => $user, 'profile' => $profile] = $this->createTeacherWithProfile();

        $this->actingAs($user);
        app()->instance('tenant', $tenant);

        $response = $this->get(route('growth.profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Your Referral Link');
        $response->assertSee($profile->referral_code);
    }

    public function test_notifications_page_returns_200(): void
    {
        ['tenant' => $tenant, 'user' => $user] = $this->createTeacherWithProfile();

        $this->actingAs($user);
        app()->instance('tenant', $tenant);

        $response = $this->get(route('growth.notifications'));

        $response->assertStatus(200);
        $response->assertSee('Notifications');
    }

    public function test_mark_notification_read(): void
    {
        ['tenant' => $tenant, 'user' => $user] = $this->createTeacherWithProfile();

        $notification = \App\Models\TeacherNotification::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'type' => 'new_demand',
            'title' => 'New demand',
            'message' => 'Someone is interested.',
        ]);

        $this->actingAs($user);
        app()->instance('tenant', $tenant);

        $response = $this->post(route('growth.notification.read', $notification->id));

        $response->assertRedirect();

        $notification->refresh();
        $this->assertNotNull($notification->read_at);
        $this->assertTrue($notification->read_at->isToday());
    }

    public function test_notification_is_tenant_isolated(): void
    {
        $result1 = $this->createTeacherWithProfile();
        $result2 = $this->createTeacherWithProfile();

        \App\Models\TeacherNotification::create([
            'tenant_id' => $result1['tenant']->id,
            'user_id' => $result1['user']->id,
            'type' => 'new_demand',
            'title' => 'Demand for tenant 1',
            'message' => 'Test message 1',
        ]);

        \App\Models\TeacherNotification::create([
            'tenant_id' => $result2['tenant']->id,
            'user_id' => $result2['user']->id,
            'type' => 'new_demand',
            'title' => 'Demand for tenant 2',
            'message' => 'Test message 2',
        ]);

        $this->actingAs($result1['user']);
        app()->instance('tenant', $result1['tenant']);

        $response = $this->get(route('growth.notifications'));

        $response->assertStatus(200);
        $response->assertSee('Demand for tenant 1');
        $response->assertDontSee('Demand for tenant 2');
    }
}
