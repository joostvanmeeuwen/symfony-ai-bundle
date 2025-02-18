<?php

namespace VanMeeuwen\SymfonyAI\Domain\Model\Conversation;

use DateTimeImmutable;
use VanMeeuwen\SymfonyAI\Domain\Model\Parameters\AIParameters;

final readonly class Message
{
    private function __construct(
        private string            $content,
        private Role              $role,
        private DateTimeImmutable $createdAt,
        private ?AIParameters     $parameters = null,
        private ?string           $id = null,
    ) {
    }

    public static function create(
        string $content,
        Role $role,
        ?AIParameters $parameters = null,
        ?string $id = null
    ): self {
        return new self(
            content: $content,
            role: $role,
            createdAt: new DateTimeImmutable(),
            parameters: $parameters ?? AIParameters::default(),
            id: $id
        );
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getParameters(): AIParameters
    {
        return $this->parameters ?? AIParameters::default();
    }

    public function withParameters(AIParameters $parameters): self
    {
        return new self(
            content: $this->content,
            role: $this->role,
            createdAt: $this->createdAt,
            parameters: $parameters,
            id: $this->id
        );
    }
}