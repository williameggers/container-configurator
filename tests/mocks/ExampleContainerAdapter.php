<?php

declare(strict_types=1);

namespace tests\mocks;

use TomPHP\ContainerConfigurator\ApplicationConfig;
use TomPHP\ContainerConfigurator\ContainerAdapter;
use TomPHP\ContainerConfigurator\InflectorConfig;
use TomPHP\ContainerConfigurator\ServiceConfig;

final class ExampleContainerAdapter implements ContainerAdapter
{
    private static int $instanceCount = 0;

    private ?object $container = null;

    public function __construct()
    {
        self::$instanceCount++;
    }

    public static function reset(): void
    {
        self::$instanceCount = 0;
    }

    public static function getNumberOfInstances(): int
    {
        return self::$instanceCount;
    }

    public function setContainer(object $container): void
    {
        $this->container = $container;
    }

    public function getContainer(): ?object
    {
        return $this->container;
    }

    public function addApplicationConfig(ApplicationConfig $applicationConfig, string $prefix = 'config'): void
    {
    }

    public function addServiceConfig(ServiceConfig $serviceConfig): void
    {
    }

    public function addInflectorConfig(InflectorConfig $inflectorConfig): void
    {
    }
}
