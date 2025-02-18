<?php

namespace VanMeeuwen\SymfonyAI\Domain\Model\Conversation;

use DateTimeImmutable;

final class Conversation
{
    /** @var Message[] */
    private array $messages = [];

    private function __construct(
        private readonly string $id,
        private readonly DateTimeImmutable $createdAt,
        private ?string $title = null,
    ) {
    }

    public static function create(
        string $id,
        ?string $title = null
    ): self {
        return new self(
            id: $id,
            createdAt: new DateTimeImmutable(),
            title: $title
        );
    }

    public function addMessage(Message $message): void
    {
        $this->messages[] = $message;
    }

    /** @return Message[] */
    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
