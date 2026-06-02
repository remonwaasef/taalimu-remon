<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    protected $client;
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
        $this->baseUrl = config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
        ]);
    }

    protected function getAccessToken()
    {
        $response = $this->client->post('/v1/oauth2/token', [
            'auth' => [$this->clientId, $this->clientSecret],
            'form_params' => [
                'grant_type' => 'client_credentials',
            ],
        ]);

        return json_decode($response->getBody(), true)['access_token'];
    }

    public function createSubscription($planId, $subscriberInfo, $returnUrl, $cancelUrl)
    {
        try {
            $token = $this->getAccessToken();

            $response = $this->client->post('/v1/billing/subscriptions', [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'plan_id' => $planId,
                    'subscriber' => [
                        'name' => [
                            'given_name' => $subscriberInfo['name'] ?? 'Customer',
                        ],
                        'email_address' => $subscriberInfo['email'],
                    ],
                    'application_context' => [
                        'brand_name' => config('app.name'),
                        'locale' => 'en-US',
                        'shipping_preference' => 'NO_SHIPPING',
                        'user_action' => 'SUBSCRIBE_NOW',
                        'payment_method' => [
                            'payer_selected' => 'PAYPAL',
                            'payee_preferred' => 'IMMEDIATE_PAYMENT_CAPABILITY',
                        ],
                        'return_url' => $returnUrl,
                        'cancel_url' => $cancelUrl,
                    ],
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('PayPal Subscription Creation Error: ' . $e->getMessage());
            return null;
        }
    }

    public function getSubscriptionDetails($subscriptionId)
    {
        try {
            $token = $this->getAccessToken();

            $response = $this->client->get("/v1/billing/subscriptions/{$subscriptionId}", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('PayPal Subscription Details Error: ' . $e->getMessage());
            return null;
        }
    }

    public function createOrder($amount, $currency, $returnUrl, $cancelUrl)
    {
        try {
            $token = $this->getAccessToken();

            $response = $this->client->post('/v2/checkout/orders', [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [
                        [
                            'amount' => [
                                'currency_code' => $currency,
                                'value' => (string) $amount,
                            ],
                        ],
                    ],
                    'application_context' => [
                        'brand_name' => config('app.name'),
                        'landing_page' => 'BILLING',
                        'user_action' => 'PAY_NOW',
                        'return_url' => $returnUrl,
                        'cancel_url' => $cancelUrl,
                    ],
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('PayPal Order Creation Error: ' . $e->getMessage());
            if (method_exists($e, 'getResponse') && $e->getResponse()) {
                Log::error('PayPal Error Response: ' . $e->getResponse()->getBody()->getContents());
            }
            return null;
        }
    }

    public function captureOrder($orderId)
    {
        try {
            $token = $this->getAccessToken();

            $response = $this->client->post("/v2/checkout/orders/{$orderId}/capture", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type' => 'application/json',
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('PayPal Order Capture Error: ' . $e->getMessage());
            return null;
        }
    }

    public function verifyWebhook($headers, $body)
    {
        try {
            $token = $this->getAccessToken();
            $webhookId = config('services.paypal.webhook_id');

            if (!$webhookId) {
                Log::warning('PayPal Webhook ID is not configured.');
                return false;
            }

            $response = $this->client->post('/v1/notifications/verify-webhook-signature', [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'auth_algo' => $headers['paypal-auth-algo'][0] ?? '',
                    'cert_url' => $headers['paypal-cert-url'][0] ?? '',
                    'transmission_id' => $headers['paypal-transmission-id'][0] ?? '',
                    'transmission_sig' => $headers['paypal-transmission-sig'][0] ?? '',
                    'transmission_time' => $headers['paypal-transmission-time'][0] ?? '',
                    'webhook_id' => $webhookId,
                    'webhook_event' => json_decode($body, true),
                ],
            ]);

            $result = json_decode($response->getBody(), true);
            return isset($result['verification_status']) && $result['verification_status'] === 'SUCCESS';
        } catch (\Exception $e) {
            Log::error('PayPal Webhook Verification Error: ' . $e->getMessage());
            return false;
        }
    }
}
