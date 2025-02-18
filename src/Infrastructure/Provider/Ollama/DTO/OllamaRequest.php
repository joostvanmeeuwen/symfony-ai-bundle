<?php

namespace VanMeeuwen\SymfonyAI\Infrastructure\Provider\Ollama\DTO;

final readonly class OllamaRequest
{
    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        private string $model,
        private string $prompt,
        private ?string $system = null,
        private array $options = [],
        private bool $stream = false,
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'model' => $this->model,
            'prompt' => $this->prompt,
            'stream' => $this->stream,
        ];

        if ($this->system !== null) {
            $data['system'] = $this->system;
        }


        if (!empty($this->options)) {
            $data = array_merge($data, $this->options);
        }

        return $data;
    }
}