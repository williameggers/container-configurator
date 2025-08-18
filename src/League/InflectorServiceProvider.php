<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\League;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use TomPHP\ContainerConfigurator\InflectorConfig;
use TomPHP\ContainerConfigurator\InflectorDefinition;

/**
 * @internal
 */
final class InflectorServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    public function __construct(
        /**
         * @var InflectorConfig<int|string,InflectorDefinition>
         */
        private readonly \TomPHP\ContainerConfigurator\InflectorConfig $inflectorConfig
    ) {
    }

    public function provides(string $id): bool
    {
        return false;
    }

    public function register(): void
    {
    }

    public function boot(): void
    {
        foreach ($this->inflectorConfig as $definition) {
            $this->configureInterface($definition);
        }
    }

    private function configureInterface(InflectorDefinition $inflectorDefinition): void
    {
        foreach ($inflectorDefinition->getMethods() as $method => $args) {
            $this->addInflectorMethod(
                $inflectorDefinition->getInterface(),
                $method,
                $args
            );
        }
    }

    /**
     * @param array<mixed> $args
     */
    private function addInflectorMethod(string $interface, string $method, array $args): void
    {
        $this->getContainer()
            ->inflector($interface)
            ->invokeMethod($method, $args);
    }
}
