<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;

class PayPalWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $eventType = $payload['event_type'] ?? '';

        Log::info('PayPal Webhook Received: ' . $eventType, $payload);

        switch ($eventType) {
            case 'BILLING.SUBSCRIPTION.ACTIVATED':
                $this->handleSubscriptionActivated($payload);
                break;
            case 'BILLING.SUBSCRIPTION.CANCELLED':
                $this->handleSubscriptionCancelled($payload);
                break;
            case 'BILLING.SUBSCRIPTION.EXPIRED':
                $this->handleSubscriptionExpired($payload);
                break;
            case 'PAYMENT.SALE.COMPLETED':
                $this->handleSaleCompleted($payload);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    protected function handleSubscriptionActivated($payload)
    {
        $paypalId = $payload['resource']['id'];
        $subscription = Subscription::where('paypal_id', $paypalId)->first();

        if ($subscription) {
            $subscription->update([
                'paypal_status' => 'active',
                'status' => 'active',
            ]);
        }
    }

    protected function handleSubscriptionCancelled($payload)
    {
        $paypalId = $payload['resource']['id'];
        $subscription = Subscription::where('paypal_id', $paypalId)->first();

        if ($subscription) {
            $subscription->update([
                'paypal_status' => 'cancelled',
                'status' => 'cancelled',
            ]);
        }
    }

    protected function handleSubscriptionExpired($payload)
    {
        $paypalId = $payload['resource']['id'];
        $subscription = Subscription::where('paypal_id', $paypalId)->first();

        if ($subscription) {
            $subscription->update([
                'paypal_status' => 'expired',
                'status' => 'expired',
            ]);
        }
    }

    protected function handleSaleCompleted($payload)
    {
        // For processing renewals
        $paypalId = $payload['resource']['billing_agreement_id'] ?? null;
        if ($paypalId) {
            $subscription = Subscription::where('paypal_id', $paypalId)->first();
            if ($subscription) {
                // Update ends_at based on payment period
                // Simplification for now
                $subscription->update([
                    'ends_at' => $subscription->billing_cycle === 'yearly' ? now()->addYear() : now()->addMonth(),
                ]);
            }
        }
    }
}
