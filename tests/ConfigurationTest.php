<?php

namespace JonathanTorres\Construct\Tests;

use JonathanTorres\Construct\Configuration;
use JonathanTorres\Construct\Defaults;
use JonathanTorres\Construct\Settings;
use JonathanTorres\Construct\Helpers\Filesystem;
use PHPUnit_Framework_TestCase as PHPUnit;

class ConfigurationTest extends PHPUnit
{
    public function testExceptionIsRaisedOnNonExistentFile()
    {
        $this->setExpectedException('RuntimeException');
        Configuration::getSettings(
            'non-existent-file.txt',
            'example-project',
            'composer,keywords',
            new Filesystem
        );
    }

    public function testCompleteConfigIsTransformedIntoSettings()
    {
        $settings = Configuration::getSettings(
            __DIR__ . '/stubs/config/complete.stub',
            'example-project',
            'composer,keywords',
            new Filesystem
        );

        $this->assertInstanceOf(
            'JonathanTorres\Construct\Settings',
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
            true
        );

        $this->assertEquals(
            $expectedSettings,
            $settings,
            "Configuration wasn't transformed into expected Settings object."
        );
    }

    public function testDefaultsAreUsedWhenNotConfigured()
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
}
