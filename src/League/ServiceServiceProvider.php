<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\League;

use League\Container\Definition\Definition;
use League\Container\ServiceProvider\AbstractServiceProvider;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\ContainerConfigurator\Exception\NotClassDefinitionException;
use TomPHP\ContainerConfigurator\Exception\NotFactoryException;
use TomPHP\ContainerConfigurator\ServiceDefinition;

/**
 * @internal
 */
final class ServiceServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array<int|string,string>
     */
    private readonly array $provides;

    public function __construct(private readonly \TomPHP\ContainerConfigurator\ServiceConfig $serviceConfig)
    {
        $this->provides = $this->serviceConfig->getKeys();
    }

    public function provides(string $id): bool
    {
        return in_array($id, $this->provides);
    }

    public function register(): void
    {
        foreach ($this->serviceConfig as $config) {
            $this->registerService($config);
        }
    }

    /**
     * @throws NotClassDefinitionException
     */
    private function registerService(ServiceDefinition $serviceDefinition): void
    {
        if ($serviceDefinition->isFactory()) {
            $service = $this->getContainer()->add(
                $serviceDefinition->getName(),
                $this->createFactoryFactory($serviceDefinition)
            );

            $service->setShared($serviceDefinition->isSingleton());

            return;
        }

        if ($serviceDefinition->isAlias()) {
            $this->getContainer()->add(
                $serviceDefinition->getName(),
                $this->createAliasFactory($serviceDefinition)
            );

            return;
        }

        $service = $this->getContainer()->add(
            $serviceDefinition->getName(),
            $serviceDefinition->getClass()
        );

        $service->setShared($serviceDefinition->isSingleton());

        if (!$service instanceof Definition) {
            throw NotClassDefinitionException::fromServiceName($serviceDefinition->getName());
        }

        $service->addArguments($this->injectContainer($serviceDefinition->getArguments()));
        $this->addMethodCalls($service, $serviceDefinition);
    }

    private function addMethodCalls(Definition $definition, ServiceDefinition $serviceDefinition): void
    {
        foreach ($serviceDefinition->getMethods() as $method => $args) {
            $definition->addMethodCall($method, $this->injectContainer($args));
        }
    }

    /**
     * @return \Closure
     */
    private function createAliasFactory(ServiceDefinition $serviceDefinition)
    {
        return fn () => $this->getContainer()->get($serviceDefinition->getClass());
    }

    /**
     * @return \Closure
     */
    private function createFactoryFactory(ServiceDefinition $serviceDefinition)
    {
        return function () use ($serviceDefinition) {
            $className = $serviceDefinition->getClass();
            $factory   = new $className();
            if (!is_callable($factory)) {
                throw NotFactoryException::fromClassName($className);
            }

            return $factory(...$this->resolveArguments($serviceDefinition->getArguments()));
        };
    }

    /**
     * @param array<mixed> $arguments
     *
     * @return array<mixed>
     */
    private function injectContainer(array $arguments): array
    {
        return array_map(
            fn ($argument): mixed => ($argument === Configurator::container())
                ? $this->container
                : $argument,
            $arguments
        );
    }

    /**
     * @param array<mixed> $arguments
     *
     * @return array<mixed>
     */
    private function resolveArguments(array $arguments): array
    {
        return array_map(
            function ($argument) {
                if ($argument === Configurator::container()) {
                    return $this->container;
                }

                if ((is_string($argument) || is_int($argument) || $argument instanceof \Stringable)
                    && $this->container?->has((string) $argument)
                ) {
                    return $this->container->get((string) $argument);
                }

                return $argument;
            },
            $arguments
        );
    }
}
