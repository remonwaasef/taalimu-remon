<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContentAssistantService
{
    protected $apiKey;
    protected $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    /**
     * Generate course outline from topic
     */
    public function generateCourseOutline(string $topic, string $level = 'beginner'): array
    {
        $prompt = "Create a detailed course outline for: {$topic}\n";
        $prompt .= "Level: {$level}\n";
        $prompt .= "Include: Course title, description, 5-8 main sections with subsections.\n";
        $prompt .= "Format as structured text. Respond in Arabic.";

        return $this->callAPI($prompt);
    }

    /**
     * Generate quiz questions from topic
     */
    public function generateQuiz(string $topic, int $questionCount = 5): array
    {
        $prompt = "Create {$questionCount} multiple-choice quiz questions about: {$topic}\n";
        $prompt .= "For each question provide: question text, 4 options (A, B, C, D), and correct answer.\n";
        $prompt .= "Format clearly. Respond in Arabic.";

        return $this->callAPI($prompt);
    }

    /**
     * Improve course description
     */
    public function improveCourseDescription(string $currentDescription): array
    {
        $prompt = "Improve this course description to be more engaging and professional:\n\n";
        $prompt .= $currentDescription . "\n\n";
        $prompt .= "Keep the same language (Arabic or English) as the input.";

        return $this->callAPI($prompt);
    }

    /**
     * Call Gemini API
     */
    protected function callAPI(string $prompt): array
    {
        try {
            $response = Http::timeout(30)->post($this->apiUrl . '?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.8,
                    'maxOutputTokens' => 1500,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                return [
                    'success' => true,
                    'content' => trim($content),
                ];
            }

            Log::error('Gemini API Error', ['response' => $response->body()]);
            return [
                'success' => false,
                __('services.string_55'),
            ];

        } catch (\Exception $e) {
            Log::error('Content Assistant Error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                __('services.string_56'),
            ];
        }
    }
}
