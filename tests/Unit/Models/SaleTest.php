<?php

namespace Tests\Unit\Models;

use App\Models\Payment;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Student;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenant = Tenant::create(['domain' => 'test', 'name' => 'Test', 'onboarding_status' => 'completed']);
        app()->instance('tenant', $this->tenant);
    }

    #[Test]
    public function it_determines_paid_status()
    {
        $student = Student::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Student',
            'email' => 'student@test.com',
            'phone' => '01000000001',
        ]);

        $sale = Sale::create([
            'tenant_id' => $this->tenant->id,
            'student_id' => $student->id,
            'total_amount' => 100,
            'paid_amount' => 100,
            'status' => 'pending',
        ]);

        $this->assertEquals('paid', $sale->determineStatus());
    }

    #[Test]
    public function it_determines_partial_status()
    {
        $student = Student::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Student',
            'email' => 'student@test.com',
            'phone' => '01000000001',
        ]);

        $sale = Sale::create([
            'tenant_id' => $this->tenant->id,
            'student_id' => $student->id,
            'total_amount' => 100,
            'paid_amount' => 50,
            'status' => 'pending',
        ]);

        $this->assertEquals('partial', $sale->determineStatus());
    }

    #[Test]
    public function it_determines_pending_status()
    {
        $student = Student::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Student',
            'email' => 'student@test.com',
            'phone' => '01000000001',
        ]);

        $sale = Sale::create([
            'tenant_id' => $this->tenant->id,
            'student_id' => $student->id,
            'total_amount' => 100,
            'paid_amount' => 0,
            'status' => 'pending',
        ]);

        $this->assertEquals('pending', $sale->determineStatus());
    }

    #[Test]
    public function it_updates_status_based_on_payments()
    {
        $student = Student::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Student',
            'email' => 'student@test.com',
            'phone' => '01000000001',
        ]);

        $sale = Sale::create([
            'tenant_id' => $this->tenant->id,
            'student_id' => $student->id,
            'total_amount' => 100,
            'paid_amount' => 0,
            'status' => 'pending',
        ]);

        $sale->update(['paid_amount' => 50]);
        $sale->updateStatus();

        $this->assertEquals('partial', $sale->fresh()->status);

        $sale->update(['paid_amount' => 100]);
        $sale->updateStatus();

        $this->assertEquals('paid', $sale->fresh()->status);
    }

    #[Test]
    public function it_has_items_relationship()
    {
        $student = Student::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Student',
            'email' => 'student@test.com',
            'phone' => '01000000001',
        ]);

        $sale = Sale::create([
            'tenant_id' => $this->tenant->id,
            'student_id' => $student->id,
            'total_amount' => 100,
            'paid_amount' => 0,
        ]);

        SaleItem::create([
            'tenant_id' => $this->tenant->id,
            'sale_id' => $sale->id,
            'description' => 'Course Fee',
            'amount' => 100,
        ]);

        $this->assertCount(1, $sale->items);
    }

    #[Test]
    public function it_has_payments_relationship()
    {
        $student = Student::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Student',
            'email' => 'student@test.com',
            'phone' => '01000000001',
        ]);

        $sale = Sale::create([
            'tenant_id' => $this->tenant->id,
            'student_id' => $student->id,
            'total_amount' => 100,
            'paid_amount' => 100,
        ]);

        Payment::create([
            'tenant_id' => $this->tenant->id,
            'sale_id' => $sale->id,
            'amount' => 100,
            'payment_method' => 'cash',
            'paid_at' => now(),
        ]);

        $this->assertCount(1, $sale->payments);
    }

    #[Test]
    public function it_uses_soft_deletes()
    {
        $student = Student::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Student',
            'email' => 'student@test.com',
            'phone' => '01000000001',
        ]);

        $sale = Sale::create([
            'tenant_id' => $this->tenant->id,
            'student_id' => $student->id,
            'total_amount' => 100,
            'paid_amount' => 0,
        ]);

        $sale->delete();

        $this->assertSoftDeleted('sales', ['id' => $sale->id]);
    }
}
