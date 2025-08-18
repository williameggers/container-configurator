<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\Exception;

use DomainException;
use TomPHP\ExceptionConstructorTools;

final class UnknownSettingException extends DomainException implements Exception
{
    use ExceptionConstructorTools;

    /**
     * @internal
     *
     * @param string[] $knownSettings
     */
    public static function fromSetting(string $setting, array $knownSettings): self
    {
        return self::create(
            'Setting "%s" is unknown; valid settings are %s.',
            [$setting, self::listToString($knownSettings)]
        );
    }
}
