<?php

declare(strict_types=1);

namespace VanMeeuwen\SymfonyAI\Domain\Model\Parameters;

final readonly class AIParameters
{
    public function __construct(
        private float $temperature = 1.0,
        private ?int $maxTokens = null,
        private ?float $topP = null,
        private ?int $presencePenalty = null,
        private ?int $frequencyPenalty = null,
        private ?int $seed = null,
    ) {
        if ($temperature < 0.0 || $temperature > 2.0) {
            throw new \InvalidArgumentException('Temperature must be between 0.0 and 2.0');
        }

        if ($topP !== null && ($topP < 0.0 || $topP > 1.0)) {
            throw new \InvalidArgumentException('Top P must be between 0.0 and 1.0');
        }

        if ($presencePenalty !== null && ($presencePenalty < -2 || $presencePenalty > 2)) {
            throw new \InvalidArgumentException('Presence penalty must be between -2 and 2');
        }

        if ($frequencyPenalty !== null && ($frequencyPenalty < -2 || $frequencyPenalty > 2)) {
            throw new \InvalidArgumentException('Frequency penalty must be between -2 and 2');
        }
    }

    /**
     * Balanced settings for general use
     */
    public static function default(): self
    {
        return new self(
            temperature: 0.7,
            topP: 0.8,
            presencePenalty: 0,
            frequencyPenalty: 0
        );
    }

    /**
     * High creativity for storytelling, brainstorming, etc.
     */
    public static function creative(): self
    {
        return new self(
            temperature: 1.5,
            topP: 0.9,
            presencePenalty: 1,
            frequencyPenalty: 1
        );
    }

    /**
     * Low creativity for factual responses, coding, etc.
     */
    public static function precise(): self
    {
        return new self(
            temperature: 0.2,
            topP: 0.1,
            presencePenalty: 0,
            frequencyPenalty: 0
        );
    }

    /**
     * Natural, conversational responses
     */
    public static function conversational(): self
    {
        return new self(
            temperature: 0.8,
            maxTokens: 150,
            topP: 0.9,
            presencePenalty: 1,
            frequencyPenalty: 1
        );
    }

    /**
     * Short, concise responses
     */
    public static function concise(): self
    {
        return new self(
            temperature: 0.5,
            maxTokens: 50,
            topP: 0.5,
            presencePenalty: 0,
            frequencyPenalty: 1
        );
    }

    /**
     * For code generation and technical responses
     */
    public static function technical(): self
    {
        return new self(
            temperature: 0.3,
            topP: 0.2,
            presencePenalty: 0,
            frequencyPenalty: 1
        );
    }

    /**
     * For brainstorming and idea generation
     */
    public static function brainstorm(): self
    {
        return new self(
            temperature: 1.8,
            topP: 0.9,
            presencePenalty: 2,
            frequencyPenalty: 1
        );
    }

    /**
     * For professional/formal communication
     */
    public static function professional(): self
    {
        return new self(
            temperature: 0.6,
            topP: 0.7,
            presencePenalty: 1,
            frequencyPenalty: 1
        );
    }

    /**
     * For explaining complex topics simply
     */
    public static function explanatory(): self
    {
        return new self(
            temperature: 0.5,
            maxTokens: 300,
            topP: 0.8,
            presencePenalty: 1,
            frequencyPenalty: 1
        );
    }

    /**
     * For step-by-step instructions or tutorials
     */
    public static function instructional(): self
    {
        return new self(
            temperature: 0.4,
            topP: 0.6,
            presencePenalty: 1,
            frequencyPenalty: 1
        );
    }

    // Getters en with() methode blijven hetzelfde
    public function getTemperature(): float
    {
        return $this->temperature;
    }

    public function getMaxTokens(): ?int
    {
        return $this->maxTokens;
    }

    public function getTopP(): ?float
    {
        return $this->topP;
    }

    public function getPresencePenalty(): ?int
    {
        return $this->presencePenalty;
    }

    public function getFrequencyPenalty(): ?int
    {
        return $this->frequencyPenalty;
    }

    public function getSeed(): ?int
    {
        return $this->seed;
    }

    public function with(array $parameters): self
    {
        return new self(
            temperature: $parameters['temperature'] ?? $this->temperature,
            maxTokens: $parameters['maxTokens'] ?? $this->maxTokens,
            topP: $parameters['topP'] ?? $this->topP,
            presencePenalty: $parameters['presencePenalty'] ?? $this->presencePenalty,
            frequencyPenalty: $parameters['frequencyPenalty'] ?? $this->frequencyPenalty,
            seed: $parameters['seed'] ?? $this->seed,
        );
    }
}