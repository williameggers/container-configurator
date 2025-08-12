<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

final class UnknownContainerException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @internal
     *
     * @param string[] $knownContainers
     */
    public static function fromContainerName(string $name, array $knownContainers): self
    {
        return self::create(
            'Container %s is unknown; known containers are %s.',
            [$name, self::listToString($knownContainers)]
        );
    }
}
