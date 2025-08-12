<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

final class NoMatchingFilesException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @internal
     */
    public static function fromPattern(string $pattern): self
    {
        return self::create('No files found matching pattern: "%s".', [$pattern]);
    }
}
