<?php

declare(strict_types=1);

namespace tests\mocks\FileReader;

use TomPHP\ContainerConfigurator\FileReader\FileReader;

final class CustomFileReader implements FileReader
{
    private static array $reads = [];

    public static function reset(): void
    {
        self::$reads = [];
    }

    public static function getReads(): array
    {
        return self::$reads;
    }

    public function read(string $filename): mixed
    {
        self::$reads[] = $filename;

        return [];
    }
}
