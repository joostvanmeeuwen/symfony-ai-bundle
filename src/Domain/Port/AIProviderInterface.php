<?php

namespace VanMeeuwen\SymfonyAI\Domain\Port;

use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Message;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Conversation;

interface AIProviderInterface
{
    public function sendMessage(Message $message, ?Conversation $conversation = null): Message;

    public function createConversation(): Conversation;

    public function getProviderName(): string;

    public function supportsStreaming(): bool;
}