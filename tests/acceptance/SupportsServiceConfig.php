<?php

declare(strict_types=1);

namespace tests\acceptance;

use tests\mocks\ExampleClass;
use tests\mocks\ExampleClassWithArgs;
use tests\mocks\ExampleFactory;
use TomPHP\ContainerConfigurator\Configurator;

trait SupportsServiceConfig
{
    public function testItAddsServicesToTheContainer(): void
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => ExampleClass::class,
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $this->assertInstanceOf(ExampleClass::class, $this->container->get('example_class'));
    }

    public function testItAddsServicesToTheContainerForADifferentConfigKey(): void
    {
        $config = [
            'di' => [
                'example_class' => [
                    'class' => ExampleClass::class,
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->withSetting(Configurator::SETTING_SERVICES_KEY, 'di')
            ->to($this->container);

        $this->assertInstanceOf(ExampleClass::class, $this->container->get('example_class'));
    }

    public function testItCreatesUniqueServiceInstancesByDefault(): void
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class'     => ExampleClass::class,
                        'singleton' => false,
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        $this->assertNotSame($instance1, $instance2);
    }

    public function testItCanCreateSingletonServiceInstances(): void
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class'     => ExampleClass::class,
                        'singleton' => true,
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        $this->assertSame($instance1, $instance2);
    }

    public function testItCanCreateSingletonServiceInstancesByDefault(): void
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class' => ExampleClass::class,
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->withSetting(Configurator::SETTING_DEFAULT_SINGLETON_SERVICES, true)
            ->to($this->container);

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        $this->assertSame($instance1, $instance2);
    }

    public function testItCanCreateUniqueServiceInstancesWhenSingletonIsDefault(): void
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class'     => ExampleClass::class,
                        'singleton' => false,
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->withSetting(Configurator::SETTING_DEFAULT_SINGLETON_SERVICES, true)
            ->to($this->container);

        $instance1 = $this->container->get('example_class');
        $instance2 = $this->container->get('example_class');

        $this->assertNotSame($instance1, $instance2);
    }

    public function testItAddsConstructorArguments(): void
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class'     => ExampleClassWithArgs::class,
                        'arguments' => [
                            'arg1',
                            'arg2',
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_class');

        $this->assertEquals(['arg1', 'arg2'], $instance->getConstructorArgs());
    }

    public function testItResolvesConstructorArgumentsIfTheyAreServiceNames(): void
    {
        $config = [
            'arg1' => 'value1',
            'arg2' => 'value2',
            'di'   => [
                'services' => [
                    'example_class' => [
                        'class'     => ExampleClassWithArgs::class,
                        'arguments' => [
                            'config.arg1',
                            'config.arg2',
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_class');

        $this->assertEquals(['value1', 'value2'], $instance->getConstructorArgs());
    }

    public function testItUsesTheStringIfConstructorArgumentsAreClassNames(): void
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class'     => ExampleClassWithArgs::class,
                        'arguments' => [
                            ExampleClass::class,
                            'arg2',
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_class');

        $this->assertEquals([ExampleClass::class, 'arg2'], $instance->getConstructorArgs());
    }

    public function testItUsesComplexConstructorArguments(): void
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class'     => ExampleClassWithArgs::class,
                        'arguments' => [
                            ['example_array'],
                            new \stdClass(),
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_class');

        $this->assertEquals([['example_array'], new \stdClass()], $instance->getConstructorArgs());
    }

    public function testItCallsSetterMethods(): void
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class'   => ExampleClass::class,
                        'methods' => [
                            'setValue' => ['the value'],
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_class');

        $this->assertEquals('the value', $instance->getValue());
    }

    public function testItResolvesSetterMethodArgumentsIfTheyAreServiceNames(): void
    {
        $config = [
            'arg' => 'value',
            'di'  => [
                'services' => [
                    'example_class' => [
                        'class'   => ExampleClass::class,
                        'methods' => [
                            'setValue' => ['config.arg'],
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_class');

        $this->assertEquals('value', $instance->getValue());
    }

    public function testItUsesTheStringIffSetterMethodArgumentsAreClassNames(): void
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class'   => ExampleClass::class,
                        'methods' => [
                            'setValue' => [ExampleClass::class],
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_class');

        $this->assertSame(ExampleClass::class, $instance->getValue());
    }

    public function testIsCreatesAServiceThroughAFactoryClass(): void
    {
        $config = [
            'class_name' => ExampleClassWithArgs::class,
            'di'         => [
                'services' => [
                    'example_service' => [
                        'factory'   => ExampleFactory::class,
                        'arguments' => [
                            'config.class_name',
                            'example_argument',
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_service');

        $this->assertInstanceOf(ExampleClassWithArgs::class, $instance);
        $this->assertSame(['example_argument'], $instance->getConstructorArgs());
    }

    public function testItCanCreateAServiceAlias(): void
    {
        $config = [
            'di' => [
                'services' => [
                    'example_class' => [
                        'class'     => ExampleClass::class,
                        'singleton' => true,
                    ],
                    'example_alias' => [
                        'service' => 'example_class',
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $this->assertSame($this->container->get('example_class'), $this->container->get('example_alias'));
    }

    public function testItInjectsTheContainerAsAConstructorDependency(): void
    {
        $config = [
            'di' => [
                'services' => [
                    'example_service' => [
                        'class'     => ExampleClassWithArgs::class,
                        'arguments' => [Configurator::container()],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_service');

        $this->assertSame([$this->container], $instance->getConstructorArgs());
    }

    public function testItInjectsTheContainerAsAMethodDependency(): void
    {
        $config = [
            'di' => [
                'services' => [
                    'example_service' => [
                        'class'   => ExampleClass::class,
                        'methods' => [
                            'setValue' => [Configurator::container()],
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_service');

        $this->assertSame($this->container, $instance->getValue());
    }

    public function testItInjectsTheContainerAsFactoryDependency(): void
    {
        $config = [
            'class_name' => ExampleClassWithArgs::class,
            'di'         => [
                'services' => [
                    'example_service' => [
                        'factory'   => ExampleFactory::class,
                        'arguments' => [
                            'config.class_name',
                            Configurator::container(),
                        ],
                    ],
                ],
            ],
        ];

        Configurator::apply()
            ->configFromArray($config)
            ->to($this->container);

        $instance = $this->container->get('example_service');

        $this->assertSame([$this->container], $instance->getConstructorArgs());
    }
}
