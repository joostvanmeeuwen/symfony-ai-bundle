<?php

namespace VanMeeuwen\SymfonyAI\Application\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class SendMessageRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1)]
        private string $content,

        #[Assert\NotBlank]
        private string $role,

        private ?string $conversationId = null,
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
}
