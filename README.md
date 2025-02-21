# Symfony AI Bundle

A Symfony bundle for integrating various AI providers (Ollama, Claude, etc.) into your Symfony application. Features include:
- Multiple AI provider support
- Streaming responses
- Conversation management
- Customizable AI parameters
- CQRS pattern implementation

## Installation

1. Add the repository to your `composer.json`:
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/joostvanmeeuwen/symfony-ai-bundle.git"
        }
    ]
}
```

2. Require the bundle:
```bash
composer require vanmeeuwen/symfony-ai-bundle
```

3. Add configuration in `config/packages/van_meeuwen_symfony_ai.yaml`:
```yaml
van_meeuwen_symfony_ai:
    ollama:
        base_url: '%env(OLLAMA_BASE_URL)%'
        model: 'phi4'  # or your preferred model
```

4. Add to your `.env`:
```
OLLAMA_BASE_URL=http://localhost:11434
```

## Usage

### Basic Message Sending

```php
use VanMeeuwen\SymfonyAI\Application\Command\SendMessage\SendMessageCommand;
use VanMeeuwen\SymfonyAI\Domain\Model\Conversation\Role;
use VanMeeuwen\SymfonyAI\Domain\Model\Parameters\AIParameters;

class YourController
{
    public function __construct(
        private SendMessageHandler $sendMessageHandler
    ) {}

    public function someAction(): Response
    {
        // Basic usage
        $command = new SendMessageCommand(
            content: "What is quantum computing?",
            role: Role::USER->value
        );

        // Using AI parameters
        $command = new SendMessageCommand(
            content: "Write a creative story about robots",
            role: Role::USER->value,
            parameters: AIParameters::creative()
        );

        // Or with custom parameters
        $command = new SendMessageCommand(
            content: "Explain databases",
            role: Role::USER->value,
            parameters: new AIParameters(
                temperature: 0.7,
                maxTokens: 500,
                topP: 0.8
            )
        );

        $response = $this->sendMessageHandler->__invoke($command);
        
        return new JsonResponse($response);
    }
}
```

### Available Parameter Presets

- `AIParameters::default()` - Balanced settings for general use
- `AIParameters::creative()` - High creativity for storytelling and brainstorming
- `AIParameters::precise()` - Low creativity for factual responses
- `AIParameters::conversational()` - Natural dialogue settings
- `AIParameters::concise()` - Short, to-the-point responses (150 tokens)
- `AIParameters::technical()` - Optimized for code and technical content
- `AIParameters::professional()` - Formal communication style
- `AIParameters::instructional()` - Clear step-by-step instructions

For detailed information about AI parameters and their use cases, see [AI Parameters Documentation](docs/ai-parameters.md).


### Streaming Responses

```php
use VanMeeuwen\SymfonyAI\Application\Command\StreamMessage\StreamMessageCommand;
use Symfony\Component\HttpFoundation\StreamedResponse;

class YourController
{
    public function __construct(
        private StreamMessageHandler $streamMessageHandler
    ) {}

    public function streamAction(): StreamedResponse
    {
        return new StreamedResponse(function() {
            $command = new StreamMessageCommand(
                content: "Explain the theory of relativity",
                role: Role::USER->value,
                onChunk: function(string $chunk) {
                    echo $chunk;
                    ob_flush();
                    flush();
                }
            );

            $this->streamMessageHandler->__invoke($command);
        });
    }
}
```

### Conversations

```php
use VanMeeuwen\SymfonyAI\Application\Command\CreateConversation\CreateConversationCommand;

class YourController
{
    public function __construct(
        private CreateConversationHandler $createConversationHandler,
        private SendMessageHandler $sendMessageHandler
    ) {}

    public function conversationAction(): Response
    {
        // Start a conversation
        $conversation = $this->createConversationHandler->__invoke(
            new CreateConversationCommand("Chat about physics")
        );

        // Send a message in the conversation context
        $message = new SendMessageCommand(
            content: "What is quantum entanglement?",
            role: Role::USER->value,
            conversationId: $conversation->getId()
        );

        $response = $this->sendMessageHandler->__invoke($message);
        
        return new JsonResponse($response);
    }
}
```

## Currently Supported Providers

- Ollama (with streaming support)
- More coming soon (Claude, ChatGPT, etc.)
