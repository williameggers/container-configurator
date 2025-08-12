<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use LogicException;
use PHPUnit\Framework\TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\UnknownContainerException;

final class UnknownContainerExceptionTest extends TestCase
{
    public function testItImplementsTheBaseExceptionType(): void
    {
        $this->assertInstanceOf(Exception::class, new UnknownContainerException());
    }

    public function testItIsADomainException(): void
    {
        $this->assertInstanceOf(LogicException::class, new UnknownContainerException());
    }

    public function testItCanBeCreatedFromFileExtension(): void
    {
        $unknownContainerException =
            UnknownContainerException::fromContainerName('example-container', ['container-a', 'container-b']);

        $this->assertSame(
            'Container example-container is unknown; known containers are ["container-a", "container-b"].',
            $unknownContainerException->getMessage()
        );
    }
}
