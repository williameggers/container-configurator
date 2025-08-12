<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator;

use PHPUnit\Framework\TestCase;
use TomPHP\ContainerConfigurator\InflectorConfig;
use TomPHP\ContainerConfigurator\InflectorDefinition;

final class InflectorConfigTest extends TestCase
{
    public function testItMapsTheConfigArrayToInflectorDefinitions(): void
    {
        $interface = 'example_interface';
        $methods   = ['method1' => ['arg1', 'arg2']];

        $inflectorConfig = new InflectorConfig([$interface => $methods]);

        $this->assertEquals(
            [new InflectorDefinition($interface, $methods)],
            iterator_to_array($inflectorConfig)
        );
    }
}
