<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1/models';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
    }

    /**
     * Generate content using Gemini Pro.
     */
    public function generate(string $prompt, string $systemInstruction = '')
    {
        $url = "{$this->baseUrl}/gemini-1.5-flash:generateContent?key={$this->apiKey}";

        // Merge instruction into prompt for maximum compatibility across v1 and v1beta
        $text = $systemInstruction
            ? "INSTRUCTIONS:\n{$systemInstruction}\n\nTASK:\n{$prompt}"
            : $prompt;

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $text]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => 2048,
            ]
        ];

        try {
            $response = Http::post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response generated.';
            }

            Log::error('Gemini API Error: ' . $response->body());
            return 'Error: ' . ($response->json()['error']['message'] ?? 'Unknown error occurred.');
        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: ' . $e->getMessage());
            return 'Exception: ' . $e->getMessage();
        }
    }

    /**
     * Specialized paraphraser.
     */
    public function paraphrase(string $text)
    {
        $system = "You are an expert academic writing assistant. Your task is to paraphrase the provided text to be more professional, clear, and academic while maintaining its original meaning. You should provide 2-3 variations. Respond in the same language as the input (Thai or English).";
        return $this->generate($text, $system);
    }
}
