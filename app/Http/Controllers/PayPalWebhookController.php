<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayPalWebhookController extends Controller
{
    public function handle(Request $request, \App\Services\PayPalService $paypalService)
    {
        $payload = $request->all();

        // Verify webhook signature
        if (! $paypalService->verifyWebhook($request->headers->all(), $request->getContent())) {
            Log::warning('Invalid PayPal Webhook Signature', ['ip' => $request->ip()]);

            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $eventType = $payload['event_type'] ?? '';

        $safePayload = $request->except(['card_pan', 'source', 'cvv', 'token', 'payer.payer_info.tax_id_type']);
        Log::info('PayPal Webhook Received: '.$eventType, $safePayload);

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
                $daysToAdd = 30;
                if ($subscription->billing_cycle === 'term') {
                    $termDuration = (int) \App\Models\SiteSetting::get('term_duration_days', 150);
                    $daysToAdd = $termDuration;
                } elseif ($subscription->billing_cycle === 'yearly') {
                    $daysToAdd = 365;
                }

                $subscription->update([
                    'ends_at' => now()->addDays($daysToAdd),
                ]);
            }
        }
    }
}
