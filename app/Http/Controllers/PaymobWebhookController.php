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
            // Restore context from merchant_order_id
            $merchantOrderId = $result['merchant_order_id'] ?? null;

            if ($merchantOrderId && str_starts_with($merchantOrderId, 'tx_')) {
                $parts = explode('_', $merchantOrderId);
                if (count($parts) >= 6) {
                    $tenantId = $parts[2];
                    $planSlug = $parts[3];
                    $billingCycle = $parts[4];
                    $isChange = $parts[5] === '1';

                    $tenant = Tenant::find($tenantId);
                    if ($tenant) {
                        $package = \App\Models\Package::where('slug', $planSlug)->first();

                        $totalAmount = ($billingCycle === 'yearly' ? ($package->yearly_price ?? 0) : ($billingCycle === 'term' ? ($package->term_price ?? 0) : ($package->price ?? 0)));

                        if ($billingCycle === 'monthly') {
                            $days = 30;
                        } elseif ($billingCycle === 'term') {
                            $termDuration = (int) \App\Models\SiteSetting::get('term_duration_days', 150);
                            $days = $termDuration;
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

                        // Log the subscription operation
                        $operationType = $isChange ? 'upgrade' : 'subscription';
                        \App\Models\SubscriptionLog::logOperation(
                            $tenant->id,
                            $operationType,
                            $package->slug ?? ($planSlug ?: 'unknown'),
                            $package->name ?? 'مخصص',
                            $billingCycle,
                            'paymob',
                            $totalAmount,
                            $transactionId,
                            now(),
                            now()->addDays($days)
                        );

                        Log::info("Paymob Webhook: Subscription created from context restoration for Trans ID: {$transactionId}");

                        return response()->json(['status' => 'success']);
                    }
                }
            }

            Log::info("Paymob Webhook: Subscription not found and context restoration failed for Trans ID: {$transactionId}.");
        }

        return response()->json(['status' => 'success']);
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

        // Idempotency guard — never double post the same transaction
        $alreadyRecorded = \App\Models\Payment::where('reference_number', 'paymob_'.$result['transaction_id'])->exists();
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
                'دفعة إلكترونية عبر بوابة الدفع (Paymob #'.$result['transaction_id'].')'
            );

            // Attach the transaction reference to the created payment row for idempotency
            \App\Models\Payment::where('sale_id', $sale->id)
                ->whereNull('reference_number')
                ->latest('id')
                ->first()
                ?->forceFill(['reference_number' => 'paymob_'.$result['transaction_id']])
                ->save();

            Log::info('Paymob Sale Webhook: payment recorded', [
                'sale_id' => $sale->id,
                'amount' => $amount,
                'transaction_id' => $result['transaction_id'],
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {
            Log::error('Paymob Sale Webhook: recording failed: '.$e->getMessage(), [
                'sale_id' => $sale->id ?? null,
                'transaction_id' => $result['transaction_id'] ?? null,
            ]);

            return response()->json(['status' => 'error'], 500);
        }
    }
}
