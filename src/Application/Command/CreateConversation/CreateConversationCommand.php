<?php

namespace VanMeeuwen\SymfonyAI\Application\Command\CreateConversation;

final readonly class CreateConversationCommand
{
    public function __construct(
        private ?string $title = null,
        private ?string $systemMessage = null
    ) {
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getSystemMessage(): ?string
    {
        return $this->systemMessage;
    }
}