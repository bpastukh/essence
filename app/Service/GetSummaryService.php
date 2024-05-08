<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class GetSummaryService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private string $apiToken)
    {
    }

    public function getSummary(array $urls): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiToken,
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You will be given an url or a bunch of urls separated by commas. You have to get articles from these urls and give a short summary of the article and tags. As a result, give me a json of objects with the following keys: url, title, summary, tags. If summary or tags are unavailable, use null as a value.'
                ],
                [
                    'role' => 'user',
                    'content' => implode(',', $urls)
                ]
            ],
            'temperature' => 0.5,
            'max_tokens' => 1500,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0
        ]);

        return json_decode($response->json()['choices'][0]['message']['content'], true);
    }
}
