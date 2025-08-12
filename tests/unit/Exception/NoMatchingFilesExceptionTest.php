<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use LogicException;
use PHPUnit\Framework\TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\NoMatchingFilesException;

final class NoMatchingFilesExceptionTest extends TestCase
{
    public function testItImplementsTheBaseExceptionType(): void
    {
        $this->assertInstanceOf(Exception::class, new NoMatchingFilesException());
    }

    public function testItIsALogicException(): void
    {
        $this->assertInstanceOf(LogicException::class, new NoMatchingFilesException());
    }

    public function testItCanBeCreatedFromThePattern(): void
    {
        $this->assertSame(
            'No files found matching pattern: "*.json".',
            NoMatchingFilesException::fromPattern('*.json')->getMessage()
        );
    }
}
