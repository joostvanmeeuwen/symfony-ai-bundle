<?php

namespace VanMeeuwen\SymfonyAI\Tests\Unit\Domain\Model\Conversation;

use PHPUnit\Framework\TestCase;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Conversation;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Message;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Role;

final class ConversationTest extends TestCase
{
    public function testCreateConversation(): void
    {
        $id = 'test_conversation';
        $title = 'Test Conversation';

        $conversation = Conversation::create($id, $title);

        $this->assertSame($id, $conversation->getId());
        $this->assertSame($title, $conversation->getTitle());
        $this->assertNotNull($conversation->getCreatedAt());
        $this->assertEmpty($conversation->getMessages());
    }

    public function testCreateConversationWithoutTitle(): void
    {
        $id = 'test_conversation';

        $conversation = Conversation::create($id);

        $this->assertSame($id, $conversation->getId());
        $this->assertNull($conversation->getTitle());
    }

    public function testAddMessage(): void
    {
        $conversation = Conversation::create('test_conversation');

        $message1 = Message::create('Hello', Role::USER);
        $message2 = Message::create('Hi there', Role::ASSISTANT);

        $conversation->addMessage($message1);
        $conversation->addMessage($message2);

        $messages = $conversation->getMessages();

        $this->assertCount(2, $messages);
        $this->assertSame($message1, $messages[0]);
        $this->assertSame($message2, $messages[1]);
    }

    public function testMessagesOrder(): void
    {
        $conversation = Conversation::create('test_conversation');

        $message1 = Message::create('First message', Role::USER);
        $message2 = Message::create('Second message', Role::ASSISTANT);
        $message3 = Message::create('Third message', Role::USER);

        $conversation->addMessage($message1);
        $conversation->addMessage($message2);
        $conversation->addMessage($message3);

        $messages = $conversation->getMessages();

        $this->assertCount(3, $messages);
        $this->assertSame('First message', $messages[0]->getContent());
        $this->assertSame('Second message', $messages[1]->getContent());
        $this->assertSame('Third message', $messages[2]->getContent());
    }

    public function testSetTitle(): void
    {
        $conversation = Conversation::create('test_conversation');

        $this->assertNull($conversation->getTitle());

        $conversation->setTitle('New Title');

        $this->assertSame('New Title', $conversation->getTitle());
    }
}