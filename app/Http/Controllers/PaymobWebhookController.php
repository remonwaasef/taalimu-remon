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
            // If subscription doesn't exist yet (webhook arrived before redirect), 
            // we could potentially create it here if we have enough context 
            // (e.g., from custom_data or metadata in Paymob order)
            Log::info("Paymob Webhook: Subscription not found for Trans ID: {$transactionId}. It might be created during redirect.");
        }

        return response()->json(['status' => 'success']);
    }
}
