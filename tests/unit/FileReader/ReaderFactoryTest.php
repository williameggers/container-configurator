<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator\FileReader;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use tests\support\TestFileCreator;
use TomPHP\ContainerConfigurator\Exception\UnknownFileTypeException;
use TomPHP\ContainerConfigurator\FileReader\JSONFileReader;
use TomPHP\ContainerConfigurator\FileReader\PHPFileReader;
use TomPHP\ContainerConfigurator\FileReader\ReaderFactory;
use TomPHP\ContainerConfigurator\FileReader\YAMLFileReader;

final class ReaderFactoryTest extends TestCase
{
    use TestFileCreator;

    private \TomPHP\ContainerConfigurator\FileReader\ReaderFactory $readerFactory;

    protected function setUp(): void
    {
        $this->readerFactory = new ReaderFactory([
            '.php'  => PHPFileReader::class,
            '.json' => JSONFileReader::class,
            '.yaml' => YAMLFileReader::class,
            '.yml'  => YAMLFileReader::class,
        ]);
    }

    /**
     * @dataProvider providerCreatesAppropriateFileReader
     */
    public function testCreatesAppropriateFileReader(string $extension, string $fileReaderClass): void
    {
        $filename = 'test' . $extension;

        $this->createTestFile($filename);

        $fileReader = $this->readerFactory->create($this->getTestPath($filename));

        $this->assertInstanceOf($fileReaderClass, $fileReader);
    }

    /**
     * @return \Generator
     */
    public function providerCreatesAppropriateFileReader()
    {
        $extensions = [
            '.json' => JSONFileReader::class,
            '.php'  => PHPFileReader::class,
            '.yaml' => YAMLFileReader::class,
            '.yml'  => YAMLFileReader::class,
        ];

        foreach ($extensions as $extension => $fileReaderClass) {
            yield [
                $extension,
                $fileReaderClass,
            ];
        }
    }

    public function testReturnsTheSameReaderForTheSameFileType(): void
    {
        $this->createTestFile('test1.php');
        $this->createTestFile('test2.php');

        $fileReader = $this->readerFactory->create($this->getTestPath('test1.php'));
        $reader2    = $this->readerFactory->create($this->getTestPath('test2.php'));

        $this->assertSame($fileReader, $reader2);
    }

    public function testItThrowsIfTheArgumentIsNotAFileName(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->readerFactory->create('missing-file.xxx');
    }

    public function testItThrowsIfThereIsNoRegisteredReaderForGivenFileType(): void
    {
        $this->createTestFile('test.unknown');

        $this->expectException(UnknownFileTypeException::class);

        $this->readerFactory->create($this->getTestPath('test.unknown'));
    }
}
