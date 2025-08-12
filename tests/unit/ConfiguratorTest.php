<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Pimple\Container;
use tests\mocks\ExampleContainer;
use tests\mocks\ExampleContainerAdapter;
use tests\mocks\FileReader\CustomFileReader;
use tests\support\TestFileCreator;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\ContainerConfigurator\Exception\NoMatchingFilesException;
use TomPHP\ContainerConfigurator\Exception\UnknownSettingException;

final class ConfiguratorTest extends TestCase
{
    use TestFileCreator;

    public function testItThrowsAnExceptionWhenTheFileIsNotFound(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Configurator::apply()->configFromFile($this->getTestPath('config.php'));
    }

    public function testItThrowsAnExceptionWhenNoFilesAreNotFound(): void
    {
        $this->expectException(NoMatchingFilesException::class);

        Configurator::apply()->configFromFiles($this->getTestPath('config.php'));
    }

    public function testItThrowsWhenAnUnknownSettingIsSet(): void
    {
        $this->expectException(UnknownSettingException::class);

        Configurator::apply()->withSetting('unknown_setting', 'value');
    }

    public function testTheContainerIdentifierStringIsAlwaysTheSame(): void
    {
        $this->assertSame(Configurator::container(), Configurator::container());
    }

    public function testItCanAcceptADifferentFileReader(): void
    {
        $container = new Container();
        $this->createTestFile('custom.xxx');
        CustomFileReader::reset();

        $configFile = $this->getTestPath('custom.xxx');
        Configurator::apply()
            ->withFileReader('.xxx', CustomFileReader::class)
            ->configFromFile($configFile)
            ->to($container);

        $this->assertSame([$configFile], CustomFileReader::getReads());
    }

    public function testItCanUseDifferentContainerAdapters(): void
    {
        $exampleContainer = new ExampleContainer();
        ExampleContainerAdapter::reset();

        Configurator::apply()
            ->withContainerAdapter(ExampleContainer::class, ExampleContainerAdapter::class)
            ->configFromArray([])
            ->to($exampleContainer);

        $this->assertSame(1, ExampleContainerAdapter::getNumberOfInstances());
    }
}
