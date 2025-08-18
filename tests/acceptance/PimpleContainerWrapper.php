<?php

declare(strict_types=1);

namespace tests\acceptance;

use Pimple\Container;

final class PimpleContainerWrapper extends Container
{
    public function get($id)
    {
        return $this[$id];
    }

    public function has($id): bool
    {
        return isset($this[$id]);
    }
}
