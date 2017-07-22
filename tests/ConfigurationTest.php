<?php

namespace Construct\Tests;

use Construct\Configuration;
use Construct\Defaults;
use Construct\Settings;
use Construct\Helpers\Filesystem;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    public function test_exception_is_raised_on_non_existent_file()
    {
        $this->setExpectedException('RuntimeException');
        Configuration::getSettings(
            'non-existent-file.txt',
            'example-project',
            'composer,keywords',
            new Filesystem
        );
    }

    public function test_complete_config_is_transformed_into_settings()
    {
        $settings = Configuration::getSettings(
            __DIR__ . '/stubs/config/complete.stub',
            'example-project',
            'composer,keywords',
            new Filesystem
        );

        $this->assertInstanceOf(
            'Construct\Settings',
            $settings
        );

        $expectedSettings = new Settings(
            'example-project',
            'phpspec',
            'MIT',
            'Namespace',
            true,
            true,
            'composer,keywords',
            true,
            true,
            '5.4',
            true,
            true,
            true,
            true,
            true
        );

        $this->assertEquals(
            $expectedSettings,
            $settings,
            "Configuration wasn't transformed into expected Settings object."
        );
    }

    public function test_defaults_are_used_when_not_configured()
    {
        $settings = Configuration::getSettings(
            __DIR__ . '/stubs/config/php+testframework+licenceless.stub',
            'example-project',
            'composer,keywords',
            new Filesystem
        );

        $this->assertSame(
            PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION,
            $settings->getPhpVersion()
        );
        $this->assertSame(
            (new Defaults())->testingFrameworks[0],
            $settings->getTestingFramework()
        );
        $this->assertSame(
            (new Defaults())->licenses[0],
            $settings->getLicense()
        );
    }

    public function test_github_config_implicates_github_templates_and_docs()
    {
        $settings = Configuration::getSettings(
            __DIR__ . '/stubs/config/complete.github.stub',
            'example-project',
            'composer,keywords',
            new Filesystem
        );

        $expectedSettings = new Settings(
            'example-project',
            'phpspec',
            'MIT',
            'Namespace',
            true,
            true,
            'composer,keywords',
            true,
            true,
            '5.4',
            true,
            true,
            true,
            true,
            true
        );

        $this->assertEquals(
            $expectedSettings,
            $settings,
            "Configuration wasn't transformed into expected Settings object."
        );
    }
}
