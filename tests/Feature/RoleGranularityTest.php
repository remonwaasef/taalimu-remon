<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use App\Services\RolePresetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class RoleGranularityTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create(['domain' => 'granular', 'name' => 'Granularity Center', 'onboarding_status' => 'completed']);
        app()->instance('tenant', $this->tenant);

        config(['session.domain' => '.localhost']);
        \Illuminate\Support\Facades\URL::forceRootUrl('http://granular.localhost');

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($this->tenant->id);

        // All permissions referenced by the preset templates + a few extras
        $permissionNames = [
            'view students', 'create students', 'edit students', 'delete students',
            'view instructors', 'create instructors', 'edit instructors', 'delete instructors',
            'view courses', 'create courses', 'edit courses', 'delete courses', 'publish courses',
            'view sales', 'create sales', 'edit sales', 'delete sales',
            'view expenses', 'create expenses', 'edit expenses', 'delete expenses',
            'view billing', 'manage billing',
            'view schedule', 'manage schedule',
            'view attendance', 'take attendance',
            'view exams', 'manage exams',
            'view reports', 'view analytics',
            'manage users', 'manage settings',
        ];

        foreach ($permissionNames as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // System-scope permissions (must never be assignable by a tenant)
        Permission::firstOrCreate(['name' => 'view centers', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create centers', 'guard_name' => 'web']);

        // Feature-gated routes: advanced_roles must be active on the subscription
        $package = Package::create([
            'name' => 'Pro',
            'slug' => 'pro-granularity',
            'stripe_price_id' => 'price_granularity',
            'price' => 99,
            'duration_in_days' => 30,
        ]);

        $feature = Feature::firstOrCreate(
            ['code' => 'advanced_roles'],
            ['name' => 'Advanced Roles', 'type' => 'boolean', 'category' => 'core']
        );

        $package->features()->attach($feature->id, ['value' => 'true']);

        Subscription::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'main',
            'stripe_id' => 'sub_granularity',
            'stripe_status' => 'active',
            'stripe_price' => 'price_granularity',
            'ends_at' => now()->addDays(30),
            'status' => 'active',
        ]);

        // Tenant admin with the center_admin role (RolePolicy grants create/update)
        $this->admin = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'center_admin',
            'must_change_password' => false,
        ]);

        $role = \App\Models\Role::firstOrCreate(['name' => 'center_admin', 'guard_name' => 'web', 'tenant_id' => $this->tenant->id]);
        $this->admin->assignRole($role);
    }

    public function test_create_page_shows_only_center_scope_permissions()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('center.roles.create', ['tenant' => $this->tenant->domain]));

        $response->assertStatus(200);
        $response->assertSee('value="view students"', false);
        $response->assertDontSee('value="view centers"', false);
        $response->assertSee('preset-card', false);
    }

    public function test_tenant_can_create_custom_subrole_with_center_scope_permissions()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('center.roles.store', ['tenant' => $this->tenant->domain]), [
            'name' => 'Junior Accountant',
            'permissions' => ['view sales', 'create sales', 'view expenses', 'manage billing', 'view reports'],
        ]);

        $response->assertRedirect(route('center.roles.index', ['tenant' => $this->tenant->domain]));

        $this->assertDatabaseHas('roles', [
            'name' => 'Junior Accountant',
            'tenant_id' => $this->tenant->id,
            'guard_name' => 'web',
        ]);

        $role = \App\Models\Role::where('name', 'Junior Accountant')->where('tenant_id', $this->tenant->id)->first();

        foreach (['view sales', 'create sales', 'view expenses', 'manage billing', 'view reports'] as $permission) {
            $this->assertTrue($role->hasPermissionTo($permission), "Role should have '{$permission}'");
        }
    }

    public function test_system_scope_permissions_are_never_attached_to_tenant_roles()
    {
        $this->actingAs($this->admin);

        $this->post(route('center.roles.store', ['tenant' => $this->tenant->domain]), [
            'name' => 'Sneaky Role',
            'permissions' => ['view sales', 'view centers', 'create centers'],
        ]);

        $role = \App\Models\Role::where('name', 'Sneaky Role')->where('tenant_id', $this->tenant->id)->first();

        $this->assertTrue($role->hasPermissionTo('view sales'));
        $this->assertFalse($role->hasPermissionTo('view centers'), 'System permissions must be filtered out server-side');
    }

    public function test_role_cache_is_invalidated_after_crud()
    {
        $this->actingAs($this->admin);

        // Populate the tenant role cache
        $this->get(route('center.roles.index', ['tenant' => $this->tenant->domain]));
        $this->assertTrue(Cache::has('roles_tenant_' . $this->tenant->id));

        $this->post(route('center.roles.store', ['tenant' => $this->tenant->domain]), [
            'name' => 'Cache Buster',
            'permissions' => ['view students'],
        ]);

        $this->assertFalse(Cache::has('roles_tenant_' . $this->tenant->id), 'Creating a role must invalidate the tenant role cache');
    }

    public function test_presets_offer_valid_tenant_scope_permissions()
    {
        $presets = app(RolePresetService::class)->presets();

        $this->assertTrue($presets->has('accountant'));
        $this->assertTrue($presets->has('secretary'));
        $this->assertTrue($presets->has('cashier'));
        $this->assertTrue($presets->has('staff'));

        foreach ($presets as $preset) {
            $this->assertNotEmpty($preset['permissions'], 'Every preset must define permissions');

            foreach ($preset['permissions'] as $permission) {
                $this->assertDatabaseHas('permissions', ['name' => $permission]);
            }
        }
    }
}
