<?php

namespace VanMeeuwen\SymfonyAI\Application\Command\SendMessage;

use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Message;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Role;
use VanMeeuwen\SymfonyAI\Domain\Port\AIProviderInterface;
use VanMeeuwen\SymfonyAI\Application\DTO\Response\MessageResponse;
use VanMeeuwen\SymfonyAI\Application\Mapper\MessageMapper;

final readonly class SendMessageHandler
{
    public function __construct(
        private AIProviderInterface $aiProvider,
        private MessageMapper $messageMapper
    ) {
    }

    public function __invoke(SendMessageCommand $command): MessageResponse
    {
        $userMessage = Message::create(
            content: $command->getContent(),
            role: Role::from($command->getRole())
        );

        $responseMessage = $this->aiProvider->sendMessage(
            $userMessage,
            $command->getConversationId() ? $this->aiProvider->getConversation($command->getConversationId()) : null
        );

        return $this->messageMapper->toResponse($responseMessage);
    }
}