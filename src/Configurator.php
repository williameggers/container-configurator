<?php

declare(strict_types=1);

namespace TomPHP\ContainerConfigurator;

use Assert\Assertion;
use InvalidArgumentException;
use TomPHP\ContainerConfigurator\Exception\NoMatchingFilesException;
use TomPHP\ContainerConfigurator\Exception\UnknownSettingException;

final class Configurator
{
    public const SETTING_PREFIX                     = 'config_prefix';

    public const SETTING_SEPARATOR                  = 'config_separator';

    public const SETTING_SERVICES_KEY               = 'services_key';

    public const SETTING_INFLECTORS_KEY             = 'inflectors_key';

    public const SETTING_DEFAULT_SINGLETON_SERVICES = 'default_singleton_services';

    public const FILE_READERS = [
        '.json'  => FileReader\JSONFileReader::class,
        '.hjson' => FileReader\HJSONFileReader::class,
        '.php'   => FileReader\PHPFileReader::class,
        '.yaml'  => FileReader\YAMLFileReader::class,
        '.yml'   => FileReader\YAMLFileReader::class,
    ];

    public const CONTAINER_ADAPTERS = [
        \League\Container\Container::class => League\LeagueContainerAdapter::class,
        \Pimple\Container::class           => Pimple\PimpleContainerAdapter::class,
    ];

    private \TomPHP\ContainerConfigurator\ApplicationConfig $applicationConfig;

    private ?\TomPHP\ContainerConfigurator\FileReader\ReaderFactory $readerFactory = null;

    /**
     * @var array<string,mixed>
     */
    private array $settings = [
        self::SETTING_PREFIX                     => 'config',
        self::SETTING_SEPARATOR                  => '.',
        self::SETTING_SERVICES_KEY               => 'di.services',
        self::SETTING_INFLECTORS_KEY             => 'di.inflectors',
        self::SETTING_DEFAULT_SINGLETON_SERVICES => false,
    ];

    /**
     * @var array<string,string>
     */
    private array $fileReaders = self::FILE_READERS;

    /**
     * @var array<string,string>
     */
    private array $containerAdapters = self::CONTAINER_ADAPTERS;

    private static ?string $containerIdentifier = null;

    public static function apply(): self
    {
        return new self();
    }

    private function __construct()
    {
        $this->applicationConfig = new ApplicationConfig([]);
    }

    public static function container(): string
    {
        if (self::$containerIdentifier === null
            || self::$containerIdentifier === ''
            || self::$containerIdentifier === '0'
        ) {
            self::$containerIdentifier = uniqid(self::class . '::CONTAINER_ID::');
        }

        return self::$containerIdentifier;
    }

    /**
     * @param array<mixed> $config
     */
    public function configFromArray(array $config): self
    {
        $this->applicationConfig->merge($config);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return $this
     */
    public function configFromFile(string $filename): self
    {
        Assertion::file($filename);

        $this->readFileAndMergeConfig($filename);

        return $this;
    }

    /**
     * @throws NoMatchingFilesException
     * @throws InvalidArgumentException
     *
     * @return $this
     */
    public function configFromFiles(string $pattern): self
    {
        Assertion::string($pattern);

        $fileLocator = new FileReader\FileLocator();

        $files = $fileLocator->locate($pattern);

        if ($files === []) {
            throw NoMatchingFilesException::fromPattern($pattern);
        }

        foreach ($files as $file) {
            $this->readFileAndMergeConfig($file);
        }

        return $this;
    }

    /**
     * @throws UnknownSettingException
     * @throws InvalidArgumentException
     *
     * @return $this
     */
    public function withSetting(string $name, mixed $value): self
    {
        Assertion::string($name);
        Assertion::scalar($value);

        if (!array_key_exists($name, $this->settings)) {
            throw UnknownSettingException::fromSetting($name, array_keys($this->settings));
        }

        $this->settings[$name] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function withFileReader(string $extension, string $className): self
    {
        $this->fileReaders[$extension] = $className;

        return $this;
    }

    /**
     * @return $this
     */
    public function withContainerAdapter(string $containerName, string $adapterName): self
    {
        $this->containerAdapters[$containerName] = $adapterName;

        return $this;
    }

    public function to(object $container): void
    {
        if (is_string($this->settings[self::SETTING_SEPARATOR])
            && (isset($this->settings[self::SETTING_SEPARATOR])
            && ($this->settings[self::SETTING_SEPARATOR] !== ''
            && $this->settings[self::SETTING_SEPARATOR] !== '0'))
        ) {
            $this->applicationConfig->setSeparator($this->settings[self::SETTING_SEPARATOR]);
        }

        $containerAdapterFactory = new ContainerAdapterFactory($this->containerAdapters);

        $containerAdapter = $containerAdapterFactory->create($container);

        if (!is_string($this->settings[self::SETTING_PREFIX])) {
            throw new \InvalidArgumentException('The SETTING_PREFIX must be a string.');
        }

        $containerAdapter->addApplicationConfig($this->applicationConfig, $this->settings[self::SETTING_PREFIX]);

        if (isset($this->applicationConfig[$this->settings[self::SETTING_SERVICES_KEY]])) {
            $containerAdapter->addServiceConfig(new ServiceConfig(
                $this->applicationConfig[$this->settings[self::SETTING_SERVICES_KEY]], // @phpstan-ignore-line
                (bool) $this->settings[self::SETTING_DEFAULT_SINGLETON_SERVICES]
            ));
        }

        if (isset($this->applicationConfig[$this->settings[self::SETTING_INFLECTORS_KEY]])) {
            $containerAdapter->addInflectorConfig(new InflectorConfig(
                $this->applicationConfig[$this->settings[self::SETTING_INFLECTORS_KEY]] // @phpstan-ignore-line
            ));
        }
    }

    private function readFileAndMergeConfig(string $filename): void
    {
        $fileReader = $this->getReaderFor($filename);
        $config     = $fileReader->read($filename);
        if (!is_array($config)) {
            throw new \InvalidArgumentException(sprintf("Configuration file '%s' did not return an array.", $filename));
        }

        $this->applicationConfig->merge($config);
    }

    private function getReaderFor(string $filename): FileReader\FileReader
    {
        if (!$this->readerFactory instanceof \TomPHP\ContainerConfigurator\FileReader\ReaderFactory) {
            $this->readerFactory = new FileReader\ReaderFactory($this->fileReaders);
        }

        return $this->readerFactory->create($filename);
    }
}
