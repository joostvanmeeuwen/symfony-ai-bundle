<?php

namespace VanMeeuwen\SymfonyAI\Infrastructure\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class DefaultProviderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('van_meeuwen_symfony_ai.default_provider')) {
            return;
        }

        $defaultProvider = $container->getParameter('van_meeuwen_symfony_ai.default_provider');
        $providerId = 'van_meeuwen_symfony_ai.provider.' . $defaultProvider;

        if (!$container->hasDefinition($providerId)) {
            $availableProviders = array_filter($container->getServiceIds(), function ($id) {
                return str_starts_with($id, 'van_meeuwen_symfony_ai.provider.');
            });
            $shortProviderNames = array_map(function ($id) {
                return str_replace('van_meeuwen_symfony_ai.provider.', '', $id);
            }, $availableProviders);

            throw new \InvalidArgumentException(sprintf('Default provider "%s" is not registered. Available providers: %s', $defaultProvider, implode(', ',
                $shortProviderNames
            )));
        }

        $container->setAlias('VanMeeuwen\SymfonyAI\Domain\AIProvider\AIProviderInterface', $providerId)
            ->setPublic(true);
    }
}