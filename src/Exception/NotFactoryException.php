<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

final class NotFactoryException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @internal
     */
    public static function fromClassName(string $name): self
    {
        return self::create(
            'Class "%s" is not a factory.',
            [$name]
        );
    }
}
