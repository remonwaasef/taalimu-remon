<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Payment gateway webhooks are server-to-server POSTs that carry no CSRF token.
 * They must never be rejected with 419 — authenticity is verified in-controller
 * (PayPal webhook signature / Paymob HMAC).
 */
class WebhookCsrfTest extends TestCase
{
    use RefreshDatabase;

    public function test_paypal_webhook_is_not_blocked_by_csrf()
    {
        $response = $this->post('/webhooks/paypal', ['event_type' => 'TEST']);

        $this->assertNotEquals(419, $response->status());
        // Unsigned payload must be rejected by signature verification, not CSRF
        $response->assertStatus(400);
    }

    public function test_paymob_webhook_is_not_blocked_by_csrf()
    {
        $response = $this->post('/webhooks/paymob', ['obj' => []]);

        $this->assertNotEquals(419, $response->status());
    }
}
