<?php

namespace VanMeeuwen\SymfonyAI\Domain\Model\Conversation;

use DateTimeImmutable;

final readonly class Message
{
    private function __construct(
        private string            $content,
        private Role              $role,
        private DateTimeImmutable $createdAt,
        private ?string           $id = null,
    ) {
    }

    public static function create(
        string $content,
        Role $role,
        ?string $id = null
    ): self {
        return new self(
            content: $content,
            role: $role,
            createdAt: new DateTimeImmutable(),
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
}