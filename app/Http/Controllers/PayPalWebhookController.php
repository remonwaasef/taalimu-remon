<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        // Renewal payments. Idempotent: each sale is recorded once in
        // subscription_logs (operation_type=renewal, transaction_id=sale id),
        // so PayPal webhook retries cannot extend ends_at multiple times.
        $paypalId = $payload['resource']['billing_agreement_id'] ?? null;
        $saleId = $payload['resource']['id'] ?? null;

        if (! $paypalId || ! $saleId) {
            return;
        }

        $subscription = Subscription::where('paypal_id', $paypalId)->first();
        if (! $subscription) {
            Log::warning('PayPal renewal: no subscription for agreement', ['billing_agreement_id' => $paypalId]);

            return;
        }

        DB::transaction(function () use ($subscription, $saleId) {
            $sub = Subscription::query()->whereKey($subscription->id)->lockForUpdate()->first();
            if (! $sub) {
                return;
            }

            $alreadyProcessed = SubscriptionLog::query()
                ->where('tenant_id', $sub->tenant_id)
                ->where('gateway', 'paypal')
                ->where('operation_type', 'renewal')
                ->where('transaction_id', $saleId)
                ->exists();

            if ($alreadyProcessed) {
                Log::info('PayPal renewal skipped (already processed)', ['sale_id' => $saleId]);

                return;
            }

            $daysToAdd = 30;
            if ($sub->billing_cycle === 'term') {
                $daysToAdd = (int) \App\Models\SiteSetting::get('term_duration_days', 150);
            } elseif ($sub->billing_cycle === 'yearly') {
                $daysToAdd = 365;
            }

            $renewalCount = SubscriptionLog::query()
                ->where('tenant_id', $sub->tenant_id)
                ->where('gateway', 'paypal')
                ->where('operation_type', 'renewal')
                ->count();

            if ($sub->ends_at && $sub->ends_at->isFuture() && $renewalCount === 0) {
                // First sale payment corresponds to the period already credited
                // by the redirect callback — record it, do not extend twice.
                $startsAt = $sub->ends_at->copy();
                $endsAt = $sub->ends_at->copy();
            } else {
                // True renewal (or the very first period when no redirect
                // callback ever credited it): extend from the current end.
                $startsAt = $sub->ends_at && $sub->ends_at->isFuture() ? $sub->ends_at->copy() : now();
                $endsAt = $startsAt->copy()->addDays($daysToAdd);
                $sub->update(['ends_at' => $endsAt]);
            }

            SubscriptionLog::logOperation(
                $sub->tenant_id,
                'renewal',
                $sub->stripe_price,
                $sub->type_label,
                $sub->billing_cycle,
                'paypal',
                (float) ($payload['resource']['amount']['total'] ?? 0),
                $saleId,
                $startsAt,
                $endsAt
            );
        });
    }
}
