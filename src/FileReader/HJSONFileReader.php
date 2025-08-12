<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator\FileReader;

use Assert\Assertion;
use HJSON\HJSONException;
use HJSON\HJSONParser;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;
use TomPHP\ContainerConfigurator\Exception\MissingDependencyException;

/**
 * @internal
 */
final class HJSONFileReader implements FileReader
{
    private const JSON_ERRORS = [
        JSON_ERROR_NONE                  => null,
        JSON_ERROR_DEPTH                 => 'Maximum stack depth exceeded',
        JSON_ERROR_STATE_MISMATCH        => 'Underflow or the modes mismatch',
        JSON_ERROR_CTRL_CHAR             => 'Unexpected control character found',
        JSON_ERROR_SYNTAX                => 'Syntax error, malformed JSON',
        JSON_ERROR_UTF8                  => 'Malformed UTF-8 characters, possibly incorrectly encoded',
        JSON_ERROR_RECURSION             => 'One or more recursive references in the value to be encoded',
        JSON_ERROR_INF_OR_NAN            => 'One or more NAN or INF values in the value to be encoded',
        JSON_ERROR_UNSUPPORTED_TYPE      => 'A value of a type that cannot be encoded was given',
        JSON_ERROR_INVALID_PROPERTY_NAME => 'A property name that cannot be encoded was given',
        JSON_ERROR_UTF16                 => 'Malformed UTF-16 characters, possibly incorrectly encoded',
    ];

    private readonly HJSONParser $hjsonParser;

    /**
     * @throws MissingDependencyException
     */
    public function __construct()
    {
        if (!class_exists(HJSONParser::class)) {
            throw MissingDependencyException::fromPackageName('laktak/hjson');
        }

        $this->hjsonParser = new HJSONParser();
    }

    public function read(string $filename): mixed
    {
        Assertion::file($filename);

        try {
            $config = $this->hjsonParser->parse(
                file_get_contents($filename),
                [
                    'assoc' => true, // boolean, return associative array instead of object
                ]
            );
        } catch (HJSONException $hjsonException) {
            throw InvalidConfigException::fromHJSONFileError($filename, $hjsonException->getMessage());
        }

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw InvalidConfigException::fromHJSONFileError($filename, $this->getJsonError());
        }

        return $config;
    }

    private function getJsonError(): string
    {
        if (function_exists('json_last_error_msg')) {
            return json_last_error_msg();
        }

        return self::JSON_ERRORS[json_last_error()] ?? 'Unknown error';
    }
}
