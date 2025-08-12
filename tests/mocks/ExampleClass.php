<?php

declare(strict_types=1);

namespace tests\mocks;

final class ExampleClass implements ExampleInterface
{
    private $value;

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
