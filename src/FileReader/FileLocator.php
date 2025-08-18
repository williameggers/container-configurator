<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\FileReader;

use Assert\Assertion;
use InvalidArgumentException;

/**
 * @internal
 */
final class FileLocator
{
    /**
     * @throws InvalidArgumentException
     *
     * @return string[]
     */
    public function locate(string $pattern): array
    {
        Assertion::string($pattern);

        return glob($pattern, GLOB_BRACE) ?: [];
    }
}
