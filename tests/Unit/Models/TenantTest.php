<?php

namespace Tests\Unit\Models;

use App\Models\Invoice;
use App\Models\Sale;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TenantTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_tenant()
    {
        $tenant = Tenant::create([
            'name' => 'Test Center',
            'domain' => 'test-center',
            'email' => 'admin@test.com',
            'phone' => '01000000000',
        ]);

        $this->assertDatabaseHas('tenants', ['domain' => 'test-center']);
        $this->assertEquals('Test Center', $tenant->name);
    }

    #[Test]
    public function it_has_users_relationship()
    {
        $tenant = Tenant::create(['domain' => 'test', 'name' => 'Test']);
        User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => 'password',
            'role' => 'center_admin',
        ]);

        $this->assertCount(1, $tenant->users);
    }

    #[Test]
    public function it_has_students_relationship()
    {
        $tenant = Tenant::create(['domain' => 'test', 'name' => 'Test']);
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Student',
            'email' => 'student@test.com',
            'password' => 'password',
            'role' => 'student',
        ]);
        \App\Models\Student::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'name' => 'Student',
            'email' => 'student@test.com',
            'phone' => '01000000001',
        ]);

        $this->assertCount(1, $tenant->students);
    }

    #[Test]
    public function it_has_subscriptions_relationship()
    {
        $tenant = Tenant::create(['domain' => 'test', 'name' => 'Test']);
        Subscription::create([
            'tenant_id' => $tenant->id,
            'name' => 'main',
            'stripe_status' => 'active',
            'status' => 'active',
            'ends_at' => now()->addDays(30),
        ]);

        $this->assertCount(1, $tenant->subscriptions);
    }

    #[Test]
    public function it_can_get_active_subscription()
    {
        $tenant = Tenant::create(['domain' => 'test', 'name' => 'Test']);
        Subscription::create([
            'tenant_id' => $tenant->id,
            'name' => 'expired',
            'stripe_status' => 'active',
            'status' => 'active',
            'ends_at' => now()->subDays(5),
        ]);
        Subscription::create([
            'tenant_id' => $tenant->id,
            'name' => 'active',
            'stripe_status' => 'active',
            'status' => 'active',
            'ends_at' => now()->addDays(30),
        ]);

        $active = $tenant->currentSubscription;
        $this->assertEquals('active', $active->name);
    }

    #[Test]
    public function it_can_calculate_ltv()
    {
        $tenant = Tenant::create(['domain' => 'test', 'name' => 'Test']);
        Invoice::create([
            'tenant_id' => $tenant->id,
            'amount' => 100,
            'status' => 'paid',
            'due_date' => now(),
        ]);
        Invoice::create([
            'tenant_id' => $tenant->id,
            'amount' => 200,
            'status' => 'paid',
            'due_date' => now(),
        ]);

        $this->assertEquals(300, $tenant->ltv);
    }

    #[Test]
    public function it_can_get_overdue_students_count()
    {
        $tenant = Tenant::create(['domain' => 'test', 'name' => 'Test']);
        $student = \App\Models\Student::create([
            'tenant_id' => $tenant->id,
            'name' => 'Student',
            'email' => 'student@test.com',
            'phone' => '01000000001',
        ]);
        Sale::create([
            'tenant_id' => $tenant->id,
            'student_id' => $student->id,
            'total_amount' => 100,
            'paid_amount' => 50,
            'status' => 'partial',
        ]);

        $this->assertEquals(1, $tenant->getOverdueStudentsCount());
    }

    #[Test]
    public function it_hides_sensitive_fields()
    {
        $tenant = Tenant::create([
            'domain' => 'test',
            'name' => 'Test',
            'database_name' => 'secret_db',
            'stripe_id' => 'cus_secret',
        ]);

        $array = $tenant->toArray();
        $this->assertArrayNotHasKey('database_name', $array);
        $this->assertArrayNotHasKey('stripe_id', $array);
        $this->assertArrayNotHasKey('settings', $array);
    }
}
