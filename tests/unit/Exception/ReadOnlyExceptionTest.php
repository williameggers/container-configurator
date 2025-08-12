<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use LogicException;
use PHPUnit\Framework\TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\ReadOnlyException;

final class ReadOnlyExceptionTest extends TestCase
{
    public function testItImplementsTheBaseExceptionType(): void
    {
        $this->assertInstanceOf(Exception::class, new ReadOnlyException());
    }

    public function testItIsALogicException(): void
    {
        $this->assertInstanceOf(LogicException::class, new ReadOnlyException());
    }

    public function testItCanBeCreatedFromThePatterns(): void
    {
        $this->assertSame(
            '"ClassName" is read only.',
            ReadOnlyException::fromClassName('ClassName')->getMessage()
        );
    }
}
