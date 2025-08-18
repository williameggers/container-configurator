<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use DomainException;
use PHPUnit\Framework\TestCase;
use TomPHP\ContainerConfigurator\Exception\EntryDoesNotExistException;
use TomPHP\ContainerConfigurator\Exception\Exception;

final class EntryDoesNotExistExceptionTest extends TestCase
{
    public function testItImplementsTheBaseExceptionType(): void
    {
        $this->assertInstanceOf(Exception::class, new EntryDoesNotExistException());
    }

    public function testItIsADomainException(): void
    {
        $this->assertInstanceOf(DomainException::class, new EntryDoesNotExistException());
    }

    public function testItCanBeCreatedFromTheKey(): void
    {
        $this->assertSame(
            'No entry found for "example-key".',
            EntryDoesNotExistException::fromKey('example-key')->getMessage()
        );
    }
}
