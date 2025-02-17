<?php

namespace VanMeeuwen\SymfonyAI\Infrastructure\Bundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class VanMeuwenSymfonyAIBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__, 3);
    }
}