<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator;

/**
 * @internal
 */
final class InflectorDefinition
{
    /**
     * @param array<string,array<mixed>> $methods
     */
    public function __construct(private readonly string $interface, private readonly array $methods)
    {
    }

    public function getInterface(): string
    {
        return $this->interface;
    }

    /**
     * @return array<string,array<mixed>>
     */
    public function getMethods(): array
    {
        return $this->methods;
    }
}
