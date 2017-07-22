<?php

namespace Construct\Tests;

use Construct\Settings;
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{
    protected $settings;

    protected function setUp()
    {
        $this->settings = new Settings(
            'jonathantorres/logger',
            'phpunit',
            'MIT',
            'JonathanTorres\Logger',
            true,
            true,
            'some, another, keyword',
            false,
            false,
            '5.6.0',
            true,
            false,
            null
        );
    }

    public function test_settings_are_retrieved()
    {
        $this->assertEquals('jonathantorres/logger', $this->settings->getProjectName());
        $this->assertEquals('phpunit', $this->settings->getTestingFramework());
        $this->assertEquals('MIT', $this->settings->getLicense());
        $this->assertEquals('JonathanTorres\Logger', $this->settings->getNamespace());
        $this->assertTrue($this->settings->withGitInit());
        $this->assertTrue($this->settings->withPhpcsConfiguration());
        $this->assertSame('some, another, keyword', $this->settings->getComposerKeywords());
        $this->assertFalse($this->settings->withVagrantfile());
        $this->assertFalse($this->settings->withEditorConfig());
        $this->assertSame('5.6.0', $this->settings->getPhpVersion());
        $this->assertTrue($this->settings->withEnvironmentFiles());
        $this->assertFalse($this->settings->withLgtmConfiguration());
        $this->assertFalse($this->settings->withGithubDocs());
        $this->assertNull($this->settings->getCliFramework());
    }

    public function test_can_set_cli_framework_after_instantiation()
    {
        $this->assertFalse($this->settings->withCliFramework());

        $this->settings->setCliFramework('zendframework/zend-console');

        $this->assertTrue($this->settings->withCliFramework());
        $this->assertEquals('zendframework/zend-console', $this->settings->getCliFramework());
    }
}
