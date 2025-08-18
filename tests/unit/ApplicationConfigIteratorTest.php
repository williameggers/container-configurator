<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator;

use PHPUnit\Framework\TestCase;
use TomPHP\ContainerConfigurator\ApplicationConfig;

final class ApplicationConfigIteratorTest extends TestCase
{
    public function testItIteratesOverSimpleConfigValues(): void
    {
        $applicationConfig = new ApplicationConfig([
            'keyA'   => 'valueA',
            'keyB'   => 'valueB',
        ]);

        $this->assertSame(
            [
                'keyA'   => 'valueA',
                'keyB'   => 'valueB',
            ],
            iterator_to_array($applicationConfig)
        );
    }

    public function testItIteratesRecursively(): void
    {
        $applicationConfig = new ApplicationConfig([
            'group1' => [
                'keyA'   => 'valueA',
            ],
            'group2' => [
                'keyB'   => 'valueB',
            ],
        ]);

        $this->assertSame(
            [
                'group1' => [
                    'keyA' => 'valueA',
                ],
                'group1.keyA' => 'valueA',
                'group2'      => [
                    'keyB' => 'valueB',
                ],
                'group2.keyB' => 'valueB',
            ],
            iterator_to_array($applicationConfig)
        );
    }

    public function testItGoesMultipleLevels(): void
    {
        $applicationConfig = new ApplicationConfig([
            'group1' => [
                'keyA'   => 'valueA',
                'group2' => [
                    'keyB'   => 'valueB',
                ],
            ],
        ]);

        $this->assertSame(
            [
                'group1' => [
                    'keyA'   => 'valueA',
                    'group2' => [
                        'keyB'   => 'valueB',
                    ],
                ],
                'group1.keyA'   => 'valueA',
                'group1.group2' => [
                    'keyB' => 'valueB',
                ],
                'group1.group2.keyB' => 'valueB',
            ],
            iterator_to_array($applicationConfig)
        );
    }

    public function testItRewinds(): void
    {
        $applicationConfig = new ApplicationConfig([
            'group1' => [
                'keyA'   => 'valueA',
                'keyB'   => 'valueB',
                'keyC'   => 'valueC',
            ],
        ]);

        $applicationConfig->getIterator()->next();
        $applicationConfig->getIterator()->next();
        $applicationConfig->getIterator()->next();

        $this->assertSame(
            [
                'group1' => [
                    'keyA'   => 'valueA',
                    'keyB'   => 'valueB',
                    'keyC'   => 'valueC',
                ],
                'group1.keyA' => 'valueA',
                'group1.keyB' => 'valueB',
                'group1.keyC' => 'valueC',
            ],
            iterator_to_array($applicationConfig)
        );
    }

    public function testItUsesADifferentSeparator(): void
    {
        $applicationConfig = new ApplicationConfig([
            'group1' => [
                'keyA'   => 'valueA',
            ],
        ], '->');

        $this->assertSame(
            [
                'group1' => [
                    'keyA' => 'valueA',
                ],
                'group1->keyA' => 'valueA',
            ],
            iterator_to_array($applicationConfig)
        );
    }
}
