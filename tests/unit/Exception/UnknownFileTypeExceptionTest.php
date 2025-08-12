<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use DomainException;
use PHPUnit\Framework\TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\UnknownFileTypeException;

final class UnknownFileTypeExceptionTest extends TestCase
{
    public function testItImplementsTheBaseExceptionType(): void
    {
        $this->assertInstanceOf(Exception::class, new UnknownFileTypeException());
    }

    public function testItIsADomainException(): void
    {
        $this->assertInstanceOf(DomainException::class, new UnknownFileTypeException());
    }

    public function testItCanBeCreatedFromFileExtension(): void
    {
        $unknownFileTypeException = UnknownFileTypeException::fromFileExtension('.yml', ['.json', '.php']);

        $this->assertSame(
            'No reader configured for ".yml" files; readers are available for [".json", ".php"].',
            $unknownFileTypeException->getMessage()
        );
    }
}
