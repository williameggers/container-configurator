<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\Pimple;

use Assert\Assertion;
use Closure;
use Pimple\Container;
use TomPHP\ContainerConfigurator\ApplicationConfig;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\ContainerConfigurator\ContainerAdapter;
use TomPHP\ContainerConfigurator\Exception\NotFactoryException;
use TomPHP\ContainerConfigurator\InflectorConfig;
use TomPHP\ContainerConfigurator\InflectorDefinition;
use TomPHP\ContainerConfigurator\ServiceConfig;
use TomPHP\ContainerConfigurator\ServiceDefinition;

/**
 * @internal
 */
final class PimpleContainerAdapter implements ContainerAdapter
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var array<callable>
     */
    private array $inflectors = [];

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

        if ($prefix !== '' && $prefix !== '0') {
            $this->container[$prefix] = $applicationConfig->asArray();
            $prefix .= $applicationConfig->getSeparator();
        }

        foreach ($applicationConfig as $key => $value) {
            // @phpstan-ignore-next-line
            if (!is_string($key) && !is_int($key) && !($key instanceof \Stringable)) {
                continue;
            }

            $this->container[$prefix . $key] = $value;
        }
    }

    public function addServiceConfig(ServiceConfig $serviceConfig): void
    {
        foreach ($serviceConfig as $definition) {
            $this->addServiceToContainer($definition);
        }
    }

    public function addInflectorConfig(InflectorConfig $inflectorConfig): void
    {
        foreach ($inflectorConfig as $definition) {
            $this->inflectors[$definition->getInterface()] = $this->createInflector($definition);
        }
    }

    private function addServiceToContainer(ServiceDefinition $serviceDefinition): void
    {
        $factory = $this->createFactory($serviceDefinition);

        if (!$serviceDefinition->isSingleton()) {
            $factory = $this->container->factory($factory);
        }

        $this->container[$serviceDefinition->getName()] = $factory;
    }

    private function createFactory(ServiceDefinition $serviceDefinition): callable
    {
        if ($serviceDefinition->isFactory()) {
            return $this->applyInflectors($this->createFactoryFactory($serviceDefinition));
        }

        if ($serviceDefinition->isAlias()) {
            return $this->createAliasFactory($serviceDefinition);
        }

        return $this->applyInflectors($this->createInstanceFactory($serviceDefinition));
    }

    /**
     * @return Closure
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
     * @return Closure
     */
    private function createAliasFactory(ServiceDefinition $serviceDefinition)
    {
        return fn () => $this->container[$serviceDefinition->getClass()];
    }

    /**
     * @return Closure
     */
    private function createInstanceFactory(ServiceDefinition $serviceDefinition)
    {
        return function () use ($serviceDefinition): object {
            $className = $serviceDefinition->getClass();
            $instance  = new $className(...$this->resolveArguments($serviceDefinition->getArguments()));

            foreach ($serviceDefinition->getMethods() as $name => $args) {
                $instance->$name(...$this->resolveArguments($args));
            }

            return $instance;
        };
    }

    private function createInflector(InflectorDefinition $inflectorDefinition): callable
    {
        return function ($subject) use ($inflectorDefinition): void {
            foreach ($inflectorDefinition->getMethods() as $method => $arguments) {
                $subject->$method(...$this->resolveArguments($arguments));
            }
        };
    }

    private function applyInflectors(Closure $factory): callable
    {
        return function () use ($factory) {
            $instance = $factory();

            foreach ($this->inflectors as $interface => $inflector) {
                if ($instance instanceof $interface) {
                    $inflector($instance);
                }
            }

            return $instance;
        };
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
                if (!is_string($argument)) {
                    return $argument;
                }

                if ($argument === Configurator::container()) {
                    return $this->container;
                }

                return $this->container[$argument] ?? $argument;
            },
            $arguments
        );
    }
}
