<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator;

use PHPUnit\Framework\TestCase;
use TomPHP\ContainerConfigurator\ServiceConfig;
use TomPHP\ContainerConfigurator\ServiceDefinition;

final class ServiceConfigTest extends TestCase
{
    public function testFormatsASingleService(): void
    {
        $serviceConfig = [
            'class'     => self::class,
            'singleton' => false,
            'arguments' => ['argument1', 'argument2'],
            'method'    => ['setSomething' => ['value']],
        ];

        $config = new ServiceConfig(['service_name' => $serviceConfig]);

        $this->assertEquals(
            [new ServiceDefinition('service_name', $serviceConfig)],
            iterator_to_array($config)
        );
    }

    public function testItProvidesAListOfKeys(): void
    {
        $serviceConfig = [
            'class'     => self::class,
            'singleton' => false,
            'arguments' => ['argument1', 'argument2'],
            'method'    => ['setSomething' => ['value']],
        ];

        $config = new ServiceConfig([
            'service1' => $serviceConfig,
            'service2' => $serviceConfig,
        ]);

        $this->assertSame(['service1', 'service2'], $config->getKeys());
    }

    public function testDefaultValueForSingletonCanBeSetToTrue(): void
    {
        $serviceConfig = ['class' => self::class];

        $config = new ServiceConfig(['service_name' => $serviceConfig], true);

        $this->assertTrue(iterator_to_array($config)[0]->isSingleton());
    }
}
