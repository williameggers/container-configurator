<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator;

use ArrayAccess;
use InvalidArgumentException;
use IteratorAggregate;
use TomPHP\ContainerConfigurator\Exception\EntryDoesNotExistException;
use TomPHP\ContainerConfigurator\Exception\ReadOnlyException;
use Traversable;

/**
 * @internal
 */
/**
 * @implements ArrayAccess<int|string, mixed>
 * @implements IteratorAggregate<int|string, mixed>
 */
final class ApplicationConfig implements ArrayAccess, IteratorAggregate
{
    /**
     * @var non-empty-string
     */
    private string $separator;

    /**
     * @param non-empty-string $separator
     *
     * @throws InvalidArgumentException
     */
    public function __construct(/**
         * @var array<int|string,mixed>
         */
        private array $config,
        string $separator = '.'
    ) {
        \Assert\that($separator)->string()->notEmpty();
        $this->separator = $separator;
    }

    /**
     * @param array<int|string,mixed> $config
     */
    public function merge(array $config): void
    {
        $this->config = array_replace_recursive($this->config, $config);
    }

    /**
     * @param non-empty-string $separator
     *
     * @throws InvalidArgumentException
     */
    public function setSeparator(string $separator): void
    {
        \Assert\that($separator)->string()->notEmpty();

        $this->separator = $separator;
    }

    public function getIterator(): Traversable
    {
        return new ApplicationConfigIterator($this);
    }

    /**
     * @return array<int|string>
     */
    public function getKeys(): array
    {
        return array_keys(iterator_to_array(new ApplicationConfigIterator($this)));
    }

    public function offsetExists(mixed $offset): bool
    {
        try {
            $this->traverseConfig($this->getPath((string) $offset));
        } catch (EntryDoesNotExistException) {
            return false;
        }

        return true;
    }

    /**
     * @throws EntryDoesNotExistException
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->traverseConfig($this->getPath((string) $offset));
    }

    /**
     * @throws ReadOnlyException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw ReadOnlyException::fromClassName(self::class);
    }

    /**
     * @param mixed $offset
     *
     * @throws ReadOnlyException
     */
    public function offsetUnset($offset): void
    {
        throw ReadOnlyException::fromClassName(self::class);
    }

    /**
     * @return array<int|string,mixed>
     */
    public function asArray(): array
    {
        return $this->config;
    }

    public function getSeparator(): string
    {
        return $this->separator;
    }

    /**
     * @return array<string>
     */
    private function getPath(string $offset): array
    {
        return explode($this->separator, $offset);
    }

    /**
     * @param array<int|string> $path
     *
     * @throws EntryDoesNotExistException
     */
    private function traverseConfig(array $path): mixed
    {
        $pointer = &$this->config;

        foreach ($path as $node) {
            if (!is_array($pointer) || !array_key_exists($node, $pointer)) {
                throw EntryDoesNotExistException::fromKey(implode($this->separator, $path));
            }

            $pointer = &$pointer[$node];
        }

        return $pointer;
    }
}
