<?php

namespace VanMeeuwen\SymfonyAI\Domain\Model\Conversation;

enum Role: string
{
    case SYSTEM = 'system';
    case USER = 'user';
    case ASSISTANT = 'assistant';
}