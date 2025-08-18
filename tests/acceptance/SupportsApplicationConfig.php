<?php

declare(strict_types=1);

namespace tests\acceptance;

use TomPHP\ContainerConfigurator\Configurator;

trait SupportsApplicationConfig
{
    public function testItAddsConfigToTheContainer(): void
    {
        $config = ['keyA' => 'valueA'];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $this->assertEquals('valueA', $this->container->get('config.keyA'));
        $this->assertIsArray($this->container->get('config'));
        $this->assertArrayHasKey('keyA', $this->container->get('config'));
    }

    public function testItCascadeAddsConfigToTheContainer(): void
    {
        Configurator::apply()
            ->configFromArray(['keyA' => 'valueA', 'keyB' => 'valueX'])
            ->configFromArray(['keyB' => 'valueB'])
            ->to($this->container);

        $this->assertEquals('valueA', $this->container->get('config.keyA'));
    }

    public function testItAddsGroupedConfigToTheContainer(): void
    {
        Configurator::apply()
            ->configFromArray(['group1' => ['keyA' => 'valueA']])
            ->to($this->container);

        $this->assertEquals(['keyA' => 'valueA'], $this->container->get('config.group1'));
        $this->assertEquals('valueA', $this->container->get('config.group1.keyA'));
    }

    public function testItAddsConfigToTheContainerWithAnAlternativeSeparator(): void
    {
        Configurator::apply()
            ->configFromArray(['keyA' => 'valueA'])
            ->withSetting(Configurator::SETTING_SEPARATOR, '/')
            ->to($this->container);

        $this->assertEquals('valueA', $this->container->get('config/keyA'));
    }

    public function testItAddsConfigToTheContainerWithAnAlternativePrefix(): void
    {
        Configurator::apply()
            ->configFromArray(['keyA' => 'valueA'])
            ->withSetting(Configurator::SETTING_PREFIX, 'settings')
            ->to($this->container);

        $this->assertEquals('valueA', $this->container->get('settings.keyA'));
        $this->assertIsArray($this->container->get('settings'));
        $this->assertArrayHasKey('keyA', $this->container->get('settings'));
    }

    public function testItAddsConfigToTheContainerWithNoPrefix(): void
    {
        Configurator::apply()
            ->configFromArray(['keyA' => 'valueA'])
            ->withSetting(Configurator::SETTING_PREFIX, '')
            ->to($this->container);

        $this->assertEquals('valueA', $this->container->get('keyA'));
    }
}
