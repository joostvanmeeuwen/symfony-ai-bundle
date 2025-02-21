<?php

namespace VanMeeuwen\SymfonyAI\Tests\Unit\Domain\Model\Conversation;

use PHPUnit\Framework\TestCase;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Message;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Role;
use VanMeeuwen\SymfonyAI\Domain\Model\Parameters\AIParameters;

final class MessageTest extends TestCase
{
    public function testCreateMessage(): void
    {
        $content = 'Test message';
        $role = Role::USER;

        $message = Message::create($content, $role);

        $this->assertSame($content, $message->getContent());
        $this->assertSame($role, $message->getRole());
        $this->assertNotNull($message->getCreatedAt());
    }

    public function testCreateMessageWithParameters(): void
    {
        $parameters = AIParameters::creative();

        $message = Message::create(
            content: 'Test message',
            role: Role::USER,
            parameters: $parameters
        );

        $this->assertSame($parameters, $message->getParameters());
    }

    public function testCreateMessageWithId(): void
    {
        $id = 'test_id';

        $message = Message::create(
            content: 'Test message',
            role: Role::USER,
            id: $id
        );

        $this->assertSame($id, $message->getId());
    }
}