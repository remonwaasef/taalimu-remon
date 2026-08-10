<?php

namespace App\Services\PaymentGateways;

use App\Interfaces\PaymentGatewayInterface;
use App\Models\Package;
use App\Models\Tenant;
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
            if (isset($options['total_amount'])) {
                $amountInCents = $options['total_amount'] * 100;
            } else {
                if ($billingCycle === 'term') {
                    $amountInCents = ($package->term_price ?: ($package->price * 4)) * 100;
                } elseif ($billingCycle === 'yearly') {
                    $amountInCents = ($package->yearly_price ?: ($package->price * 10)) * 100;
                } else {
                    $amountInCents = $package->price * 100;
                }
            }

            $orderId = $this->createOrder($authToken, $amountInCents, $tenant, $package, $billingCycle);

            // 3. Payment Key Generation
            $paymentToken = $this->getPaymentKey($authToken, $orderId, $amountInCents, $tenant, $package);

            // 4. Return Redirect URL (assuming Card integration for now)
            return "https://accept.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentToken}";

        } catch (\Exception $e) {
            Log::error('Paymob Checkout Error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a Paymob iframe checkout URL for a student invoice (sale).
     * Merchant order id encodes: sale_{saleId}_{tenantId}_{paymentToken}
     * so both the redirect callback and the webhook can restore context.
     */
    public function createSaleCheckout(\App\Models\Sale $sale, string $paymentToken, float $amount = null, array $billingData = []): string
    {
        $amount = $amount ?? ($sale->total_amount - $sale->paid_amount);
        $amountInCents = (int) round($amount * 100);

        if ($amountInCents <= 0) {
            throw new \InvalidArgumentException('Invoice already fully paid.');
        }

        $customer = $sale->relationLoaded('student') ? $sale->student : $sale->student()->first();

        $billingData = array_merge([
            'first_name' => $customer?->name ?: 'طالب',
            'last_name' => 'Student',
            'email' => $customer?->email ?: ($sale->tenant?->email ?: 'student@example.com'),
            'phone_number' => $customer?->parent_phone ?: ($sale->tenant?->phone ?: '+201234567890'),
            'apartment' => 'NA',
            'floor' => 'NA',
            'street' => 'NA',
            'building' => 'NA',
            'shipping_method' => 'NA',
            'postal_code' => 'NA',
            'city' => 'NA',
            'country' => 'EG',
            'state' => 'NA',
        ], $billingData);

        // 1. Auth + 2. Order
        $authToken = $this->getAuthToken();

        $merchantOrderId = 'sale_'.$sale->id.'_'.$sale->tenant_id.'_'.$paymentToken;

        $response = Http::post("{$this->baseUrl}/ecommerce/orders", [
            'auth_token' => $authToken,
            'delivery_needed' => 'false',
            'amount_cents' => (string) $amountInCents,
            'currency' => 'EGP',
            'items' => [],
            'merchant_order_id' => $merchantOrderId,
        ]);

        if ($response->failed()) {
            throw new \Exception('Paymob Order Creation Failed for sale '.$sale->id);
        }

        $orderId = $response->json()['id'];

        // 3. Payment key
        $paymentResponse = Http::post("{$this->baseUrl}/acceptance/payment_keys", [
            'auth_token' => $authToken,
            'amount_cents' => (string) $amountInCents,
            'expiration' => 3600,
            'order_id' => $orderId,
            'billing_data' => $billingData,
            'currency' => 'EGP',
            'integration_id' => $this->cardIntegrationId,
        ]);

        if ($paymentResponse->failed()) {
            throw new \Exception('Paymob Payment Key Generation Failed for sale '.$sale->id);
        }

        // 4. Iframe URL
        return "https://accept.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentResponse->json()['token']}";
    }

    /**
     * Parse a sale payment merchant_order_id back into its parts.
     * Format: sale_{saleId}_{tenantId}_{paymentToken}
     */
    public function parseSaleOrderId(?string $merchantOrderId): ?array
    {
        if (! $merchantOrderId || ! str_starts_with($merchantOrderId, 'sale_')) {
            return null;
        }

        $parts = explode('_', $merchantOrderId);
        if (count($parts) !== 4) {
            return null;
        }

        return [
            'sale_id' => (int) $parts[1],
            'tenant_id' => (int) $parts[2],
            'payment_token' => $parts[3],
        ];
    }

    protected function getAuthToken()
    {
        $apiKey = $this->apiKey;

        Log::info('Paymob Auth Request', [
            'url' => "{$this->baseUrl}/auth/tokens",
            'api_key_length' => strlen($apiKey),
        ]);

        $response = Http::post("{$this->baseUrl}/auth/tokens", [
            'api_key' => $apiKey,
        ]);

        Log::info('Paymob Auth Response', [
            'status' => $response->status(),
            'body' => $response->successful() ? 'SUCCESS' : substr($response->body(), 0, 500),
        ]);

        if ($response->failed()) {
            throw new \Exception('Paymob Authentication Failed: '.$response->body());
        }

        return $response->json()['token'];
    }

    protected function createOrder($token, $amountInCents, $tenant, $package = null, $billingCycle = null)
    {
        $packageSlug = $package ? $package->slug : session('selected_plan', 'pro');
        $billingCycle = $billingCycle ?: session('billing_cycle', 'monthly');
        $isChange = session('is_subscription_change') ? '1' : '0';

        // we encode business context into merchant_order_id to recover it if session is lost
        $context = implode('_', [
            $tenant->id,
            $packageSlug,
            $billingCycle,
            $isChange,
        ]);

        $merchantOrderId = 'tx_'.time().'_'.$context;

        $response = Http::post("{$this->baseUrl}/ecommerce/orders", [
            'auth_token' => $token,
            'delivery_needed' => 'false',
            'amount_cents' => (string) $amountInCents,
            'currency' => 'EGP',
            'items' => [],
            'merchant_order_id' => $merchantOrderId,
        ]);

        if ($response->failed()) {
            throw new \Exception('Paymob Order Creation Failed');
        }

        $orderId = $response->json()['id'];

        // PAY-2: persist the HMAC-covered Paymob order id → context map so the
        // webhook can verify subscription activations against server-side truth
        // instead of the unsigned merchant_order_id.
        \App\Models\OnlineCheckout::updateOrCreate(
            ['paymob_order_id' => $orderId],
            [
                'tenant_id' => $tenant->id,
                'package_slug' => $packageSlug,
                'billing_cycle' => $billingCycle,
                'is_change' => $isChange === '1',
                'amount_cents' => (int) $amountInCents,
                'merchant_order_id' => $merchantOrderId,
                'status' => 'pending',
            ]
        );

        return $orderId;
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
                'state' => 'NA',
            ],
            'currency' => 'EGP',
            'integration_id' => $this->cardIntegrationId,
        ]);

        if ($response->failed()) {
            throw new \Exception('Paymob Payment Key Generation Failed');
        }

        return $response->json()['token'];
    }

    /**
     * Verify HMAC for Paymob Redirect (Client Side)
     * Uses Paymob's official field ordering (alphabetical) and strict type handling.
     */
    public function verifyRedirectHmac(array $data): bool
    {
        if (! isset($data['hmac'])) {
            Log::channel('security')->warning('Paymob Redirect: Missing HMAC field', [
                'ip' => request()->ip(),
            ]);

            return false;
        }

        // Paymob's official alphabetically-ordered fields for redirect HMAC
        $fields = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];

        $source = '';
        foreach ($fields as $field) {
            $val = $data[$field] ?? '';
            // Paymob sends booleans as strings 'true'/'false' in redirect
            $source .= $this->normalizeHmacValue($val);
        }

        $calculated = hash_hmac('sha512', $source, $this->hmacSecret);

        $isValid = hash_equals($calculated, $data['hmac']);

        if (! $isValid) {
            Log::channel('security')->warning('Paymob Redirect HMAC Verification Failed', [
                'ip' => request()->ip(),
                'transaction_id' => $data['id'] ?? 'unknown',
                'amount_cents' => $data['amount_cents'] ?? 'unknown',
            ]);
        }

        return $isValid;
    }

    /**
     * Normalize a value for HMAC concatenation.
     * Handles booleans, nulls, and type inconsistencies from Paymob.
     */
    protected function normalizeHmacValue($val): string
    {
        if (is_bool($val)) {
            return $val ? 'true' : 'false';
        }
        if ($val === null) {
            return '';
        }

        // Paymob sometimes sends 'true'/'false' as strings
        return (string) $val;
    }

    public function handleCallback(array $payload): array
    {
        // Fail-closed: if payload structure is invalid, deny immediately
        if (! isset($payload['obj']) || ! is_array($payload['obj']) || ! isset($payload['hmac'])) {
            Log::channel('security')->warning('Paymob Webhook: Malformed payload structure', [
                'ip' => request()->ip(),
            ]);

            return ['success' => false, 'message' => 'Malformed payload'];
        }

        $data = $payload['obj'];

        // Validate required nested fields exist before accessing them
        if (! isset($data['order']['id']) || ! isset($data['source_data']['pan'])
            || ! isset($data['source_data']['sub_type']) || ! isset($data['source_data']['type'])) {
            Log::channel('security')->warning('Paymob Webhook: Missing required nested fields', [
                'ip' => request()->ip(),
            ]);

            return ['success' => false, 'message' => 'Missing required fields'];
        }

        // Paymob standard HMAC verification logic for Transaction Processed webhook
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
            $data['success'],
        ];

        $hmacSource = '';
        foreach ($hmacFields as $field) {
            $hmacSource .= $this->normalizeHmacValue($field);
        }

        $calculatedHmac = hash_hmac('sha512', $hmacSource, $this->hmacSecret);

        if (! hash_equals($calculatedHmac, $payload['hmac'])) {
            Log::channel('security')->error('Paymob Webhook HMAC Mismatch - Possible Tampering', [
                'ip' => request()->ip(),
                'transaction_id' => $data['id'] ?? 'unknown',
            ]);

            return ['success' => false, 'message' => 'HMAC Mismatch'];
        }

        return [
            'success' => ($data['success'] === 'true' || $data['success'] === true),
            'transaction_id' => $data['id'],
            'order_id' => $data['order']['id'],
            'merchant_order_id' => $data['order']['merchant_order_id'] ?? null,
            'amount' => $data['amount_cents'] / 100,
        ];
    }

    public function getName(): string
    {
        return 'paymob';
    }
}
