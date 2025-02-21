<?php

namespace VanMeeuwen\SymfonyAI\Application\Command\SendMessage;

use VanMeeuwen\SymfonyAI\Domain\Model\Parameters\AIParameters;

final readonly class SendMessageCommand
{
    public function __construct(
        private string $content,
        private string $role,
        private ?string $conversationId = null,
        private ?AIParameters $parameters = null
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

    public function getConversationId(): ?string
    {
        return $this->conversationId;
    }

    public function getParameters(): ?AIParameters
    {
        return $this->parameters;
    }
}