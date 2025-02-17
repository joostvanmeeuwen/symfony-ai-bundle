<?php

namespace VanMeeuwen\SymfonyAI\Infrastructure\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use VanMeeuwen\SymfonyAI\Domain\Port\AIProviderInterface;

final class VanMeuwenSymfonyAIExtension extends Extension
{
    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('framework', [
            'validation' => [
                'enabled' => true
            ]
        ]);
    }

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

        $container->registerForAutoconfiguration(AIProviderInterface::class)
            ->addTag('van_meeuwen_symfony_ai.provider');
    }

    public function getConfiguration(array $config, ContainerBuilder $container): Configuration
    {
        return new Configuration();
    }
}