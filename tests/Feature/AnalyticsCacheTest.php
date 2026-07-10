<?php

namespace Tests\Feature;

use App\Models\Grade;
use App\Models\Sale;
use App\Models\Stage;
use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AnalyticsCacheTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Tenant & Bind
        $this->tenant = Tenant::create(['domain' => 'test_cache', 'name' => 'Test Cache Center']);
        app()->instance('tenant', $this->tenant);

        // Clear Permission Cache
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($this->tenant->id);

        // Setup Admin
        $this->admin = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'center_admin',
        ]);

        $role = \App\Models\Role::firstOrCreate(['name' => 'center_admin', 'guard_name' => 'web', 'tenant_id' => $this->tenant->id]);
        $role->givePermissionTo(\Spatie\Permission\Models\Permission::create(['name' => 'view students', 'guard_name' => 'web']));
        $this->admin->assignRole($role);

        // Fix Session Domain & Root URL
        config(['session.domain' => '.localhost']);
        \Illuminate\Support\Facades\URL::forceRootUrl("http://{$this->tenant->domain}.localhost");

        // Setup Data for Analytics
        $stage = Stage::create(['name' => 'Stage 1', 'tenant_id' => $this->tenant->id]);
        $grade = Grade::create(['name' => 'Grade 1', 'stage_id' => $stage->id, 'tenant_id' => $this->tenant->id]);

        Student::factory()->count(10)->create([
            'tenant_id' => $this->tenant->id,
            'grade_id' => $grade->id,
            'status' => 'active',
        ]);

        // Create Sales to trigger caching
        Sale::create([
            'student_id' => Student::first()->id,
            'tenant_id' => $this->tenant->id,
            'total_amount' => 100,
            'paid_amount' => 50,
            'status' => 'partial',
            'created_at' => now(),
        ]);
    }

    #[Test]
    public function it_reduces_db_queries_on_second_request()
    {
        $this->actingAs($this->admin);

        // First Request - Should hit DB
        // We can't easily assert query count without clearing global query log which isn't enabled by default in tests easily without DB::enableQueryLog()

        \Illuminate\Support\Facades\DB::enableQueryLog();

        $response1 = $this->get(route('center.analytics.students', ['tenant' => $this->tenant->domain]));
        $response1->assertStatus(200);

        $queries1 = count(\Illuminate\Support\Facades\DB::getQueryLog());

        \Illuminate\Support\Facades\DB::flushQueryLog();

        // Second Request - Should hit Cache
        $response2 = $this->get(route('center.analytics.students', ['tenant' => $this->tenant->domain]));
        $response2->assertStatus(200);

        $queries2 = count(\Illuminate\Support\Facades\DB::getQueryLog());

        // Assert second request has fewer queries
        // Note: Some queries might still run (like auth check, tenant resolution), but the heavy aggregations should be gone.
        $this->assertLessThan($queries1, $queries2, "Expected cached request to have fewer queries ($queries2) than first request ($queries1)");
    }
}
