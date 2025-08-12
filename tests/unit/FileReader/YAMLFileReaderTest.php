<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator\FileReader;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml;
use tests\support\TestFileCreator;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;
use TomPHP\ContainerConfigurator\FileReader\FileReader;
use TomPHP\ContainerConfigurator\FileReader\YAMLFileReader;

final class YAMLFileReaderTest extends TestCase
{
    use TestFileCreator;

    private \TomPHP\ContainerConfigurator\FileReader\YAMLFileReader $yamlFileReader;

    protected function setUp(): void
    {
        $this->yamlFileReader = new YAMLFileReader();
    }

    public function testItIsAFileReader(): void
    {
        $this->assertInstanceOf(FileReader::class, $this->yamlFileReader);
    }

    public function testItThrowsIfFileDoesNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->yamlFileReader->read('file-which-does-not-exist');
    }

    public function testReadsAYAMLConfigFile(): void
    {
        $config = ['key' => 'value', 'sub' => ['key' => 'value']];

        $this->createTestFile('config.yml', Yaml\Yaml::dump($config));

        $this->assertEquals($config, $this->yamlFileReader->read($this->getTestPath('config.yml')));
    }

    public function testItThrowsIfTheConfigIsInvalid(): void
    {
        $this->expectException(InvalidConfigException::class);

        $this->createTestFile('config.yml', '[not yaml;');

        $this->yamlFileReader->read($this->getTestPath('config.yml'));
    }
}
