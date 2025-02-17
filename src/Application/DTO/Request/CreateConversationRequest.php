<?php

namespace VanMeeuwen\SymfonyAI\Application\DTO\Request;

final readonly class CreateConversationRequest
{
    public function __construct(
        #[Assert\Length(min: 3, max: 255)]
        private ?string $title = null,
    ) {
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}