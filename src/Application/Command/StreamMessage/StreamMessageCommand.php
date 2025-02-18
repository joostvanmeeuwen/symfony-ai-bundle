<?php

namespace VanMeeuwen\SymfonyAI\Application\Command\StreamMessage;

final readonly class StreamMessageCommand
{
    public function __construct(
        private string $content,
        private string $role,
        private \Closure $onChunk,
        private ?string $conversationId = null,
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

    public function getOnChunk(): callable
    {
        return $this->onChunk;
    }

    public function getConversationId(): ?string
    {
        return $this->conversationId;
    }
}