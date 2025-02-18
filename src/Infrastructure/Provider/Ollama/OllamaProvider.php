<?php

namespace VanMeeuwen\SymfonyAI\Infrastructure\Provider\Ollama;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Conversation;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Message;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Role;
use VanMeeuwen\SymfonyAI\Domain\Port\AIProviderInterface;
use VanMeeuwen\SymfonyAI\Infrastructure\Provider\Ollama\DTO\OllamaRequest;
use VanMeeuwen\SymfonyAI\Infrastructure\Provider\Ollama\DTO\OllamaResponse;

final class OllamaProvider implements AIProviderInterface
{
    private const DEFAULT_MODEL = 'llama2';

    /**
     * Active conversations/chat sessions
     * This is not for persistence, only for maintaining chat context during the session
     * @var array<string, Conversation>
     */
    private array $activeConversations = [];

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $baseUrl,
        private readonly string $model = self::DEFAULT_MODEL,
    ) {
    }

    public function sendMessage(Message $message, ?Conversation $conversation = null): Message
    {
        if ($conversation !== null && !isset($this->activeConversations[$conversation->getId()])) {
            $this->activeConversations[$conversation->getId()] = $conversation;
        }

        $systemMessage = null;
        $context = [];

        if ($conversation !== null) {
            foreach ($conversation->getMessages() as $historyMessage) {
                if ($historyMessage->getRole() === Role::SYSTEM) {
                    $systemMessage = $historyMessage->getContent();
                } else {
                    $context[] = sprintf(
                        "%s: %s",
                        $historyMessage->getRole()->value,
                        $historyMessage->getContent()
                    );
                }
            }

            $conversation->addMessage($message);
        }

        $request = new OllamaRequest(
            model: $this->model,
            prompt: $message->getContent(),
            system: $systemMessage,
            options: [
                'context' => $context,
            ]
        );

        $response = $this->httpClient->request(
            'POST',
            sprintf('%s/api/generate', rtrim($this->baseUrl, '/')),
            [
                'json' => $request->toArray(),
            ]
        );

        $ollamaResponse = OllamaResponse::fromArray($response->toArray());

        $responseMessage = Message::create(
            content: $ollamaResponse->getResponse(),
            role: Role::ASSISTANT
        );

        $conversation?->addMessage($responseMessage);

        return $responseMessage;
    }

    public function createConversation(): Conversation
    {
        $conversation = Conversation::create(
            id: uniqid('ollama_', true)
        );

        $this->activeConversations[$conversation->getId()] = $conversation;

        return $conversation;
    }

    public function getConversation(string $id): ?Conversation
    {
        return $this->activeConversations[$id] ?? null;
    }

    public function getProviderName(): string
    {
        return 'ollama';
    }

    public function supportsStreaming(): bool
    {
        return true;
    }
}