<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * @internal
 *
 * @extends RecursiveIteratorIterator<RecursiveArrayIterator>
 */
final class ApplicationConfigIterator extends RecursiveIteratorIterator
{
    /**
     * @var string[]
     */
    private array $path = [];

    private readonly string $separator;

    public function __construct(ApplicationConfig $applicationConfig)
    {
        parent::__construct(
            new RecursiveArrayIterator($applicationConfig->asArray()),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $this->separator = $applicationConfig->getSeparator();
    }

    public function key(): mixed
    {
        return implode($this->separator, array_merge($this->path, [parent::key()]));
    }

    public function next(): void
    {
        if ($this->callHasChildren()) {
            $key = parent::key();
            if (is_string($key) || is_int($key) || ($key instanceof \Stringable)) {
                $this->path[] = (string) $key;
            }
        }

        parent::next();
    }

    public function rewind(): void
    {
        $this->path = [];

        parent::rewind();
    }

    public function endChildren(): void
    {
        array_pop($this->path);
    }
}
