<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

final class InvalidConfigException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @internal
     */
    public static function fromPHPFileError(string $filename): static
    {
        return self::create('"%s" does not return a PHP array.', [$filename]);
    }

    /**
     * @internal
     */
    public static function fromJSONFileError(string $filename, string $message): static
    {
        return self::create('Invalid JSON in "%s": %s', [$filename, $message]);
    }

    /**
     * @internal
     */
    public static function fromHJSONFileError(string $filename, string $message): static
    {
        return self::create('Invalid HJSON in "%s": %s', [$filename, $message]);
    }

    /**
     * @internal
     */
    public static function fromYAMLFileError(string $filename, string $message): static
    {
        return self::create('Invalid YAML in "%s": %s', [$filename, $message]);
    }

    /**
     * @internal
     */
    public static function fromNameWhenClassAndFactorySpecified(string $name): static
    {
        return self::create(
            'Both "class" and "factory" are specified for service "%s"; these cannot be used together.',
            [$name]
        );
    }

    /**
     * @internal
     */
    public static function fromNameWhenClassAndServiceSpecified(string $name): static
    {
        return self::create(
            'Both "class" and "service" are specified for service "%s"; these cannot be used together.',
            [$name]
        );
    }

    /**
     * @internal
     */
    public static function fromNameWhenFactoryAndServiceSpecified(string $name): self
    {
        return self::create(
            'Both "factory" and "service" are specified for service "%s"; these cannot be used together.',
            [$name]
        );
    }
}
