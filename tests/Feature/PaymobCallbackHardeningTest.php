<?php

namespace Tests\Feature;

use App\Models\OnlineCheckout;
use App\Models\Package;
use App\Models\Sale;
use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * SEC-PAY-1 / SEC-PAY-3 regression suite.
 *
 * The Paymob redirect HMAC covers `order` but NOT `merchant_order_id`, so
 * subscription context must be restored from the server-side online_checkouts
 * map keyed by paymob_order_id. A tampered merchant_order_id must never be
 * able to activate (or overwrite) another tenant's subscription, and the sale
 * redirect must reject an invoice whose embedded payment token was changed.
 */
class PaymobCallbackHardeningTest extends TestCase
{
    use RefreshDatabase;

    private const HMAC_SECRET = 'test_paymob_hmac_secret';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.tenant_domain' => 'localhost',
            'services.paymob.hmac_secret' => self::HMAC_SECRET,
            'session.domain' => '.localhost',
        ]);
    }

    /**
     * Build a signed redirect payload exactly like Paymob does: alphabetically
     * ordered fields, booleans as 'true'/'false', SHA-512 HMAC with the
     * configured secret. merchant_order_id is deliberately NOT part of it.
     */
    private function signedRedirectPayload(array $overrides = []): array
    {
        $fields = [
            'amount_cents', 'created_at', 'currency', 'error_occured',
            'has_parent_transaction', 'id', 'integration_id', 'is_3d_secure',
            'is_auth', 'is_capture', 'is_refunded', 'is_standalone_payment',
            'is_voided', 'order', 'owner', 'pending', 'source_data_pan',
            'source_data_sub_type', 'source_data_type', 'success',
        ];

        $data = array_merge([
            'amount_cents' => '10000',
            'created_at' => '2026-01-01 10:00:00',
            'currency' => 'EGP',
            'error_occured' => 'false',
            'has_parent_transaction' => 'false',
            'id' => '900000',
            'integration_id' => '123',
            'is_3d_secure' => 'false',
            'is_auth' => 'false',
            'is_capture' => 'true',
            'is_refunded' => 'false',
            'is_standalone_payment' => 'false',
            'is_voided' => 'false',
            'order' => '7777777',
            'owner' => '0',
            'pending' => 'false',
            'source_data_pan' => '1234',
            'source_data_sub_type' => 'CARD',
            'source_data_type' => 'card',
            'success' => 'true',
        ], $overrides);

        $source = '';
        foreach ($fields as $field) {
            $val = $data[$field] ?? null;
            $source .= $val === null ? '' : (string) $val;
        }

        $data['hmac'] = hash_hmac('sha512', $source, self::HMAC_SECRET);

        return $data;
    }

    private function createTenantWithAdmin(string $domain): Tenant
    {
        $tenant = Tenant::create([
            'domain' => $domain,
            'name' => 'Center '.$domain,
            'onboarding_status' => 'completed',
        ]);

        User::create([
            'name' => 'Admin '.$domain,
            'email' => 'admin-'.$domain.'@test.com',
            'phone' => '010'.$domain,
            'password' => Hash::make('password'),
            'role' => 'center_admin',
            'tenant_id' => $tenant->id,
        ]);

        return $tenant;
    }

    public function test_tampered_merchant_order_id_cannot_activate_victim_subscription()
    {
        $attacker = $this->createTenantWithAdmin('attacker');
        $victim = $this->createTenantWithAdmin('victim');

        Package::firstOrCreate(
            ['slug' => 'pro'],
            ['name' => 'Pro', 'stripe_price_id' => 'p_pro', 'price' => 950, 'duration_in_days' => 30]
        );

        // The attacker's own pending checkout (order id covered by redirect HMAC).
        OnlineCheckout::create([
            'paymob_order_id' => '7777777',
            'merchant_order_id' => 'tx_1111_'.$attacker->id.'_pro_monthly_0',
            'tenant_id' => $attacker->id,
            'package_slug' => 'pro',
            'billing_cycle' => 'monthly',
            'is_change' => false,
            'amount_cents' => 95000,
            'status' => 'pending',
        ]);

        // The victim's pending checkout — the legacy exploit target.
        OnlineCheckout::create([
            'paymob_order_id' => '8888888',
            'merchant_order_id' => 'tx_2222_'.$victim->id.'_enterprise_yearly_1',
            'tenant_id' => $victim->id,
            'package_slug' => 'enterprise',
            'billing_cycle' => 'yearly',
            'is_change' => true,
            'amount_cents' => 1200000,
            'status' => 'pending',
        ]);

        // Legitimate redirect HMAC for the attacker's own order…
        $payload = $this->signedRedirectPayload([
            'id' => '3456789',
            'amount_cents' => '95000',
            'order' => '7777777',
            'success' => 'true',
        ]);
        // …carrying an attacker-supplied merchant_order_id pointing at the victim.
        $payload['merchant_order_id'] = 'tx_2222_'.$victim->id.'_enterprise_yearly_1';

        $response = $this->get('/payment/paymob/callback?'.http_build_query($payload));

        // Context is restored from the HMAC-covered order id: the attacker's own
        // checkout wins and the victim's subscription is never touched.
        $this->assertDatabaseMissing('subscriptions', ['tenant_id' => $victim->id]);
        $this->assertDatabaseHas('subscriptions', [
            'tenant_id' => $attacker->id,
            'stripe_id' => 'sub_paymob_3456789',
        ]);

        // Sanity: the HMAC validated — it was the attacker's own legitimate order.
        $this->assertNotEquals(403, $response->getStatusCode());
    }

    public function test_sale_redirect_rejects_tampered_payment_token()
    {
        $tenant = $this->createTenantWithAdmin('saler');

        $student = Student::create([
            'tenant_id' => $tenant->id,
            'name' => 'Student',
            'phone' => '0100',
            'status' => 'active',
        ]);

        $sale = Sale::create([
            'tenant_id' => $tenant->id,
            'student_id' => $student->id,
            'total_amount' => 100,
            'paid_amount' => 0,
            'status' => 'unpaid',
        ]);
        $sale->payment_token = 'secret_token_abc';
        $sale->save();

        $payload = $this->signedRedirectPayload([
            'id' => '54321',
            'success' => 'true',
        ]);
        $payload['merchant_order_id'] = 'sale_'.$sale->id.'_'.$tenant->id.'_wrong_token';

        $response = $this->get('/payment/paymob/callback?'.http_build_query($payload));

        // SEC-PAY-3: mismatched embedded token → generic error redirect, never
        // the invoice result page.
        $response->assertRedirect(route('home'));
    }
}