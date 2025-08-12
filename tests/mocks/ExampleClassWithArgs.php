<?php

declare(strict_types=1);

namespace tests\mocks;

final class ExampleClassWithArgs
{
    private array $constructorArgs = [];

    public function __construct(...$constructorArgs)
    {
        $this->constructorArgs = $constructorArgs;
    }

    public function getConstructorArgs(): array
    {
        return $this->constructorArgs;
    }
}
