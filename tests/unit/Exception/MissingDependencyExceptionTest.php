<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use LogicException;
use PHPUnit\Framework\TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\MissingDependencyException;

final class MissingDependencyExceptionTest extends TestCase
{
    public function testItImplementsTheBaseExceptionType(): void
    {
        $this->assertInstanceOf(Exception::class, new MissingDependencyException());
    }

    public function testItIsALogicException(): void
    {
        $this->assertInstanceOf(LogicException::class, new MissingDependencyException());
    }

    public function testItCanBeCreatedFromPackageName(): void
    {
        $this->assertSame(
            'The package "foo/bar" is missing. Please run "composer require foo/bar" to install it.',
            MissingDependencyException::fromPackageName('foo/bar')->getMessage()
        );
    }
}
