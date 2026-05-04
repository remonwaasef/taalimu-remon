<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KlaviyoService
{
    protected $apiKey;
    protected $baseUrl = 'https://a.klaviyo.com/api';
    protected $revision = '2024-02-15';

    public function __construct()
    {
        $this->apiKey = config('services.klaviyo.key');
    }

    /**
     * Create or update a profile in Klaviyo.
     *
     * @param array $profileData
     * @return bool
     */
    public function syncProfile(array $profileData)
    {
        if (!$this->apiKey) {
            Log::warning('Klaviyo API key is missing. Skipping profile sync.');
            return false;
        }

        $endpoint = "{$this->baseUrl}/profile-import/"; // We use profile-import for create/update by email

        $payload = [
            'data' => [
                'type' => 'profile',
                'attributes' => $profileData
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => "Klaviyo-API-Key {$this->apiKey}",
                'revision' => $this->revision,
                'accept' => 'application/json',
            ])->post($endpoint, $payload);

            if ($response->successful()) {
                return true;
            }

            Log::error('Klaviyo Profile Sync Failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Klaviyo Profile Sync Exception', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Track a custom event in Klaviyo.
     *
     * @param string $eventName
     * @param array $profileAttributes
     * @param array $eventProperties
     * @return bool
     */
    public function trackEvent(string $eventName, array $profileAttributes, array $eventProperties = [])
    {
        if (!$this->apiKey) {
            return false;
        }

        $endpoint = "{$this->baseUrl}/events/";

        $payload = [
            'data' => [
                'type' => 'event',
                'attributes' => [
                    'profile' => $profileAttributes,
                    'metric' => [
                        'data' => [
                            'type' => 'metric',
                            'attributes' => [
                                'name' => $eventName
                            ]
                        ]
                    ],
                    'properties' => $eventProperties,
                    'time' => now()->toIso8601String()
                ]
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => "Klaviyo-API-Key {$this->apiKey}",
                'revision' => $this->revision,
                'accept' => 'application/json',
            ])->post($endpoint, $payload);

            if ($response->successful() || $response->status() === 202) {
                return true;
            }

            Log::error('Klaviyo Event Track Failed', [
                'event' => $eventName,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Klaviyo Event Track Exception', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }
}
