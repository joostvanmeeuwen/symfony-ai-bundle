<?php

namespace VanMeeuwen\SymfonyAI\Application\Command\SendMessage;

use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Message;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Role;
use VanMeeuwen\SymfonyAI\Domain\AIProvider\AIProviderInterface;
use VanMeeuwen\SymfonyAI\Application\DTO\Response\MessageResponse;
use VanMeeuwen\SymfonyAI\Application\Mapper\MessageMapper;
use VanMeeuwen\SymfonyAI\Domain\Model\Parameters\AIParameters;

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
            role: Role::from($command->getRole()),
            parameters: $command->getParameters() ?? AIParameters::default()
        );

        $responseMessage = $this->aiProvider->sendMessage(
            $userMessage,
            $command->getConversationId()
                ? $this->aiProvider->getConversation($command->getConversationId())
                : null
        );

        return $this->messageMapper->toResponse($responseMessage);
    }
}