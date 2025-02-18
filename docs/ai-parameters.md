# AI Parameters Guide

This guide explains how to use the AI parameters in the Symfony AI Bundle to optimize AI responses for different use cases.

## Understanding Parameters

### Core Parameters

- **Temperature** (0.0 - 2.0)
    - Controls randomness in the response
    - Lower values (0.0-0.5): More focused, deterministic, and consistent
    - Medium values (0.6-1.2): Balanced creativity and coherence
    - Higher values (1.3-2.0): More random, creative, and diverse

- **Top P** (0.0 - 1.0)
    - Controls diversity of word choices
    - Lower values: More focused on likely words
    - Higher values: More diverse vocabulary
    - Works together with temperature to control randomness

- **Max Tokens**
    - Limits response length
    - Useful for controlling costs and response size
    - Set based on your needs (e.g., 50 for short answers, 300+ for detailed explanations)

- **Presence Penalty** (-2 to 2)
    - Prevents topic repetition
    - Higher values encourage exploring new topics
    - Lower values allow staying on topic

- **Frequency Penalty** (-2 to 2)
    - Prevents word repetition
    - Higher values encourage using different words
    - Lower values allow word reuse when appropriate

- **Seed**
    - For reproducible responses
    - Same seed + same prompt = same response
    - Useful for testing and consistency

## Preset Use Cases

### `default()`
```php
$message = Message::create(
    content: "Tell me about dolphins",
    role: Role::USER,
    parameters: AIParameters::default()
);
```
- Balanced settings for general use
- Good starting point for most applications
- Safe choice when unsure

### `creative()`
```php
$message = Message::create(
    content: "Write a story about a time-traveling chef",
    role: Role::USER,
    parameters: AIParameters::creative()
);
```
- High temperature (1.5) for maximum creativity
- High top_p (0.9) for diverse vocabulary
- Great for:
    - Creative writing
    - Storytelling
    - Marketing copy
    - Idea generation

### `precise()`
```php
$message = Message::create(
    content: "What is the formula for calculating compound interest?",
    role: Role::USER,
    parameters: AIParameters::precise()
);
```
- Low temperature (0.2) for consistent outputs
- Low top_p (0.1) for focused vocabulary
- Perfect for:
    - Mathematical answers
    - Factual information
    - Technical documentation
    - Code generation

### `conversational()`
```php
$message = Message::create(
    content: "How was your day?",
    role: Role::USER,
    parameters: AIParameters::conversational()
);
```
- Moderate temperature (0.8) for natural responses
- Limited max tokens for chat-like responses
- Ideal for:
    - Chatbots
    - Virtual assistants
    - Customer service
    - Casual interaction

### `technical()`
```php
$message = Message::create(
    content: "Write a PHP function to validate email addresses",
    role: Role::USER,
    parameters: AIParameters::technical()
);
```
- Low temperature (0.3) for precision
- Frequency penalty to avoid redundant explanations
- Best for:
    - Code generation
    - Technical explanations
    - API documentation
    - System design discussions

### `brainstorm()`
```php
$message = Message::create(
    content: "Generate 10 startup ideas for sustainable energy",
    role: Role::USER,
    parameters: AIParameters::brainstorm()
);
```
- Very high temperature (1.8) for maximum creativity
- High presence penalty to force diverse ideas
- Perfect for:
    - Ideation sessions
    - Creative problem solving
    - Exploring possibilities
    - Innovation workshops

### `professional()`
```php
$message = Message::create(
    content: "Draft a business proposal summary",
    role: Role::USER,
    parameters: AIParameters::professional()
);
```
- Moderate temperature (0.6) for balanced formality
- Controlled creativity for business context
- Suitable for:
    - Business communications
    - Formal documents
    - Professional emails
    - Corporate content

### `explanatory()`
```php
$message = Message::create(
    content: "Explain how blockchain works",
    role: Role::USER,
    parameters: AIParameters::explanatory()
);
```
- Moderate temperature (0.5) for clear explanations
- Higher max tokens for comprehensive answers
- Great for:
    - Educational content
    - Complex topics
    - Tutorial generation
    - Concept breakdowns

### `instructional()`
```php
$message = Message::create(
    content: "How to make sourdough bread",
    role: Role::USER,
    parameters: AIParameters::instructional()
);
```
- Low temperature (0.4) for clear steps
- Balanced penalties for structured content
- Perfect for:
    - Step-by-step guides
    - How-to content
    - Recipes
    - Assembly instructions

## Custom Parameters

You can create custom parameters for specific needs:

```php
$parameters = new AIParameters(
    temperature: 0.9,
    maxTokens: 200,
    topP: 0.8,
    presencePenalty: 1,
    frequencyPenalty: 1
);

$message = Message::create(
    content: "Your prompt here",
    role: Role::USER,
    parameters: $parameters
);
```

Or modify existing presets:

```php
$parameters = AIParameters::creative()->with([
    'maxTokens' => 500,
    'temperature' => 1.2
]);
```

## Tips for Choosing Parameters

1. **Start with Presets**
    - Use presets as a starting point
    - Adjust based on results
    - Document what works for your use case

2. **Consider Your Goals**
    - Need accuracy? Use `precise()`
    - Need creativity? Use `creative()` or `brainstorm()`
    - Need natural dialogue? Use `conversational()`

3. **Monitor and Adjust**
    - Start with a preset
    - Test the results
    - Adjust parameters if needed
    - Document successful configurations

4. **Balance Cost and Quality**
    - Higher max_tokens = higher cost
    - More creative settings might need more retries
    - Find the sweet spot for your use case

## Common Patterns

### Customer Service
```php
$parameters = AIParameters::conversational()->with([
    'maxTokens' => 100,  // Keep responses concise
    'temperature' => 0.7 // Slightly lower for more reliability
]);
```

### Code Generation
```php
$parameters = AIParameters::technical()->with([
    'maxTokens' => 500,  // Space for code and explanation
    'temperature' => 0.2 // Very focused for accurate code
]);
```

### Content Creation
```php
$parameters = AIParameters::creative()->with([
    'maxTokens' => 1000, // Room for longer content
    'presencePenalty' => 1.5 // Encourage diverse topics
]);
```