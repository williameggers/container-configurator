<?php

declare(strict_types=1);

namespace tests\acceptance;

use League\Container\Container;

final class LeagueContainerAdapterTest extends AbstractContainerAdapterTest
{
    protected function setUp(): void
    {
        $this->container = new Container();
    }
}
