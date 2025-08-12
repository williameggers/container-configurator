<?php

declare(strict_types=1);

namespace tests\unit\TomPHP\ContainerConfigurator\Exception;

use DomainException;
use PHPUnit\Framework\TestCase;
use TomPHP\ContainerConfigurator\Exception\Exception;
use TomPHP\ContainerConfigurator\Exception\UnknownSettingException;

final class UnknownSettingExceptionTest extends TestCase
{
    public function testItImplementsTheBaseExceptionType(): void
    {
        $this->assertInstanceOf(Exception::class, new UnknownSettingException());
    }

    public function testItIsADomainException(): void
    {
        $this->assertInstanceOf(DomainException::class, new UnknownSettingException());
    }

    public function testItCanBeCreatedFromSetting(): void
    {
        $unknownSettingException = UnknownSettingException::fromSetting('unknown', ['setting_a', 'setting_b']);

        $this->assertSame(
            'Setting "unknown" is unknown; valid settings are ["setting_a", "setting_b"].',
            $unknownSettingException->getMessage()
        );
    }
}
