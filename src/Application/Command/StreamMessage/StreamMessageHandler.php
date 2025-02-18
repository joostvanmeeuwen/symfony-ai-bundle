<?php

namespace VanMeeuwen\SymfonyAI\Application\Command\StreamMessage;

use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Message;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Role;
use VanMeeuwen\SymfonyAI\Domain\AIProvider\AIProviderInterface;

final readonly class StreamMessageHandler
{
    public function __construct(
        private AIProviderInterface $aiProvider
    ) {
    }

    public function __invoke(StreamMessageCommand $command): void
    {
        if (!$this->aiProvider->supportsStreaming()) {
            throw new \RuntimeException('The current AI provider does not support streaming');
        }

        $message = Message::create(
            content: $command->getContent(),
            role: Role::from($command->getRole())
        );

        $conversation = $command->getConversationId()
            ? $this->aiProvider->getConversation($command->getConversationId())
            : null;

        $this->aiProvider->streamMessage(
            message: $message,
            onChunk: $command->getOnChunk(),
            conversation: $conversation
        );
    }
}