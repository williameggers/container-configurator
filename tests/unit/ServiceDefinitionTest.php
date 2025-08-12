<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator;

use PHPUnit\Framework\TestCase;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;
use TomPHP\ContainerConfigurator\ServiceDefinition;

final class ServiceDefinitionTest extends TestCase
{
    public function testItCreatesFromConfig(): void
    {
        $config = [
            'class'     => self::class,
            'singleton' => false,
            'arguments' => ['argument1', 'argument2'],
            'methods'   => ['setSomething' => ['value']],
        ];

        $serviceDefinition = new ServiceDefinition('service_name', $config);

        $this->assertSame('service_name', $serviceDefinition->getName());
        $this->assertSame(self::class, $serviceDefinition->getClass());
        $this->assertFalse($serviceDefinition->isFactory());
        $this->assertFalse($serviceDefinition->isSingleton());
        $this->assertSame(['argument1', 'argument2'], $serviceDefinition->getArguments());
        $this->assertSame(['setSomething' => ['value']], $serviceDefinition->getMethods());
    }

    public function testClassDefaultsToKey(): void
    {
        $serviceDefinition = new ServiceDefinition('service_name', []);

        $this->assertSame('service_name', $serviceDefinition->getClass());
    }

    public function testSingletonDefaultsToFalse(): void
    {
        $serviceDefinition = new ServiceDefinition('service_name', []);

        $this->assertFalse($serviceDefinition->isSingleton());
    }

    public function testSingletonDefaultCanBeSetToToTrue(): void
    {
        $serviceDefinition = new ServiceDefinition('service_name', [], true);

        $this->assertTrue($serviceDefinition->isSingleton());
    }

    public function testArgumentsDefaultToAnEmptyList(): void
    {
        $serviceDefinition = new ServiceDefinition('service_name', []);

        $this->assertSame([], $serviceDefinition->getArguments());
    }

    public function testMethodsDefaultToAnEmptyList(): void
    {
        $serviceDefinition = new ServiceDefinition('service_name', []);

        $this->assertSame([], $serviceDefinition->getMethods());
    }

    public function testServiceFactoryDefinition(): void
    {
        $serviceDefinition = new ServiceDefinition('service_name', ['factory' => self::class]);

        $this->assertTrue($serviceDefinition->isFactory());
        $this->assertFalse($serviceDefinition->isAlias());
        $this->assertSame(self::class, $serviceDefinition->getClass());
    }

    public function testServiceAliasDefinition(): void
    {
        $serviceDefinition = new ServiceDefinition('service_name', ['service' => self::class]);

        $this->assertTrue($serviceDefinition->isAlias());
        $this->assertFalse($serviceDefinition->isFactory());
        $this->assertSame(self::class, $serviceDefinition->getClass());
    }

    public function testItThrowIfClassAndFactoryAreDefined(): void
    {
        $this->expectException(InvalidConfigException::class);

        new ServiceDefinition('service_name', ['class' => self::class, 'factory' => self::class]);
    }

    public function testItThrowIfClassAndServiceAreDefined(): void
    {
        $this->expectException(InvalidConfigException::class);

        new ServiceDefinition('service_name', ['class' => self::class, 'service' => self::class]);
    }

    public function testItThrowIfFactoryAndServiceAreDefined(): void
    {
        $this->expectException(InvalidConfigException::class);

        new ServiceDefinition('service_name', ['factory' => self::class, 'service' => self::class]);
    }
}
