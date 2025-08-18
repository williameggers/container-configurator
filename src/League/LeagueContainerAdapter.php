<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\League;

use Assert\Assertion;
use League\Container\Container;
use TomPHP\ContainerConfigurator\ApplicationConfig;
use TomPHP\ContainerConfigurator\ContainerAdapter;
use TomPHP\ContainerConfigurator\InflectorConfig;
use TomPHP\ContainerConfigurator\ServiceConfig;

/**
 * @internal
 */
final class LeagueContainerAdapter implements ContainerAdapter
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function setContainer(object $container): void
    {
        $this->container = $container;
    }

    public function addApplicationConfig(ApplicationConfig $applicationConfig, string $prefix = 'config'): void
    {
        Assertion::string($prefix);

        $this->container->addServiceProvider(new ApplicationConfigServiceProvider($applicationConfig, $prefix));
    }

    public function addServiceConfig(ServiceConfig $serviceConfig): void
    {
        $this->container->addServiceProvider(new ServiceServiceProvider($serviceConfig));
    }

    public function addInflectorConfig(InflectorConfig $inflectorConfig): void
    {
        $this->container->addServiceProvider(new InflectorServiceProvider($inflectorConfig));
    }
}
