<?php

declare(strict_types=1);

namespace tests\acceptance;

final class PimpleContainerAdapterTest extends AbstractContainerAdapterTest
{
    protected function setUp(): void
    {
        $this->container = new PimpleContainerWrapper();
    }
}
