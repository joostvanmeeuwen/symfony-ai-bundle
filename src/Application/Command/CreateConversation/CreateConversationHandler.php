<?php

namespace VanMeeuwen\SymfonyAI\Application\Command\CreateConversation;

use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Message;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Role;
use VanMeeuwen\SymfonyAI\Domain\Port\AIProviderInterface;
use VanMeeuwen\SymfonyAI\Application\DTO\Response\ConversationResponse;
use VanMeeuwen\SymfonyAI\Application\Mapper\ConversationMapper;

final readonly class CreateConversationHandler
{
    public function __construct(
        private AIProviderInterface $aiProvider,
        private ConversationMapper $conversationMapper
    ) {
    }

    public function __invoke(CreateConversationCommand $command): ConversationResponse
    {
        $conversation = $this->aiProvider->createConversation();

        if ($command->getTitle() !== null) {
            $conversation->setTitle($command->getTitle());
        }

        if ($command->getSystemMessage() !== null) {
            $systemMessage = Message::create(
                content: $command->getSystemMessage(),
                role: Role::SYSTEM
            );
            $conversation->addMessage($systemMessage);
        }

        return $this->conversationMapper->toResponse($conversation);
    }
}