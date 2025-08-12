<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\FileReader;

use Assert\Assertion;
use InvalidArgumentException;
use TomPHP\ContainerConfigurator\Exception\NotFileReaderException;
use TomPHP\ContainerConfigurator\Exception\UnknownFileTypeException;

/**
 * @internal
 */
final class ReaderFactory
{
    /**
     * @var array<string,FileReader>
     */
    private array $readers = [];

    /**
     * @param array<string,string> $config
     */
    public function __construct(private readonly array $config)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function create(string $filename): FileReader
    {
        Assertion::file($filename);

        $readerClass = $this->getReaderClass($filename);

        if (!isset($this->readers[$readerClass])) {
            $readerClassInstance = new $readerClass();
            if (!$readerClassInstance instanceof FileReader) {
                throw NotFileReaderException::fromClassName($readerClass);
            }

            $this->readers[$readerClass] = $readerClassInstance;
        }

        return $this->readers[$readerClass];
    }

    /**
     * @throws UnknownFileTypeException
     */
    private function getReaderClass(string $filename): string
    {
        $readerClass = null;

        foreach ($this->config as $extension => $className) {
            if ($this->endsWith($filename, $extension)) {
                $readerClass = $className;
                break;
            }
        }

        if ($readerClass === null) {
            throw UnknownFileTypeException::fromFileExtension(
                $filename,
                array_keys($this->config)
            );
        }

        return $readerClass;
    }

    private function endsWith(string $haystack, string $needle): bool
    {
        return str_ends_with($haystack, $needle);
    }
}
