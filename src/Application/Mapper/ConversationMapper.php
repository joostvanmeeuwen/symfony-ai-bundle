<?php

namespace VanMeeuwen\SymfonyAI\Application\Mapper;

use VanMeeuwen\SymfonyAI\Application\DTO\Response\ConversationResponse;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Conversation;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Message;

final readonly class ConversationMapper
{
    public function __construct(
        private MessageMapper $messageMapper
    ) {
    }

    public function toResponse(Conversation $conversation): ConversationResponse
    {
        return new ConversationResponse(
            id: $conversation->getId(),
            createdAt: $conversation->getCreatedAt()->format('c'),
            messages: array_map(
                fn (Message $message) => $this->messageMapper->toResponse($message),
                $conversation->getMessages()
            ),
            title: $conversation->getTitle(),
        );
    }
}