<?php

declare(strict_types=1);

namespace tests\mocks;

final class ExampleFactory
{
    public function __invoke($class, ...$arguments)
    {
        return new $class(...$arguments);
    }
}
