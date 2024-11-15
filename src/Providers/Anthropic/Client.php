<?php

declare(strict_types=1);

namespace EchoLabs\Prism\Providers\Anthropic;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Client
{
    protected PendingRequest $client;

    /**
     * @param  array<string, mixed>  $options
     */
    public function __construct(
        public readonly string $apiKey,
        public readonly string $apiVersion,
        public readonly array $options = [],
    ) {
        $this->client = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'anthropic-version' => $this->apiVersion,
        ])
            ->withOptions($this->options)
            ->baseUrl('https://api.anthropic.com/v1');
    }

    /**
     * @param  array<int, mixed>  $messages
     * @param  array<int, mixed>|null  $tools
     * @param  array<string, mixed>|string|null  $toolChoice
     */
    public function messages(
        string $model,
        array $messages,
        ?int $maxTokens,
        int|float|null $temperature,
        int|float|null $topP,
        ?string $systemPrompt,
        ?array $tools,
        string|array|null $toolChoice,
    ): Response {
        return $this->client->post(
            'messages',
            array_merge([
                'model' => $model,
                'messages' => $messages,
                'max_tokens' => $maxTokens ?? 2048,
            ], array_filter([
                'system' => $systemPrompt,
                'temperature' => $temperature,
                'top_p' => $topP,
                'tools' => $tools,
                'tool_choice' => $toolChoice,
            ]))
        );
    }
}
