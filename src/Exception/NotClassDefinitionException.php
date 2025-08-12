<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

final class NotClassDefinitionException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @internal
     */
    public static function fromServiceName(string $name): self
    {
        return self::create(
            'Service configuration for "%s" did not create a class definition.',
            [$name]
        );
    }
}
