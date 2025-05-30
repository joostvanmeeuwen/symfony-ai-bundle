<?php

namespace VanMeeuwen\SymfonyAI\Infrastructure\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use VanMeeuwen\SymfonyAI\Domain\AIProvider\AIProviderInterface;

final class VanMeuwenSymfonyAIExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../../config')
        );
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('van_meeuwen_symfony_ai.ollama.base_url', $config['ollama']['base_url']);
        $container->setParameter('van_meeuwen_symfony_ai.ollama.model', $config['ollama']['model']);

        $container->setParameter('van_meeuwen_symfony_ai.lmstudio.base_url', $config['lmstudio']['base_url']);
        $container->setParameter('van_meeuwen_symfony_ai.lmstudio.model', $config['lmstudio']['model']);

        $container->setParameter('van_meeuwen_symfony_ai.default_provider', $config['default_provider']);

        $container->registerForAutoconfiguration(AIProviderInterface::class)
            ->addTag('van_meeuwen_symfony_ai.provider');
    }

    public function getConfiguration(array $config, ContainerBuilder $container): Configuration
    {
        return new Configuration();
    }

    public function getAlias(): string
    {
        return 'van_meeuwen_symfony_ai';
    }
}