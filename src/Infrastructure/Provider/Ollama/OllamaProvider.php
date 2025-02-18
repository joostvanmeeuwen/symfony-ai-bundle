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

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $baseUrl,
        private readonly string $model = self::DEFAULT_MODEL,
    ) {
    }

    public function sendMessage(Message $message, ?Conversation $conversation = null): Message
    {
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

        return Message::create(
            content: $ollamaResponse->getResponse(),
            role: Role::ASSISTANT
        );
    }

    public function createConversation(): Conversation
    {
        return Conversation::create(
            id: uniqid('ollama_', true)
        );
    }

    public function getProviderName(): string
    {
        return 'ollama';
    }

    public function supportsStreaming(): bool
    {
        return true;
    }

    public function getConversation(string $id): ?Conversation
    {
        // TODO: Implement getConversation() method.
    }


}