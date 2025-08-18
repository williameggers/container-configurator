<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use tests\support\TestFileCreator;
use TomPHP\ContainerConfigurator\ApplicationConfig;
use TomPHP\ContainerConfigurator\Exception\ReadOnlyException;

final class ApplicationConfigTest extends TestCase
{
    use TestFileCreator;

    private \TomPHP\ContainerConfigurator\ApplicationConfig|array $config;

    protected function setUp(): void
    {
        $this->config = new ApplicationConfig([
            'keyA'   => 'valueA',
            'group1' => [
                'keyB' => 'valueB',
                'null' => null,
            ],
        ]);
    }

    public function testItProvidesAccessToSimpleScalarValues(): void
    {
        $this->assertEquals('valueA', $this->config['keyA']);
    }

    public function testItProvidesAccessToArrayValues(): void
    {
        $this->assertEquals(['keyB' => 'valueB', 'null' => null], $this->config['group1']);
    }

    public function testItProvidesToSubValuesUsingDotNotation(): void
    {
        $this->assertEquals('valueB', $this->config['group1.keyB']);
    }

    public function testItSaysIfAnEntryIsSet(): void
    {
        $this->assertArrayHasKey('group1.keyB', $this->config);
    }

    public function testItSaysIfAnEntryIsNotSet(): void
    {
        $this->assertArrayNotHasKey('bad.entry', $this->config);
    }

    public function testItSaysIfAnEntryIsSetIfItIsFalsey(): void
    {
        $this->assertArrayHasKey('group1.null', $this->config);
    }

    public function testItReturnsAllItsKeys(): void
    {
        $this->assertSame(
            [
                'keyA',
                'group1',
                'group1.keyB',
                'group1.null',
            ],
            $this->config->getKeys()
        );
    }

    public function testItCanBeConvertedToAnArray(): void
    {
        $this->assertEquals(
            [
                'keyA'   => 'valueA',
                'group1' => [
                    'keyB' => 'valueB',
                    'null' => null,
                ],
            ],
            $this->config->asArray()
        );
    }

    public function testItWorksWithADifferentSeperator(): void
    {
        $this->config = new ApplicationConfig([
            'group1' => [
                'keyA' => 'valueA',
            ],
        ], '->');
        $this->assertEquals('valueA', $this->config['group1->keyA']);
    }

    public function testItThrowsForAnEmptySeparatorOnConstruction(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->config = new ApplicationConfig([], '');
    }

    public function testItCannotHaveAValueSet(): void
    {
        $this->expectException(ReadOnlyException::class);

        $this->config['key'] = 'value';
    }

    public function testItCannotHaveAValueRemoved(): void
    {
        $this->expectException(ReadOnlyException::class);

        unset($this->config['keyA']);
    }

    public function testItMergesInNewConfig(): void
    {
        $applicationConfig = new ApplicationConfig([
            'group' => [
                'keyA' => 'valueA',
                'keyB' => 'valueX',
            ],
        ]);

        $applicationConfig->merge(['group' => ['keyB' => 'valueB']]);

        $this->assertSame('valueA', $applicationConfig['group.keyA']);
        $this->assertSame('valueB', $applicationConfig['group.keyB']);
    }

    public function testItUpdatesTheSeparator(): void
    {
        $applicationConfig = new ApplicationConfig([
            'group' => [
                'keyA' => 'valueA',
            ],
        ]);

        $applicationConfig->setSeparator('/');

        $this->assertSame('valueA', $applicationConfig['group/keyA']);
    }

    public function testItThrowsForAnEmptySeparatorWhenSettingSeparator(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->config = new ApplicationConfig([]);
        $this->config->setSeparator('');
    }
}
