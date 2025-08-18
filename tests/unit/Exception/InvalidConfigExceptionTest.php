<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use LogicException;
use PHPUnit\Framework\TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;

final class InvalidConfigExceptionTest extends TestCase
{
    public function testItImplementsTheBaseExceptionType(): void
    {
        $this->assertInstanceOf(Exception::class, new InvalidConfigException());
    }

    public function testItIsALogicException(): void
    {
        $this->assertInstanceOf(LogicException::class, new InvalidConfigException());
    }

    public function testItCanBeCreatedFromTheFileName(): void
    {
        $this->assertSame(
            '"example.cfg" does not return a PHP array.',
            InvalidConfigException::fromPHPFileError('example.cfg')->getMessage()
        );
    }

    public function testItCanBeCreatedWithAJSONFileError(): void
    {
        $this->assertSame(
            'Invalid JSON in "example.json": JSON Error Message',
            InvalidConfigException::fromJSONFileError('example.json', 'JSON Error Message')->getMessage()
        );
    }

    public function testItCanBeCreatedFromYAMLFileError(): void
    {
        $this->assertSame(
            'Invalid YAML in "example.yml": YAML Error Message',
            InvalidConfigException::fromYAMLFileError('example.yml', 'YAML Error Message')->getMessage()
        );
    }

    public function testItCanBeCreatedFromNameWhenClassAndFactoryAreSpecified(): void
    {
        $this->assertSame(
            'Both "class" and "factory" are specified for service "example"; these cannot be used together.',
            InvalidConfigException::fromNameWhenClassAndFactorySpecified('example')->getMessage()
        );
    }

    public function testItCanBeCreatedFromNameWhenClassAndServiceAreSpecified(): void
    {
        $this->assertSame(
            'Both "class" and "service" are specified for service "example"; these cannot be used together.',
            InvalidConfigException::fromNameWhenClassAndServiceSpecified('example')->getMessage()
        );
    }

    public function testItCanBeCreatedFromNameWhenFactoryAndServiceAreSpecified(): void
    {
        $this->assertSame(
            'Both "factory" and "service" are specified for service "example"; these cannot be used together.',
            InvalidConfigException::fromNameWhenFactoryAndServiceSpecified('example')->getMessage()
        );
    }
}
