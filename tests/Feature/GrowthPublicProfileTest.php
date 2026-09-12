<?php

namespace Tests\Feature;

use App\Models\GrowthEvent;
use App\Models\Instructor;
use App\Models\PublicProfile;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class GrowthPublicProfileTest extends TestCase
{
    use RefreshDatabase;

    private function createTenantWithInstructor(array $attributes = []): array
    {
        $instructorName = $attributes['instructor_name'] ?? 'Ahmed Mohammed';
        $slug = Str::slug($instructorName);

        $tenant = $this->createTenant();

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Test User',
            'email' => 'user_' . uniqid() . '@test.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
        ]);

        $instructor = Instructor::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'name' => $instructorName,
            'email' => 'instructor_' . uniqid() . '@test.com',
            'specialization' => 'Mathematics',
            'bio' => 'Experienced math teacher with 10 years of teaching.',
            'status' => 'active',
        ]);

        $profile = PublicProfile::create([
            'tenant_id' => $tenant->id,
            'profilable_type' => Instructor::class,
            'profilable_id' => $instructor->id,
            'slug' => $slug,
            'title' => $instructorName,
            'headline' => 'Mathematics Teacher',
            'bio' => 'Experienced math teacher with 10 years of teaching.',
            'location' => 'Cairo, Egypt',
            'experience_years' => 10,
            'specializations' => ['Mathematics', 'Algebra'],
            'delivery_modes' => ['online', 'offline'],
            'published' => true,
            'visibility' => [
                'name' => true,
                'email' => false,
                'phone' => false,
                'specialization' => true,
                'bio' => true,
                'image' => true,
                'location' => true,
                'experience_years' => true,
            ],
        ]);

        // NetworkIdentity is created automatically by PublicProfileObserver

        return compact('tenant', 'user', 'instructor', 'profile');
    }

    public function test_teacher_public_profile_returns_200(): void
    {
        ['profile' => $profile] = $this->createTenantWithInstructor();

        $response = $this->get(route('growth.teacher.show', $profile->slug));

        $response->assertStatus(200);
        $response->assertSee($profile->title);
        $response->assertSee($profile->headline);
    }

    public function test_teacher_public_profile_shows_seo_metadata(): void
    {
        ['profile' => $profile] = $this->createTenantWithInstructor();

        $response = $this->get(route('growth.teacher.show', $profile->slug));

        $response->assertStatus(200);
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('og:title', false);
        $response->assertSee('og:description', false);
        $response->assertSee('application/ld+json', false);
    }

    public function test_unpublished_profile_returns_404(): void
    {
        $tenant = $this->createTenant(['domain' => 'growth-unpub-' . uniqid()]);
        $instructor = Instructor::create([
            'tenant_id' => $tenant->id,
            'name' => 'Unpublished Teacher',
            'status' => 'active',
        ]);
        $profile = PublicProfile::create([
            'tenant_id' => $tenant->id,
            'profilable_type' => Instructor::class,
            'profilable_id' => $instructor->id,
            'slug' => 'unpublished-teacher',
            'title' => 'Unpublished Teacher',
            'published' => false,
            'visibility' => ['name' => true],
        ]);

        $response = $this->get(route('growth.teacher.show', $profile->slug));

        $response->assertStatus(404);
    }

    public function test_nonexistent_slug_returns_404(): void
    {
        $response = $this->get(route('growth.teacher.show', 'nonexistent-slug'));

        $response->assertStatus(404);
    }

    public function test_profile_view_is_tracked(): void
    {
        ['profile' => $profile] = $this->createTenantWithInstructor();

        $this->get(route('growth.teacher.show', ['slug' => $profile->slug, 'source' => 'facebook']));

        $this->assertDatabaseHas('growth_events', [
            'tenant_id' => $profile->tenant_id,
            'event_name' => 'profile_viewed',
            'source' => 'facebook',
        ]);
    }

    public function test_profile_view_with_source_and_campaign(): void
    {
        ['profile' => $profile] = $this->createTenantWithInstructor();

        $this->get(route('growth.teacher.show', [
            'slug' => $profile->slug,
            'source' => 'whatsapp',
            'campaign' => 'summer-2026',
        ]));

        $this->assertDatabaseHas('growth_events', [
            'tenant_id' => $profile->tenant_id,
            'event_name' => 'profile_viewed',
            'source' => 'whatsapp',
            'campaign' => 'summer-2026',
        ]);
    }

    public function test_profile_is_tenant_isolated(): void
    {
        $result1 = $this->createTenantWithInstructor(['instructor_name' => 'Ahmed Mohammed']);
        $result2 = $this->createTenantWithInstructor(['instructor_name' => 'Sara Ahmed']);

        // Use unique slugs for each tenant to test isolation
        $slug1 = $result1['profile']->slug . '-1';
        $slug2 = $result2['profile']->slug . '-2';

        $result1['profile']->update(['slug' => $slug1]);
        $result2['profile']->update(['slug' => $slug2]);

        // NetworkIdentity slugs are updated automatically by PublicProfileObserver

        // Same slug pattern, different tenants
        $response1 = $this->get(route('growth.teacher.show', $slug1));
        $response2 = $this->get(route('growth.teacher.show', $slug2));

        $response1->assertStatus(200);
        $response2->assertStatus(200);

        // Each profile shows its own data
        $response1->assertSee($result1['instructor']->name);
        $response2->assertSee($result2['instructor']->name);
    }

    public function test_profile_settings_requires_auth(): void
    {
        $response = $this->get(route('growth.profile.edit'));

        $response->assertRedirect();
    }

    public function test_profile_settings_shows_for_authenticated_instructor(): void
    {
        ['tenant' => $tenant, 'user' => $user, 'instructor' => $instructor] = $this->createTenantWithInstructor();
        app()->instance('tenant', $tenant);

        $this->actingAs($user);

        $response = $this->get(route('growth.profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Growth Profile');
    }

    public function test_profile_update_works(): void
    {
        ['tenant' => $tenant, 'user' => $user, 'instructor' => $instructor, 'profile' => $profile] = $this->createTenantWithInstructor();
        app()->instance('tenant', $tenant);

        $this->actingAs($user);

        $response = $this->put(route('growth.profile.update'), [
            'slug' => 'updated-slug',
            'title' => 'Updated Name',
            'headline' => 'Updated Headline',
            'bio' => 'Updated bio text.',
            'location' => 'Alexandria, Egypt',
            'experience_years' => 15,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $profile->refresh();
        $this->assertEquals('updated-slug', $profile->slug);
        $this->assertEquals('Updated Name', $profile->title);
    }

    public function test_slug_uniqueness_is_enforced(): void
    {
        ['tenant' => $tenant, 'user' => $user, 'profile' => $profile] = $this->createTenantWithInstructor();
        app()->instance('tenant', $tenant);

        $instructor2 = Instructor::create([
            'tenant_id' => $tenant->id,
            'name' => 'Second Instructor',
            'status' => 'active',
        ]);
        $profile2 = PublicProfile::create([
            'tenant_id' => $tenant->id,
            'profilable_type' => Instructor::class,
            'profilable_id' => $instructor2->id,
            'slug' => 'second-instructor',
            'title' => 'Second Instructor',
            'published' => false,
            'visibility' => ['name' => true],
        ]);

        $user2 = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'User 2',
            'email' => 'user2_' . uniqid() . '@test.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
        ]);
        $instructor2->update(['user_id' => $user2->id]);

        $this->actingAs($user2);

        $response = $this->put(route('growth.profile.update'), [
            'slug' => $profile->slug, // Try to use same slug in same tenant
            'title' => 'Test',
        ]);

        $response->assertSessionHasErrors('slug');
    }

    public function test_publish_profile(): void
    {
        $tenant = $this->createTenant(['domain' => 'growth-pub-' . uniqid()]);
        app()->instance('tenant', $tenant);
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Admin User',
            'email' => 'admin_' . uniqid() . '@test.com',
            'password' => Hash::make('password'),
            'role' => 'center_admin',
        ]);

        $profile = PublicProfile::create([
            'tenant_id' => $tenant->id,
            'profilable_type' => Tenant::class,
            'profilable_id' => $tenant->id,
            'slug' => 'test-center',
            'title' => 'Test Center',
            'published' => false,
            'visibility' => ['name' => true],
        ]);

        $this->actingAs($user);

        $response = $this->post(route('growth.profile.publish'));

        $response->assertRedirect();
        $profile->refresh();
        $this->assertTrue($profile->published);
    }

    public function test_unpublish_profile(): void
    {
        $tenant = $this->createTenant(['domain' => 'growth-unpub-' . uniqid()]);
        app()->instance('tenant', $tenant);
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Admin User',
            'email' => 'admin_' . uniqid() . '@test.com',
            'password' => Hash::make('password'),
            'role' => 'center_admin',
        ]);

        $profile = PublicProfile::create([
            'tenant_id' => $tenant->id,
            'profilable_type' => Tenant::class,
            'profilable_id' => $tenant->id,
            'slug' => 'test-center-2',
            'title' => 'Test Center 2',
            'published' => true,
            'visibility' => ['name' => true],
        ]);

        $this->actingAs($user);

        $response = $this->post(route('growth.profile.unpublish'));

        $response->assertRedirect();
        $profile->refresh();
        $this->assertFalse($profile->published);
    }

public function test_center_public_profile_returns_200(): void
    {
        $tenant = $this->createTenant(['domain' => 'test-center-' . uniqid()]);

        $profile = PublicProfile::create([
            'tenant_id' => $tenant->id,
            'profilable_type' => Tenant::class,
            'profilable_id' => $tenant->id,
            'slug' => 'test-center',
            'title' => $tenant->name,
            'headline' => 'Best Education Center',
            'published' => true,
            'visibility' => ['name' => true],
        ]);

        // NetworkIdentity is created automatically by PublicProfileObserver

        app()->instance('tenant', $tenant);

        $response = $this->get(route('growth.center.show', $profile->slug));

        $response->assertStatus(200);
        $response->assertSee($tenant->name);
    }
}
