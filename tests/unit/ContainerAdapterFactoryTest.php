<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator;

use PHPUnit\Framework\TestCase;
use tests\mocks\ExampleContainer;
use tests\mocks\ExampleContainerAdapter;
use tests\mocks\ExampleExtendedContainer;
use tests\mocks\NotContainerAdapter;
use TomPHP\ContainerConfigurator\ContainerAdapterFactory;
use TomPHP\ContainerConfigurator\Exception\NotContainerAdapterException;
use TomPHP\ContainerConfigurator\Exception\UnknownContainerException;

final class ContainerAdapterFactoryTest extends TestCase
{
    private \TomPHP\ContainerConfigurator\ContainerAdapterFactory $containerAdapterFactory;

    protected function setUp(): void
    {
        $this->containerAdapterFactory = new ContainerAdapterFactory([
            ExampleContainer::class => ExampleContainerAdapter::class,
        ]);
    }

    public function testItCreatesAnInstanceOfTheContainerAdapter(): void
    {
        $this->assertInstanceOf(
            ExampleContainerAdapter::class,
            $this->containerAdapterFactory->create(new ExampleContainer())
        );
    }

    public function testItCreatesAnInstanceOfTheConfiguratorForSubclassedContainer(): void
    {
        $this->assertInstanceOf(
            ExampleContainerAdapter::class,
            $this->containerAdapterFactory->create(new ExampleExtendedContainer())
        );
    }

    public function testItThrowsIfContainerIsNotKnown(): void
    {
        $this->expectException(UnknownContainerException::class);

        $this->containerAdapterFactory->create(new \stdClass());
    }

    public function testItThrowsIfNotAContainerAdapter(): void
    {
        $this->containerAdapterFactory = new ContainerAdapterFactory([
            ExampleContainer::class => NotContainerAdapter::class,
        ]);

        $this->expectException(NotContainerAdapterException::class);

        $this->containerAdapterFactory->create(new ExampleContainer());
    }

    public function testItSetsTheContainerOnTheConfigurator(): void
    {
        $exampleContainer    = new ExampleContainer();
        $containerAdapter    = $this->containerAdapterFactory->create($exampleContainer);

        $this->assertSame($exampleContainer, $containerAdapter->getContainer());
    }
}
