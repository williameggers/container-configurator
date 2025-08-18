<?php

declare(strict_types=1);

namespace tests\acceptance;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use tests\support\TestFileCreator;
use TomPHP\ContainerConfigurator\Configurator;

abstract class AbstractContainerAdapterTest extends TestCase
{
    use SupportsApplicationConfig;
    use SupportsServiceConfig;
    use SupportsInflectorConfig;
    use TestFileCreator;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function testItCanBeConfiguredFromAFile(): void
    {
        $config = ['example-key' => 'example-value'];

        $this->createJSONConfigFile('config.json', $config);

        Configurator::apply()
            ->configFromFile($this->getTestPath('config.json'))
            ->to($this->container);

        $this->assertSame('example-value', $this->container->get('config.example-key'));
    }

    public function testItCanBeConfiguredFromFiles(): void
    {
        $config = ['example-key' => 'example-value'];

        $this->createJSONConfigFile('config.json', $config);

        Configurator::apply()
            ->configFromFiles($this->getTestPath('*'))
            ->to($this->container);

        $this->assertSame('example-value', $this->container->get('config.example-key'));
    }

    public function testItAddToConfigUsingFiles(): void
    {
        $config = ['keyB' => 'valueB'];

        $this->createJSONConfigFile('config.json', $config);

        Configurator::apply()
            ->configFromArray(['keyA' => 'valueA', 'keyB' => 'valueX'])
            ->configFromFiles($this->getTestPath('*'))
            ->to($this->container);

        $this->assertSame('valueA', $this->container->get('config.keyA'));
        $this->assertSame('valueB', $this->container->get('config.keyB'));
    }
}
