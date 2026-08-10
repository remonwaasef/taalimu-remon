<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Tenant;
use App\Services\PaymentGateways\PaymobGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymobWebhookController extends Controller
{
    protected $gateway;

    public function __construct(PaymobGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function handle(Request $request)
    {
        $payload = $request->except(['card_pan', 'source', 'cvv', 'token']);
        Log::info('Paymob Webhook Received', $payload);

        // Use the gateway's built-in verification logic
        $result = $this->gateway->handleCallback($payload);

        if (! $result['success']) {
            Log::warning('Paymob Webhook Verification Failed: '.($result['message'] ?? 'Unknown Error'));

            return response()->json(['status' => 'error'], 400);
        }

        $transactionId = $result['transaction_id'];
        $orderId = $result['order_id'];
        $merchantOrderId = $result['merchant_order_id'] ?? null;

        // --- Self-service invoice payment (sale flow) ---
        $saleContext = $this->gateway->parseSaleOrderId($merchantOrderId);

        if ($saleContext) {
            return $this->handleSalePayment($result, $saleContext);
        }

        // Find subscription by transaction ID or order ID if stored
        $subscription = Subscription::where('stripe_id', 'sub_paymob_'.$transactionId)->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'active',
                'stripe_status' => 'active',
            ]);

            Log::info("Paymob Subscription Updated for Trans ID: {$transactionId}");
        } else {
            // PAY-2: subscription activation is authorized ONLY through the
            // server-side online_checkouts map keyed by the HMAC-covered
            // Paymob order id — the unsigned merchant_order_id is never trusted.
            $checkout = \App\Models\OnlineCheckout::where('paymob_order_id', $orderId)
                ->where('status', 'pending')
                ->first();

            if (! $checkout) {
                Log::channel('security')->warning('Paymob Webhook: no pending checkout for order', [
                    'order_id' => $orderId,
                    'transaction_id' => $transactionId,
                ]);

                return response()->json(['status' => 'error'], 400);
            }

            return $this->handleSubscriptionCheckout($result, $checkout);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Activate a tenant subscription from a verified online checkout row.
     * Runs atomically: the checkout is consumed (pending → processed) under
     * the same transaction as the subscription activation so a replayed
     * webhook can never double-activate.
     */
    protected function handleSubscriptionCheckout(array $result, \App\Models\OnlineCheckout $checkout)
    {
        $transactionId = $result['transaction_id'];

        // Amount must match what we minted the order for — the payload
        // amount is covered by the HMAC, the checkout amount is server-side.
        $payloadCents = (int) round($result['amount'] * 100);
        if ($payloadCents !== (int) $checkout->amount_cents) {
            Log::channel('security')->error('Paymob Webhook: checkout amount mismatch', [
                'checkout_id' => $checkout->id,
                'expected_cents' => $checkout->amount_cents,
                'received_cents' => $payloadCents,
            ]);

            return response()->json(['status' => 'error'], 400);
        }

        // Belt-and-braces: the merchant_order_id must match what we sent
        // when the order was created (it is not HMAC-covered itself).
        $merchantOrderId = $result['merchant_order_id'] ?? null;
        if ($checkout->merchant_order_id && $merchantOrderId !== $checkout->merchant_order_id) {
            Log::channel('security')->error('Paymob Webhook: merchant_order_id mismatch', [
                'checkout_id' => $checkout->id,
                'expected' => $checkout->merchant_order_id,
                'received' => $merchantOrderId,
            ]);

            return response()->json(['status' => 'error'], 400);
        }

        try {
            return \Illuminate\Support\Facades\DB::transaction(function () use ($result, $checkout, $transactionId) {
                // Lock the row: concurrent duplicate webhooks serialize here.
                $locked = \App\Models\OnlineCheckout::whereKey($checkout->id)->lockForUpdate()->first();
                if (! $locked || $locked->status !== 'pending') {
                    Log::info('Paymob Webhook: checkout already processed, duplicate ignored', [
                        'checkout_id' => $checkout->id,
                    ]);

                    return response()->json(['status' => 'duplicate']);
                }

                $tenantId = $locked->tenant_id;
                $planSlug = $locked->package_slug;
                $billingCycle = $locked->billing_cycle;
                $isChange = $locked->is_change;

                $tenant = Tenant::find($tenantId);
                if (! $tenant) {
                    Log::error('Paymob Webhook: checkout tenant not found', ['tenant_id' => $tenantId]);

                    return response()->json(['status' => 'error'], 404);
                }

                $package = \App\Models\Package::where('slug', $planSlug)->first();

                $totalAmount = $locked->amount_cents / 100;

                if ($billingCycle === 'monthly') {
                    $days = 30;
                } elseif ($billingCycle === 'term') {
                    $days = (int) \App\Models\SiteSetting::get('term_duration_days', 150);
                } elseif ($billingCycle === 'yearly') {
                    $days = 365;
                } elseif ($package) {
                    $days = $package->duration_in_days;
                } else {
                    $days = 30;
                }

                $tenant->subscriptions()->updateOrCreate(
                    ['name' => 'default'],
                    [
                        'gateway' => 'paymob',
                        'stripe_id' => 'sub_paymob_'.$transactionId,
                        'stripe_status' => 'active',
                        'stripe_price' => 'price_paymob_'.($package->slug ?? ($planSlug ?: 'unknown')),
                        'quantity' => 1,
                        'billing_cycle' => $billingCycle,
                        'base_price' => $totalAmount,
                        'total_amount' => $totalAmount,
                        'discount_amount' => 0,
                        'status' => 'active',
                        'ends_at' => now()->addDays($days),
                    ]
                );

                \App\Models\SubscriptionLog::logOperation(
                    $tenant->id,
                    $isChange ? 'upgrade' : 'subscription',
                    $package->slug ?? ($planSlug ?: 'unknown'),
                    $package->name ?? 'مخصص',
                    $billingCycle,
                    'paymob',
                    $totalAmount,
                    $transactionId,
                    now(),
                    now()->addDays($days)
                );

                $locked->forceFill(['status' => 'processed'])->save();

                Log::info("Paymob Webhook: Subscription activated from verified checkout {$locked->id} for Trans ID: {$transactionId}");

                return response()->json(['status' => 'success']);
            });
        } catch (\Throwable $e) {
            Log::error('Paymob Webhook: subscription activation failed: '.$e->getMessage(), [
                'checkout_id' => $checkout->id,
                'transaction_id' => $transactionId,
            ]);

            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Record a successful self-service invoice payment from the Paymob webhook.
     * Idempotent: a transaction reference is recorded once per Payment row.
     */
    protected function handleSalePayment(array $result, array $saleContext)
    {
        // Only success transactions post money
        if (! $result['success']) {
            Log::info('Paymob Sale Webhook: non-success transaction ignored', [
                'sale_id' => $saleContext['sale_id'],
                'transaction_id' => $result['transaction_id'],
            ]);

            return response()->json(['status' => 'ignored']);
        }

        $sale = \App\Models\Sale::withoutGlobalScopes()
            ->where('id', $saleContext['sale_id'])
            ->where('tenant_id', $saleContext['tenant_id'])
            ->first();

        if (! $sale) {
            Log::warning('Paymob Sale Webhook: sale not found', $saleContext);

            return response()->json(['status' => 'error'], 404);
        }

        // Token verification: the order must be minted for THIS invoice
        if (! $sale->payment_token || ! hash_equals((string) $sale->payment_token, (string) $saleContext['payment_token'])) {
            Log::channel('security')->warning('Paymob Sale Webhook: payment token mismatch', $saleContext);

            return response()->json(['status' => 'error'], 400);
        }

        // Idempotency guard — fast-path check (the unique index below is the
        // authoritative race-proof backstop).
        $referenceNumber = 'paymob_'.$result['transaction_id'];
        $alreadyRecorded = \App\Models\Payment::where('reference_number', $referenceNumber)->exists();
        if ($alreadyRecorded) {
            Log::info('Paymob Sale Webhook: duplicate transaction ignored', [
                'sale_id' => $sale->id,
                'transaction_id' => $result['transaction_id'],
            ]);

            return response()->json(['status' => 'duplicate']);
        }

        $amount = (float) $result['amount'];

        try {
            // Bind tenant context for scopes/notifications
            \Modules\Tenancy\Services\TenantResolver::set($sale->tenant);

            $finance = app(\App\Services\FinanceService::class);
            $finance->addPayment(
                $sale,
                $amount,
                'online',
                'دفعة إلكترونية عبر بوابة الدفع (Paymob #'.$result['transaction_id'].')',
                $referenceNumber
            );

            Log::info('Paymob Sale Webhook: payment recorded', [
                'sale_id' => $sale->id,
                'amount' => $amount,
                'transaction_id' => $result['transaction_id'],
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Illuminate\Database\QueryException $e) {
            // PAY-3: the unique index on payments.reference_number closes the
            // check-then-insert race — a concurrent duplicate webhook surfaces
            // here as a constraint violation and is treated as a duplicate.
            if ($e->getCode() === 23000 || str_contains($e->getMessage(), 'Duplicate entry')) {
                Log::info('Paymob Sale Webhook: concurrent duplicate transaction rejected by unique index', [
                    'sale_id' => $sale->id,
                    'transaction_id' => $result['transaction_id'],
                ]);

                return response()->json(['status' => 'duplicate']);
            }

            throw $e;
        } catch (\Throwable $e) {
            Log::error('Paymob Sale Webhook: recording failed: '.$e->getMessage(), [
                'sale_id' => $sale->id ?? null,
                'transaction_id' => $result['transaction_id'] ?? null,
            ]);

            return response()->json(['status' => 'error'], 500);
        }
    }
}
