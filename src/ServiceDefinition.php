<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator;

use Assert\Assertion;
use InvalidArgumentException;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;

/**
 * @internal
 */
final class ServiceDefinition
{
    private readonly string $name;

    private readonly string $class;

    private readonly bool $isSingleton;

    private readonly bool $isFactory;

    private readonly bool $isAlias;

    /**
     * @var array<mixed>
     */
    private readonly array $arguments;

    /**
     * @var array<string,array<mixed>>
     */
    private readonly array $methods;

    /**
     * @param array{
     *  singleton?:bool,
     *  factory?:mixed,
     *  service?:mixed,
     *  arguments?:array<mixed>,
     *  methods?:array<string,
     *  array<mixed>>
     * } $config
     *
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     */
    public function __construct(string $name, array $config, bool $singletonDefault = false)
    {
        Assertion::string($name);
        Assertion::boolean($singletonDefault);

        $this->name        = $name;
        $this->class       = $this->className($name, $config);
        $this->isSingleton =
            isset($config['singleton']) && is_bool($config['singleton']) ? $config['singleton'] : $singletonDefault;
        $this->isFactory   = isset($config['factory']);
        $this->isAlias     = isset($config['service']);
        $this->arguments   = isset($config['arguments']) && is_array($config['arguments']) ? $config['arguments'] : [];
        $this->methods     = isset($config['methods']) && is_array($config['methods']) ? $config['methods'] : [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function isSingleton(): bool
    {
        return $this->isSingleton;
    }

    public function isFactory(): bool
    {
        return $this->isFactory;
    }

    public function isAlias(): bool
    {
        return $this->isAlias;
    }

    /**
     * @return array<mixed>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return array<string,array<mixed>>
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param array<string,mixed> $config
     *
     * @throws InvalidConfigException
     */
    private function className(string $name, array $config): string
    {
        if (isset($config['class']) && isset($config['factory'])) {
            throw InvalidConfigException::fromNameWhenClassAndFactorySpecified($name);
        }

        if (isset($config['class']) && isset($config['service'])) {
            throw InvalidConfigException::fromNameWhenClassAndServiceSpecified($name);
        }

        if (isset($config['factory']) && isset($config['service'])) {
            throw InvalidConfigException::fromNameWhenFactoryAndServiceSpecified($name);
        }

        if (isset($config['service']) && is_string($config['service'])) {
            return $config['service'];
        }

        if (isset($config['class']) && is_string($config['class'])) {
            return $config['class'];
        }

        if (isset($config['factory']) && is_string($config['factory'])) {
            return $config['factory'];
        }

        return $name;
    }
}
