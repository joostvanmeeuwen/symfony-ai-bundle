<?php

namespace VanMeeuwen\SymfonyAI\Domain\AIProvider;

use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Message;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Conversation;

interface AIProviderInterface
{
    public function sendMessage(Message $message, ?Conversation $conversation = null): Message;

    public function streamMessage(Message $message, callable $onChunk, ?Conversation $conversation = null): void;

    public function createConversation(): Conversation;

    public function getConversation(string $id): ?Conversation;

    public function getProviderName(): string;

    public function supportsStreaming(): bool;
}