<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\Exception;

use LogicException;
use TomPHP\ExceptionConstructorTools;

final class MissingDependencyException extends LogicException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @internal
     */
    public static function fromPackageName(string $packageName): self
    {
        return self::create('The package "%s" is missing. Please run "composer require %s" to install it.', [
            $packageName,
            $packageName,
        ]);
    }
}
