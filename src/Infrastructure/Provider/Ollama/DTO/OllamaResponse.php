<?php

namespace VanMeeuwen\SymfonyAI\Infrastructure\Provider\Ollama\DTO;

final readonly class OllamaResponse
{
    public function __construct(
        private string $model,
        private string $response,
        private string $promptEval,
        private string $totalDuration,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            model: $data['model'],
            response: $data['response'],
            promptEval: $data['prompt_eval_duration'],
            totalDuration: $data['total_duration']
        );
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getPromptEval(): string
    {
        return $this->promptEval;
    }

    public function getTotalDuration(): string
    {
        return $this->totalDuration;
    }

}