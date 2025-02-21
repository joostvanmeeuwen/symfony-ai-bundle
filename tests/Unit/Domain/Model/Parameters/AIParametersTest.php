<?php

namespace VanMeeuwen\SymfonyAI\Tests\Unit\Domain\Model\Parameters;

use PHPUnit\Framework\TestCase;
use VanMeeuwen\SymfonyAI\Domain\Model\Parameters\AIParameters;

final class AIParametersTest extends TestCase
{
    public function testDefaultParameters(): void
    {
        $params = AIParameters::default();

        $this->assertSame(0.7, $params->getTemperature());
        $this->assertSame(0.8, $params->getTopP());
        $this->assertSame(0, $params->getPresencePenalty());
        $this->assertSame(0, $params->getFrequencyPenalty());
        $this->assertNull($params->getMaxTokens());
    }

    public function testCreativeParameters(): void
    {
        $params = AIParameters::creative();

        $this->assertSame(1.5, $params->getTemperature());
        $this->assertSame(0.9, $params->getTopP());
        $this->assertSame(1, $params->getPresencePenalty());
        $this->assertSame(1, $params->getFrequencyPenalty());
    }

    public function testPreciseParameters(): void
    {
        $params = AIParameters::precise();

        $this->assertSame(0.2, $params->getTemperature());
        $this->assertSame(0.1, $params->getTopP());
        $this->assertSame(0, $params->getPresencePenalty());
        $this->assertSame(0, $params->getFrequencyPenalty());
    }

    public function testCustomParameters(): void
    {
        $params = new AIParameters(
            temperature: 0.5,
            maxTokens: 100,
            topP: 0.7,
            presencePenalty: 1,
            frequencyPenalty: 2,
            seed: 123
        );

        $this->assertSame(0.5, $params->getTemperature());
        $this->assertSame(100, $params->getMaxTokens());
        $this->assertSame(0.7, $params->getTopP());
        $this->assertSame(1, $params->getPresencePenalty());
        $this->assertSame(2, $params->getFrequencyPenalty());
        $this->assertSame(123, $params->getSeed());
    }

    public function testInvalidTemperature(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new AIParameters(temperature: 2.5);
    }

    public function testInvalidTopP(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new AIParameters(temperature: 1.0, topP: 1.5);
    }

    public function testWithMethod(): void
    {
        $original = AIParameters::default();
        $modified = $original->with([
            'temperature' => 0.5,
            'maxTokens' => 100
        ]);

        $this->assertSame(0.5, $modified->getTemperature());
        $this->assertSame(100, $modified->getMaxTokens());

        $this->assertSame(0.7, $original->getTemperature());
        $this->assertNull($original->getMaxTokens());
    }
}