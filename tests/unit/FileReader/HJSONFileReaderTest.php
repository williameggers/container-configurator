<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator\FileReader;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use tests\support\TestFileCreator;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;
use TomPHP\ContainerConfigurator\FileReader\FileReader;
use TomPHP\ContainerConfigurator\FileReader\HJSONFileReader;

final class HJSONFileReaderTest extends TestCase
{
    use TestFileCreator;

    private \TomPHP\ContainerConfigurator\FileReader\HJSONFileReader $hjsonFileReader;

    protected function setUp(): void
    {
        $this->hjsonFileReader = new HJSONFileReader();
    }

    public function testItIsAFileReader(): void
    {
        $this->assertInstanceOf(FileReader::class, $this->hjsonFileReader);
    }

    public function testItThrowsIfFileDoesNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->hjsonFileReader->read('file-which-does-not-exist');
    }

    public function testReadsAPHPConfigFile(): void
    {
        $config = ['key' => 'value', 'sub' => ['key' => 'value']];

        $this->createTestFile('config.hjson', json_encode($config));

        $this->assertEquals($config, $this->hjsonFileReader->read($this->getTestPath('config.hjson')));
    }

    public function testItThrowsIfTheConfigIsInvalid(): void
    {
        $this->expectException(InvalidConfigException::class);

        $invalidHjson = <<<HJSON
        {
                xxx
        HJSON;
        $this->createTestFile('config.hjson', $invalidHjson);

        $this->hjsonFileReader->read($this->getTestPath('config.hjson'));
    }
}
