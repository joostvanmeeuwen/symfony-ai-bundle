<?php

namespace VanMeeuwen\SymfonyAI\Infrastructure\Bundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use VanMeeuwen\SymfonyAI\Infrastructure\DependencyInjection\VanMeuwenSymfonyAIExtension;

final class VanMeuwenSymfonyAIBundle extends Bundle
{
    public function getContainerExtension(): VanMeuwenSymfonyAIExtension
    {
        if (null === $this->extension) {
            $this->extension = new VanMeuwenSymfonyAIExtension();
        }

        return $this->extension;
    }

    public function getPath(): string
    {
        return \dirname(__DIR__, 3);
    }
}