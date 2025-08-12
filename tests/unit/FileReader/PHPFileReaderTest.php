<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator\FileReader;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use tests\support\TestFileCreator;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;
use TomPHP\ContainerConfigurator\FileReader\FileReader;
use TomPHP\ContainerConfigurator\FileReader\PHPFileReader;

final class PHPFileReaderTest extends TestCase
{
    use TestFileCreator;

    private \TomPHP\ContainerConfigurator\FileReader\PHPFileReader $phpFileReader;

    protected function setUp(): void
    {
        $this->phpFileReader = new PHPFileReader();
    }

    public function testItIsAFileReader(): void
    {
        $this->assertInstanceOf(FileReader::class, $this->phpFileReader);
    }

    public function testItThrowsIfFileDoesNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->phpFileReader->read('file-which-does-not-exist');
    }

    public function testReadsAPHPConfigFile(): void
    {
        $config = ['key' => 'value'];
        $code   = '<?php return ' . var_export($config, true) . ';';

        $this->createTestFile('config.php', $code);

        $this->assertEquals($config, $this->phpFileReader->read($this->getTestPath('config.php')));
    }

    public function testItThrowsIfTheConfigIsInvalid(): void
    {
        $this->expectException(InvalidConfigException::class);

        $code = '<?php return 123;';
        $this->createTestFile('config.php', $code);

        $this->phpFileReader->read($this->getTestPath('config.php'));
    }
}
