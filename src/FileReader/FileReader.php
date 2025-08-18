<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\FileReader;

use InvalidArgumentException;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;

interface FileReader
{
    /**
     * @throws InvalidConfigException
     * @throws InvalidArgumentException
     */
    public function read(string $filename): mixed;
}
