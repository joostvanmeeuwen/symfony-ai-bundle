<?php

namespace VanMeeuwen\SymfonyAI\Application\Mapper;

use VanMeeuwen\SymfonyAI\Application\DTO\Response\MessageResponse;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Message;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Role;

final class MessageMapper
{
    public function toResponse(Message $message): MessageResponse
    {
        return new MessageResponse(
            content: $message->getContent(),
            role: $message->getRole()->value,
            createdAt: $message->getCreatedAt()->format('c'),
            id: $message->getId(),
        );
    }

    public function toDomain(string $content, string $role): Message
    {
        return Message::create(
            content: $content,
            role: Role::from($role)
        );
    }
}