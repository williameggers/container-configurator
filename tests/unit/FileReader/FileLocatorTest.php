<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator\FileReader;

use PHPUnit\Framework\TestCase;
use tests\support\TestFileCreator;
use TomPHP\ContainerConfigurator\FileReader\FileLocator;

final class FileLocatorTest extends TestCase
{
    use TestFileCreator;

    private \TomPHP\ContainerConfigurator\FileReader\FileLocator $fileLocator;

    protected function setUp(): void
    {
        $this->fileLocator = new FileLocator();
    }

    public function testItFindsFilesByGlobbing(): void
    {
        $this->createTestFile('config1.php');
        $this->createTestFile('config2.php');
        $this->createTestFile('config.json');

        $files = $this->fileLocator->locate($this->getTestPath('*.php'));

        $this->assertSame([
            $this->getTestPath('config1.php'),
            $this->getTestPath('config2.php'),
        ], $files);
    }

    public function testItFindsFindsFilesByGlobbingWithBraces(): void
    {
        $this->createTestFile('global.php');
        $this->createTestFile('database.local.php');
        $this->createTestFile('nothing.php');
        $this->createTestFile('nothing.php.dist');

        $files = $this->fileLocator->locate($this->getTestPath('{,*.}{global,local}.php'));

        $this->assertSame([
            $this->getTestPath('global.php'),
            $this->getTestPath('database.local.php'),
        ], $files);
    }
}
