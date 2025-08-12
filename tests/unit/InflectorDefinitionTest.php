<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator;

use PHPUnit\Framework\TestCase;
use TomPHP\ContainerConfigurator\InflectorDefinition;

final class InflectorDefinitionTest extends TestCase
{
    private \TomPHP\ContainerConfigurator\InflectorDefinition $inflectorDefinition;

    protected function setUp(): void
    {
        $this->inflectorDefinition = new InflectorDefinition(
            'interface_name',
            ['method1' => ['arg1', 'arg2']]
        );
    }

    public function testGetInterfaceReturnsTheInterfaceName(): void
    {
        $this->assertSame('interface_name', $this->inflectorDefinition->getInterface());
    }

    public function testGetMethodsReturnsTheMethods(): void
    {
        $this->assertSame(
            ['method1' => ['arg1', 'arg2']],
            $this->inflectorDefinition->getMethods()
        );
    }
}
