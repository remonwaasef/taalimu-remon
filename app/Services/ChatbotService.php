<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    protected $apiKey;

    protected $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    /**
     * Get AI response for a user message
     */
    public function chat(string $message, array $context = []): array
    {
        try {
            // Build system context
            $systemPrompt = 'You are a helpful educational assistant for a Learning Management System. ';
            $systemPrompt .= 'Answer questions about courses, payments, technical support, and general inquiries. ';
            $systemPrompt .= 'Keep responses concise, friendly, and helpful. Respond in Arabic if the question is in Arabic.';

            // Add context if provided
            if (! empty($context)) {
                $systemPrompt .= "\n\nContext: ".json_encode($context);
            }

            $fullPrompt = $systemPrompt."\n\nUser: ".$message."\n\nAssistant:";

            $response = Http::timeout(30)->withHeaders(['x-goog-api-key' => $this->apiKey])->post($this->apiUrl, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $fullPrompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 500,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Sorry, I could not generate a response.';

                return [
                    'success' => true,
                    'message' => trim($reply),
                ];
            }

            Log::error('Gemini API Error', ['response' => $response->body()]);

            return [
                'success' => false,
                'message' => 'Chatbot API error. Please try again later.',
            ];

        } catch (\Exception $e) {
            Log::error('Chatbot Error', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'An unexpected error occurred.',
            ];
        }
    }
}
