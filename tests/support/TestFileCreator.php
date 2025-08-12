<?php

declare(strict_types=1);

namespace tests\support;

trait TestFileCreator
{
    /**
     * @var string
     */
    private $configFilePath;

    protected function tearDown(): void
    {
        $this->deleteTestFiles();
    }

    /**
     * @param string $name
     */
    protected function getTestPath($name): string
    {
        $this->ensurePathExists();

        return sprintf('%s/%s', $this->configFilePath, $name);
    }

    /**
     * @param string $filename
     */
    protected function createPHPConfigFile($filename, array $config)
    {
        $code = '<?php return ' . var_export($config, true) . ';';

        $this->createTestFile($filename, $code);
    }

    /**
     * @param string $filename
     */
    protected function createJSONConfigFile($filename, array $config)
    {
        $code = json_encode($config);

        $this->createTestFile($filename, $code);
    }

    /**
     * @param string $name
     * @param string $content
     */
    protected function createTestFile($name, $content = 'test content')
    {
        $this->ensurePathExists();

        file_put_contents(sprintf('%s/%s', $this->configFilePath, $name), $content);
    }

    private function deleteTestFiles(): void
    {
        $this->ensurePathExists();

        // Test for safety!
        if (!str_starts_with($this->configFilePath, __DIR__)) {
            throw new \Exception('DANGER!!! - Config file is not local to this project');
        }

        $files = glob($this->configFilePath . '/*');

        foreach ($files as $file) {
            unlink($file);
        }
    }

    private function ensurePathExists(): void
    {
        $this->configFilePath = __DIR__ . '/../.test-config';

        if (!file_exists($this->configFilePath)) {
            mkdir($this->configFilePath);
        }
    }
}
