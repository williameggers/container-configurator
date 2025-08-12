<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use LogicException;
use PHPUnit\Framework\TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\NotContainerAdapterException;

final class NotContainerAdapterExceptionTest extends TestCase
{
    public function testItImplementsTheBaseExceptionType(): void
    {
        $this->assertInstanceOf(Exception::class, new NotContainerAdapterException());
    }

    public function testItIsALogicException(): void
    {
        $this->assertInstanceOf(LogicException::class, new NotContainerAdapterException());
    }

    public function testItCanBeCreatedFromThePatterns(): void
    {
        $this->assertSame(
            'Class "Foo" is not a container adapter.',
            NotContainerAdapterException::fromClassName('Foo')->getMessage()
        );
    }
}
