<?php

namespace VanMeeuwen\SymfonyAI\Infrastructure\Provider\LMStudio;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use VanMeeuwen\SymfonyAI\Domain\AIProvider\AIProviderInterface;
use VanMeeuwen\SymfonyAI\Domain\Exception\InvalidResponseException;
use VanMeeuwen\SymfonyAI\Domain\Exception\NetworkException;
use VanMeeuwen\SymfonyAI\Domain\Exception\TimeoutException;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Conversation;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Message;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Role;
use VanMeeuwen\SymfonyAI\Infrastructure\Provider\LMStudio\DTO\LMStudioRequest;
use VanMeeuwen\SymfonyAI\Infrastructure\Provider\LMStudio\DTO\LMStudioResponse;

class LMStudioProvider implements AIProviderInterface
{
    private const DEFAULT_MODEL = 'default';
    private const REQUEST_TIMEOUT = 30.0;

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
        try {
            $request = $this->createRequest($message, $conversation);

            $response = $this->httpClient->request(
                'POST',
                sprintf('%s/v1/chat/completions', rtrim($this->baseUrl, '/')),
                [
                    'json' => $request->toArray(),
                    'timeout' => self::REQUEST_TIMEOUT,
                ]
            );

            try {

                $data = $response->toArray(false);
            } catch (\Throwable $throwable) {
                throw new InvalidResponseException(
                    'Invalid response from LM Studio API: ' . $throwable->getMessage(),
                    $throwable->getCode(),
                    $throwable
                );
            }

            if (!isset($data['choices'][0]['message']['content'])) {
                throw new InvalidResponseException('Invalid response from LM Studio API');
            }

            $lmStudioResponse = LMStudioResponse::fromArray($data);

            return Message::create(
                content: $lmStudioResponse->getContent(),
                role: Role::ASSISTANT,
                parameters: $message->getParameters()
            );
        } catch (TransportExceptionInterface $e) {
            if (str_contains($e->getMessage(), 'timeout')) {
                throw new TimeoutException(
                    'Request to LM Studio API timed out after ' . self::REQUEST_TIMEOUT . ' seconds',
                    0,
                    $e
                );
            }

            throw new NetworkException(
                'Failed to communicate with LM Studio API: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }

    public function streamMessage(Message $message, callable $onChunk, ?Conversation $conversation = null): void
    {
        // TODO: Implement streamMessage() method.
    }

    private function createRequest(Message $message, ?Conversation $conversation, bool $stream = false): LMStudioRequest
    {
        $messages = [];

        if ($conversation !== null) {
            foreach ($conversation->getMessages() as $historyMessage) {
                $messages[] = [
                    'role' => $historyMessage->getRole()->value,
                    'content' => $historyMessage->getContent(),
                ];
            }
        }

        // Add the current message
        $messages[] = [
            'role' => $message->getRole()->value,
            'content' => $message->getContent(),
        ];

        return new LMStudioRequest(
            messages: $messages,
            model: $this->model,
            temperature: $message->getParameters()->getTemperature(),
            topP: $message->getParameters()->getTopP(),
            maxTokens: $message->getParameters()->getMaxTokens(),
            stream: $stream
        );
    }

    public function createConversation(): Conversation
    {
        $conversation = Conversation::create(
            id: uniqid('lmstudio_', true)
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
        return 'lmstudio';
    }

    public function supportsStreaming(): bool
    {
        return true;
    }
}