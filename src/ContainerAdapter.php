<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator;

use InvalidArgumentException;

interface ContainerAdapter
{
    public function setContainer(object $container): void;

    /**
     * @throws InvalidArgumentException
     */
    public function addApplicationConfig(ApplicationConfig $applicationConfig, string $prefix = 'config'): void;

    public function addServiceConfig(ServiceConfig $serviceConfig): void;

    public function addInflectorConfig(InflectorConfig $inflectorConfig): void;
}
