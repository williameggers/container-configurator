<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\FileReader;

use Assert\Assertion;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;

/**
 * @internal
 */
final class PHPFileReader implements FileReader
{
    private string $filename;

    public function read(string $filename): mixed
    {
        Assertion::file($filename);

        $this->filename = $filename;

        $config = include $this->filename;

        $this->assertConfigIsValid($config);

        return $config;
    }

    private function assertConfigIsValid(mixed $config): void
    {
        if (!is_array($config)) {
            throw InvalidConfigException::fromPHPFileError($this->filename);
        }
    }
}
