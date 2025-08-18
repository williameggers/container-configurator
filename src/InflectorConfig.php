<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @internal
 *
 * @implements IteratorAggregate<int,InflectorDefinition>
 */
final class InflectorConfig implements IteratorAggregate
{
    /**
     * @var array<InflectorDefinition>
     */
    private array $inflectors = [];

    /**
     * @param array<string,array<string,array<mixed>>> $config
     */
    public function __construct(array $config)
    {
        foreach ($config as $interfaceName => $methods) {
            $this->inflectors[] = new InflectorDefinition(
                $interfaceName,
                $methods
            );
        }
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->inflectors);
    }
}
