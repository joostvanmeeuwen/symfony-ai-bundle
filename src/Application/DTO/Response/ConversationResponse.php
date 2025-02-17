<?php

namespace VanMeeuwen\SymfonyAI\Application\DTO\Response;

final readonly class ConversationResponse
{
    /**
     * @param MessageResponse[] $messages
     */
    public function __construct(
        private string $id,
        private string $createdAt,
        private array $messages,
        private ?string $title = null,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /** @return MessageResponse[] */
    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}