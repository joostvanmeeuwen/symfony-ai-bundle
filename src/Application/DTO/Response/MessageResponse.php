<?php

namespace VanMeeuwen\SymfonyAI\Application\DTO\Response;

final readonly class MessageResponse
{
    public function __construct(
        private string $content,
        private string $role,
        private string $createdAt,
        private ?string $id = null,
    ) {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}