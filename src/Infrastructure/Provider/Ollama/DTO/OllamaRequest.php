<?php

namespace VanMeeuwen\SymfonyAI\Infrastructure\Provider\Ollama\DTO;

use VanMeeuwen\SymfonyAI\Domain\Model\Parameters\AIParameters;

final readonly class OllamaRequest
{
    public function __construct(
        private string $model,
        private string $prompt,
        private ?string $system = null,
        private ?AIParameters $parameters = null,
        private array $options = [],
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'model' => $this->model,
            'prompt' => $this->prompt,
        ];

        if ($this->system !== null) {
            $data['system'] = $this->system;
        }

        if ($this->parameters !== null) {
            $data['options'] = array_merge($this->options, [
                'temperature' => $this->parameters->getTemperature(),
                'top_p' => $this->parameters->getTopP(),
                'seed' => $this->parameters->getSeed(),
            ]);
        } else {
            $data['options'] = $this->options;
        }

        if ($this->parameters?->getMaxTokens() !== null) {
            $data['options']['num_predict'] = $this->parameters->getMaxTokens();
        }

        return $data;
    }
}