# Symfony AI Bundle

## Installation

```bash
composer require vanmeeuwen/symfony-ai-bundle
```

## Configuration
1. Add the bundle to `config/bundles.php`:
```php
return [
    // ...
    VanMeeuwen\SymfonyAI\Infrastructure\Bundle\VanMeuwenSymfonyAIBundle::class => ['all' => true],
];
```

2. Make a `config/packages/vanmeeuwen_symfony_ai.yaml` config file:
```yaml
vanmeeuwen_symfony_ai:
    ollama:
        base_url: '%env(OLLAMA_BASE_URL)%'
        model: 'llama2'
```
3. Add the environment variables to the `.env` file: 

```
   OLLAMA_BASE_URL=http://localhost:11434
```
