<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentGateways\PaymobGateway;
use App\Models\Subscription;
use App\Models\Tenant;
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
        Log::info('Paymob Webhook Received', $request->all());

        $payload = $request->all();
        
        // Use the gateway's built-in verification logic
        $result = $this->gateway->handleCallback($payload);

        if (!$result['success']) {
            Log::warning('Paymob Webhook Verification Failed: ' . ($result['message'] ?? 'Unknown Error'));
            return response()->json(['status' => 'error'], 400);
        }

        $transactionId = $result['transaction_id'];
        $orderId = $result['order_id'];

        // Find subscription by transaction ID or order ID if stored
        $subscription = Subscription::where('stripe_id', 'sub_paymob_' . $transactionId)->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'active',
                'stripe_status' => 'active'
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
                            $days = 150;
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
                                'stripe_id' => 'sub_paymob_' . $transactionId,
                                'stripe_status' => 'active',
                                'stripe_price' => 'price_paymob_' . ($package->slug ?? ($planSlug ?: 'unknown')),
                                'quantity' => 1,
                                'billing_cycle' => $billingCycle,
                                'base_price' => $totalAmount,
                                'total_amount' => $totalAmount,
                                'discount_amount' => 0,
                                'status' => 'active',
                                'ends_at' => now()->addDays($days),
                            ]
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
}
