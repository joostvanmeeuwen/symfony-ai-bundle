<?php

namespace VanMeeuwen\SymfonyAI\Infrastructure\Provider\LMStudio\DTO;

final readonly class LMStudioResponse
{
    public function __construct(
        private string $content,
        private string $model,
        private ?int $promptTokens = null,
        private ?int $completionTokens = null,
        private ?int $totalTokens = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            content: $data['choices'][0]['message']['content'],
            model: $data['model'],
            promptTokens: $data['usage']['prompt_tokens'] ?? null,
            completionTokens: $data['usage']['completion_tokens'] ?? null,
            totalTokens: $data['usage']['total_tokens'] ?? null
        );
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getPromptTokens(): ?int
    {
        return $this->promptTokens;
    }

    public function getCompletionTokens(): ?int
    {
        return $this->completionTokens;
    }

    public function getTotalTokens(): ?int
    {
        return $this->totalTokens;
    }
}