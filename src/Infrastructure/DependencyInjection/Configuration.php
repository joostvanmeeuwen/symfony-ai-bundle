<?php

namespace VanMeeuwen\SymfonyAI\Infrastructure\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('van_meeuwen_symfony_ai');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->arrayNode('ollama')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('base_url')
            ->defaultValue('http://localhost:11434')
            ->end()
            ->scalarNode('model')
            ->defaultValue('llama2')
            ->end()
            ->end()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}