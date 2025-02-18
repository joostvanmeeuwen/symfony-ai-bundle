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
        $request = $this->createRequest($message, $conversation);

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
            role: Role::ASSISTANT,
            parameters: $message->getParameters()
        );
    }

    public function streamMessage(Message $message, callable $onChunk, ?Conversation $conversation = null): void
    {
        if (!$this->supportsStreaming()) {
            throw new \RuntimeException('Streaming is not supported by this provider');
        }

        $request = $this->createRequest($message, $conversation, true);

        $response = $this->httpClient->request(
            'POST',
            sprintf('%s/api/generate', rtrim($this->baseUrl, '/')),
            [
                'json' => $request->toArray(),
            ]
        );

        $stream = $this->httpClient->stream([$response]);

        $buffer = '';
        foreach ($stream as $chunk) {
            $buffer .= $chunk->getContent();

            $lines = explode("\n", $buffer);
            $buffer = array_pop($lines);

            foreach ($lines as $line) {
                if (empty($line)) {
                    continue;
                }

                $data = json_decode($line, true);
                if ($data === null) {
                    continue;
                }

                if (isset($data['response'])) {
                    $onChunk($data['response']);
                }
            }
        }

        if (!empty($buffer)) {
            $data = json_decode($buffer, true);
            if ($data !== null && isset($data['response'])) {
                $onChunk($data['response']);
            }
        }
    }

    private function createRequest(Message $message, ?Conversation $conversation, bool $stream = false): OllamaRequest
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

        return new OllamaRequest(
            model: $this->model,
            prompt: $message->getContent(),
            system: $systemMessage,
            parameters: $message->getParameters(),
            options: [
                'context' => $context,
            ],
            stream: $stream
        );
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