<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\Exception;

use DomainException;
use TomPHP\ExceptionConstructorTools;

final class UnknownFileTypeException extends DomainException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @internal
     *
     * @param string[] $availableExtensions
     */
    public static function fromFileExtension(string $extension, array $availableExtensions): self
    {
        return self::create(
            'No reader configured for "%s" files; readers are available for %s.',
            [$extension, self::listToString($availableExtensions)]
        );
    }
}
