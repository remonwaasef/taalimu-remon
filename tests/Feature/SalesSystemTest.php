<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Sale;
use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected $admin;

    protected $student;

    protected $course;

    protected $instructor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();

        // Clear Permission Cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Fix Session Domain for Subdomain Auth
        config(['session.domain' => '.localhost']);

        // Setup Tenant
        $this->tenant = Tenant::create(['domain' => 'test', 'name' => 'Test Center']);
        app()->instance('tenant', $this->tenant);

        \Illuminate\Support\Facades\URL::forceRootUrl('http://test.localhost');

        // Mock feature check if necessary, or ensure tenant has it.
        // Based on routes: Route::middleware(['feature:financial_reports'])
        // We'll trust the middleware uses the Tenant model's features relationship or similar.
        // Let's seed the feature.
        $feature = \App\Models\Feature::firstOrCreate(
            ['code' => 'financial_reports'],
            ['name' => 'Financial Reports', 'type' => 'boolean']
        );
        $package = \App\Models\Package::create(['name' => 'Pro', 'slug' => 'pro', 'stripe_price_id' => 'p_1', 'price' => 10, 'duration_in_days' => 30]);
        $package->features()->attach($feature, ['value' => 'true']);

        \App\Models\Subscription::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'main',
            'stripe_id' => 's_1',
            'stripe_status' => 'active',
            'stripe_price' => 'p_1',
            'ends_at' => now()->addYear(),
            'status' => 'active',
        ]);

        // Setup Admin
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'tenant_id' => $this->tenant->id,
            'role' => 'admin',
            'must_change_password' => false,
            'google2fa_enabled' => false,
        ]);

        // Setup Permissions and Roles
        // Ensure Team ID is set for Spatie
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($this->tenant->id);

        \Spatie\Permission\Models\Permission::create(['name' => 'view sales', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'create sales', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'edit sales', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'delete sales', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'manage billing', 'guard_name' => 'web']); // for expenses if needed

        $this->admin->givePermissionTo(['view sales', 'create sales', 'edit sales']);

        $role = \App\Models\Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web', 'tenant_id' => $this->tenant->id]);
        $this->admin->assignRole($role);

        // Setup Student
        $this->student = Student::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'status' => 'active',
        ]);

        // Setup Instructor
        $this->instructor = Instructor::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Prof. Smith',
            'email' => 'smith@test.com',
            'specialization' => 'Math',
        ]);

        // Setup Course
        $this->course = Course::create([
            'tenant_id' => $this->tenant->id,
            'instructor_id' => $this->instructor->id,
            'title' => 'Test Course',
            'price' => 100.00,
            'status' => 'published',
        ]);
    }

    public function test_admin_can_create_sale()
    {
        $this->actingAs($this->admin);
        // dump($this->admin->permissions->pluck('name'));
        // dump($this->admin->roles->pluck('name'));

        $response = $this->postJson(route('center.sales.store', ['tenant' => $this->tenant->domain]), [
            'student_id' => $this->student->id,
            'items' => [
                ['id' => $this->course->id, 'price' => 100.00],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 50.00,
            'notes' => 'Partial payment',
        ]);

        if ($response->status() !== 200) {
            // dump($response->json());
        }
        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('sales', [
            'total_amount' => 100.00,
            'paid_amount' => 50.00,
            'status' => 'partial',
        ]);

        $this->assertDatabaseHas('sale_items', [
            'item_id' => $this->course->id,
            'price' => 100.00,
        ]);
    }

    public function test_admin_can_view_sales_list()
    {
        $this->actingAs($this->admin);

        Sale::create([
            'tenant_id' => $this->tenant->id,
            'student_id' => $this->student->id,
            'total_amount' => 200.00,
            'paid_amount' => 200.00,
            'status' => 'paid',
        ]);

        $response = $this->get(route('center.sales.index', ['tenant' => $this->tenant->domain]));

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('200.00');
    }

    public function test_admin_can_add_payment()
    {
        $sale = Sale::create([
            'tenant_id' => $this->tenant->id,
            'student_id' => $this->student->id,
            'total_amount' => 100.00,
            'paid_amount' => 50.00,
            'status' => 'partial',
        ]);

        $this->actingAs($this->admin);

        $response = $this->post(route('center.sales.payment', ['tenant' => $this->tenant->domain, 'sale' => $sale->id]), [
            'amount' => 50.00,
        ]);

        $response->assertRedirect();

        $this->assertEquals(100.00, $sale->fresh()->paid_amount);
        $this->assertEquals('paid', $sale->fresh()->status);
    }
}
