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

            $paymobUrl = "https://accept.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentToken}";
            session(['paymob_redirect_url' => $paymobUrl]);
            return route('payment.paymob.checkout');

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
            $isChange
        ]);

        $response = Http::post("{$this->baseUrl}/ecommerce/orders", [
            'auth_token' => $token,
            'delivery_needed' => 'false',
            'amount_cents' => (string) $amountInCents,
            'currency' => 'EGP',
            'items' => [],
            'merchant_order_id' => 'tx_' . time() . '_' . $context,
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

    /**
     * Verify HMAC for Paymob Redirect (Client Side)
     */
    public function verifyRedirectHmac(array $data): bool
    {
        if (!isset($data['hmac'])) return false;

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
            'success'
        ];

        $source = '';
        foreach ($fields as $field) {
            $val = $data[$field] ?? null;
            if (is_bool($val)) {
                $source .= $val ? 'true' : 'false';
            } else {
                $source .= $val;
            }
        }

        $calculated = hash_hmac('sha512', $source, $this->hmacSecret);
        
        return hash_equals($calculated, $data['hmac']);
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

        if (!hash_equals($calculatedHmac, $payload['hmac'])) {
            Log::error('Paymob HMAC Mismatch (Webhook)', [
                'expected' => $payload['hmac'],
                'calculated' => $calculatedHmac,
            ]);
            return ['success' => false, 'message' => 'HMAC Mismatch'];
        }

        return [
            'success' => ($data['success'] === 'true' || $data['success'] === true),
            'transaction_id' => $data['id'],
            'order_id' => $data['order']['id'],
            'merchant_order_id' => $data['order']['merchant_order_id'] ?? null,
            'amount' => $data['amount_cents'] / 100
        ];
    }

    public function getName(): string
    {
        return 'paymob';
    }
}
