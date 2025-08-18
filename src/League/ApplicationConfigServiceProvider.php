<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\League;

use Assert\Assertion;
use InvalidArgumentException;
use League\Container\ServiceProvider\AbstractServiceProvider;
use TomPHP\ContainerConfigurator\ApplicationConfig;

/**
 * @internal
 */
final class ApplicationConfigServiceProvider extends AbstractServiceProvider
{
    private readonly string $prefix;

    /**
     * @var array<string>
     */
    private readonly array $provides;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(/**
         * @var ApplicationConfig<int|string,mixed>
         */
        private readonly ApplicationConfig $applicationConfig,
        string $prefix
    ) {
        Assertion::string($prefix);

        $this->prefix   = $prefix;
        $this->provides = array_merge(array_map(
            fn (int|string $key): string => $this->keyPrefix() . $key,
            $this->applicationConfig->getKeys()
        ), [$this->prefix]);
    }

    public function provides(string $id): bool
    {
        return in_array($id, $this->provides);
    }

    public function register(): void
    {
        $prefix = $this->keyPrefix();

        if ($prefix !== '' && $prefix !== '0') {
            $this->container?->addShared($this->prefix, fn (): array => $this->applicationConfig->asArray());
        }

        foreach ($this->applicationConfig as $key => $value) {
            // @phpstan-ignore-next-line
            if (!is_string($key) && !is_int($key) && !($key instanceof \Stringable)) {
                continue;
            }

            $this->container?->addShared($prefix . $key, fn (): mixed => $value);
        }
    }

    private function keyPrefix(): string
    {
        if ($this->prefix === '' || $this->prefix === '0') {
            return '';
        }

        return $this->prefix . $this->applicationConfig->getSeparator();
    }
}
