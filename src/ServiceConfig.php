<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator;

use ArrayIterator;
use Assert\Assertion;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

/**
 * @internal
 *
 * @implements IteratorAggregate<int,ServiceDefinition>
 */
final class ServiceConfig implements IteratorAggregate
{
    /**
     * @var ServiceDefinition[]
     */
    private array $config = [];

    /**
     * @param array<string,array{
     *  singleton?:bool,
     *  factory?:mixed,
     *  service?:mixed,
     *  arguments?:array<mixed>,
     *  methods?:array<string,array<mixed>>
     * }> $config
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $config, bool $singletonDefault = false)
    {
        Assertion::boolean($singletonDefault);

        foreach ($config as $key => $serviceConfig) {
            $this->config[] = new ServiceDefinition($key, $serviceConfig, $singletonDefault);
        }
    }

    /**
     * @return array<int|string,string>
     */
    public function getKeys(): array
    {
        return array_map(
            fn (ServiceDefinition $serviceDefinition): string => $serviceDefinition->getName(),
            $this->config
        );
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->config);
    }
}
