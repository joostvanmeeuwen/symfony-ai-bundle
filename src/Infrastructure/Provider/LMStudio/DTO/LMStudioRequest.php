<?php

namespace VanMeeuwen\SymfonyAI\Infrastructure\Provider\LMStudio\DTO;

final readonly class LMStudioRequest
{
    /**
     * @param array<array<string, string>> $messages
     */
    public function __construct(
        private array $messages,
        private string $model,
        private ?float $temperature = null,
        private ?float $topP = null,
        private ?int $maxTokens = null,
        private bool $stream = false,
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'model' => $this->model,
            'messages' => $this->messages,
            'stream' => $this->stream,
        ];

        if ($this->temperature !== null) {
            $data['temperature'] = $this->temperature;
        }

        if ($this->topP !== null) {
            $data['top_p'] = $this->topP;
        }

        if ($this->maxTokens !== null) {
            $data['max_tokens'] = $this->maxTokens;
        }

        return $data;
    }
}