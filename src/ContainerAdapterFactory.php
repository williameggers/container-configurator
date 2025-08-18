<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator;

use TomPHP\ContainerConfigurator\Exception\NotContainerAdapterException;
use TomPHP\ContainerConfigurator\Exception\UnknownContainerException;

/**
 * @internal
 */
final class ContainerAdapterFactory
{
    /**
     * @param array<string,string> $config
     */
    public function __construct(private readonly array $config)
    {
    }

    /**
     * @param object $container
     *
     * @throws UnknownContainerException
     * @throws NotContainerAdapterException
     *
     * @return ContainerAdapter
     */
    public function create($container)
    {
        $class = '';

        foreach ($this->config as $containerClass => $configuratorClass) {
            if ($container instanceof $containerClass) {
                $class = $configuratorClass;
                break;
            }
        }

        if (!$class) {
            throw UnknownContainerException::fromContainerName(
                $container::class,
                array_keys($this->config)
            );
        }

        $instance = new $class();

        if (!$instance instanceof ContainerAdapter) {
            throw NotContainerAdapterException::fromClassName($class);
        }

        $instance->setContainer($container);

        return $instance;
    }
}
