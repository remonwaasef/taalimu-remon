<?php

namespace App\Services\PaymentGateways;

use App\Interfaces\PaymentGatewayInterface;
use App\Models\Tenant;
use App\Models\Package;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymobGateway implements PaymentGatewayInterface
{
    protected $apiKey;
    protected $hmacSecret;
    protected $merchantId;
    protected $cardIntegrationId;
    protected $walletIntegrationId;
    protected $iframeId;
    protected $baseUrl = 'https://accept.paymob.com/api';

    public function __construct()
    {
        $this->apiKey = config('services.paymob.api_key');
        $this->hmacSecret = config('services.paymob.hmac_secret');
        $this->merchantId = config('services.paymob.merchant_id');
        $this->cardIntegrationId = config('services.paymob.card_integration_id');
        $this->walletIntegrationId = config('services.paymob.wallet_integration_id');
        $this->iframeId = config('services.paymob.iframe_id');
    }

    public function createCheckoutSession(Tenant $tenant, Package $package, string $billingCycle, array $options = []): string
    {
        try {
            // 1. Authentication Request
            $authToken = $this->getAuthToken();

            // 2. Order Registration
            $amountInCents = ($options['total_amount'] ?? ($billingCycle === 'yearly' ? $package->yearly_price : $package->price)) * 100;
            $orderId = $this->createOrder($authToken, $amountInCents, $tenant);

            // 3. Payment Key Generation
            $paymentToken = $this->getPaymentKey($authToken, $orderId, $amountInCents, $tenant, $package);

            // 4. Return Redirect URL (assuming Card integration for now)
            return "https://accept.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentToken}";

        } catch (\Exception $e) {
            Log::error('Paymob Checkout Error: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function getAuthToken()
    {
        $apiKey = $this->apiKey;

        Log::info('Paymob Auth Request', [
            'url' => "{$this->baseUrl}/auth/tokens",
            'api_key_length' => strlen($apiKey),
        ]);

        $response = Http::post("{$this->baseUrl}/auth/tokens", [
            'api_key' => $apiKey
        ]);

        Log::info('Paymob Auth Response', [
            'status' => $response->status(),
            'body' => $response->successful() ? 'SUCCESS' : substr($response->body(), 0, 500),
        ]);

        if ($response->failed()) {
            throw new \Exception('Paymob Authentication Failed: ' . $response->body());
        }

        return $response->json()['token'];
    }

    protected function createOrder($token, $amountInCents, $tenant)
    {
        $response = Http::post("{$this->baseUrl}/ecommerce/orders", [
            'auth_token' => $token,
            'delivery_needed' => 'false',
            'amount_cents' => (string) $amountInCents,
            'currency' => 'EGP',
            'items' => [],
            'merchant_order_id' => 'order_' . time() . '_' . $tenant->id,
        ]);

        if ($response->failed()) {
            throw new \Exception('Paymob Order Creation Failed');
        }

        return $response->json()['id'];
    }

    protected function getPaymentKey($token, $orderId, $amountInCents, $tenant, $package)
    {
        $response = Http::post("{$this->baseUrl}/acceptance/payment_keys", [
            'auth_token' => $token,
            'amount_cents' => (string) $amountInCents,
            'expiration' => 3600,
            'order_id' => $orderId,
            'billing_data' => [
                'first_name' => $tenant->name ?: 'Customer',
                'last_name' => 'User',
                'email' => $tenant->email ?: 'customer@example.com',
                'phone_number' => $tenant->phone ?: '+201234567890',
                'apartment' => 'NA',
                'floor' => 'NA',
                'street' => 'NA',
                'building' => 'NA',
                'shipping_method' => 'NA',
                'postal_code' => 'NA',
                'city' => 'NA',
                'country' => 'EG',
                'state' => 'NA'
            ],
            'currency' => 'EGP',
            'integration_id' => $this->cardIntegrationId,
        ]);

        if ($response->failed()) {
            throw new \Exception('Paymob Payment Key Generation Failed');
        }

        return $response->json()['token'];
    }

    public function handleCallback(array $payload): array
    {
        // Paymob standard HMAC verification logic for Transaction Processed webhook
        $data = $payload['obj'];
        
        $hmacFields = [
            $data['amount_cents'],
            $data['created_at'],
            $data['currency'],
            $data['error_occured'],
            $data['has_parent_transaction'],
            $data['id'],
            $data['integration_id'],
            $data['is_3d_secure'],
            $data['is_auth'],
            $data['is_capture'],
            $data['is_refunded'],
            $data['is_standalone_payment'],
            $data['is_voided'],
            $data['order']['id'],
            $data['owner'],
            $data['pending'],
            $data['source_data']['pan'],
            $data['source_data']['sub_type'],
            $data['source_data']['type'],
            $data['success']
        ];

        $hmacSource = '';
        foreach ($hmacFields as $field) {
            if (is_bool($field)) {
                $hmacSource .= $field ? 'true' : 'false';
            } else {
                $hmacSource .= $field;
            }
        }

        $calculatedHmac = hash_hmac('sha512', $hmacSource, $this->hmacSecret);

        if ($calculatedHmac !== $payload['hmac']) {
            Log::error('Paymob HMAC Mismatch', [
                'expected' => $payload['hmac'],
                'calculated' => $calculatedHmac,
                'source' => $hmacSource
            ]);
            return ['success' => false, 'message' => 'HMAC Mismatch'];
        }

        return [
            'success' => ($data['success'] === 'true' || $data['success'] === true),
            'transaction_id' => $data['id'],
            'order_id' => $data['order']['id'],
            'amount' => $data['amount_cents'] / 100
        ];
    }

    public function getName(): string
    {
        return 'paymob';
    }
}
