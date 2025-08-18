<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use LogicException;
use PHPUnit\Framework\TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\NotClassDefinitionException;

final class NotClassDefinitionExceptionTest extends TestCase
{
    public function testItImplementsTheBaseExceptionType(): void
    {
        $this->assertInstanceOf(Exception::class, new NotClassDefinitionException());
    }

    public function testItIsALogicException(): void
    {
        $this->assertInstanceOf(LogicException::class, new NotClassDefinitionException());
    }

    public function testItCanBeCreatedFromThePatterns(): void
    {
        $this->assertSame(
            'Service configuration for "example-service" did not create a class definition.',
            NotClassDefinitionException::fromServiceName('example-service')->getMessage()
        );
    }
}
