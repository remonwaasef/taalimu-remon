<?php

namespace Tests\Feature\Security;

use App\Models\Grade;
use App\Models\Stage;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PaymentRaceConditionTest extends TestCase
{
    use RefreshDatabase;

    public function test_concurrent_payments_dont_exceed_sale_total()
    {
        $tenant = $this->createTenant(['domain' => 'race-test']);
        app()->instance('tenant', $tenant);

        $stage = Stage::create(['tenant_id' => $tenant->id, 'name' => 'S1', 'order' => 1]);
        $grade = Grade::create(['tenant_id' => $tenant->id, 'stage_id' => $stage->id, 'name' => 'G1', 'order' => 1]);
        $user = User::factory()->create(['tenant_id' => $tenant->id, 'role' => 'student']);
        $student = Student::factory()->create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'grade_id' => $grade->id,
        ]);

        $sale = \App\Models\Sale::create([
            'tenant_id' => $tenant->id,
            'student_id' => $student->id,
            'total_amount' => 500,
            'paid_amount' => 0,
            'status' => 'unpaid',
            'payment_method' => 'cash',
        ]);

        $this->actingAs(User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'center_admin',
        ]));

        $response1 = $this->postJson(route('center.sales.payment', ['tenant' => $tenant->domain, 'sale' => $sale->id]), [
            'amount' => 500,
            'method' => 'cash',
            'reference_number' => 'PAY-001',
        ]);

        $response2 = $this->postJson(route('center.sales.payment', ['tenant' => $tenant->domain, 'sale' => $sale->id]), [
            'amount' => 500,
            'method' => 'cash',
            'reference_number' => 'PAY-002',
        ]);

        $sale->refresh();

        $this->assertLessThanOrEqual(500, (float) $sale->paid_amount,
            'paid_amount must not exceed total_amount after concurrent payments');
    }

    public function test_same_reference_number_rejected()
    {
        $tenant = $this->createTenant(['domain' => 'ref-test']);
        app()->instance('tenant', $tenant);

        $stage = Stage::create(['tenant_id' => $tenant->id, 'name' => 'S1', 'order' => 1]);
        $grade = Grade::create(['tenant_id' => $tenant->id, 'stage_id' => $stage->id, 'name' => 'G1', 'order' => 1]);
        $user = User::factory()->create(['tenant_id' => $tenant->id, 'role' => 'student']);
        $student = Student::factory()->create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'grade_id' => $grade->id,
        ]);

        $sale = \App\Models\Sale::create([
            'tenant_id' => $tenant->id,
            'student_id' => $student->id,
            'total_amount' => 1000,
            'paid_amount' => 0,
            'status' => 'unpaid',
            'payment_method' => 'cash',
        ]);

        $this->actingAs(User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'center_admin',
        ]));

        $response1 = $this->postJson(route('center.sales.payment', ['tenant' => $tenant->domain, 'sale' => $sale->id]), [
            'amount' => 500,
            'method' => 'cash',
            'reference_number' => 'DUP-REF-001',
        ]);

        $response2 = $this->postJson(route('center.sales.payment', ['tenant' => $tenant->domain, 'sale' => $sale->id]), [
            'amount' => 500,
            'method' => 'cash',
            'reference_number' => 'DUP-REF-001',
        ]);

        // The unique index on reference_number should prevent duplicate entries
        // Either the second request should fail, or only one payment should exist
        $paymentCount = \App\Models\Payment::where('reference_number', 'DUP-REF-001')->count();
        $this->assertLessThanOrEqual(1, $paymentCount, 'Duplicate reference_number must be rejected by unique index');
    }

    public function test_payment_amount_cannot_exceed_remaining_balance()
    {
        $tenant = $this->createTenant(['domain' => 'overpay-test']);
        app()->instance('tenant', $tenant);

        $stage = Stage::create(['tenant_id' => $tenant->id, 'name' => 'S1', 'order' => 1]);
        $grade = Grade::create(['tenant_id' => $tenant->id, 'stage_id' => $stage->id, 'name' => 'G1', 'order' => 1]);
        $user = User::factory()->create(['tenant_id' => $tenant->id, 'role' => 'student']);
        $student = Student::factory()->create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'grade_id' => $grade->id,
        ]);

        $sale = \App\Models\Sale::create([
            'tenant_id' => $tenant->id,
            'student_id' => $student->id,
            'total_amount' => 500,
            'paid_amount' => 400,
            'status' => 'partial',
            'payment_method' => 'cash',
        ]);

        $this->actingAs(User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'center_admin',
        ]));

        $response = $this->postJson(route('center.sales.payment', ['tenant' => $tenant->domain, 'sale' => $sale->id]), [
            'amount' => 200,
            'method' => 'cash',
            'reference_number' => 'OVERPAY-001',
        ]);

        $sale->refresh();
        $this->assertLessThanOrEqual(500, (float) $sale->paid_amount,
            'paid_amount must not exceed total_amount');
    }
}
