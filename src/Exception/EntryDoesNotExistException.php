<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\Exception;

use DomainException;
use TomPHP\ExceptionConstructorTools;

final class EntryDoesNotExistException extends DomainException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @internal
     */
    public static function fromKey(string $key): self
    {
        return self::create('No entry found for "%s".', [$key]);
    }
}
