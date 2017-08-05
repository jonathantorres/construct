<?php

namespace Construct\Tests;

use Construct\Configuration;
use Construct\Defaults;
use Construct\Settings;
use Construct\Helpers\Filesystem;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    protected $configuration;

    protected function setUp()
    {
        $this->configuration = new Configuration(new Filesystem(new Defaults()));
    }

    public function test_exception_is_raised_on_non_existent_file()
    {
        $this->setExpectedException('RuntimeException');
        $this->configuration->overwriteSettings(new Settings(), 'non-existent-file.txt');
    }

    public function test_complete_config_is_transformed_into_settings()
    {
        $settings = new Settings();
        $settings->setProjectName('example-project');
        $settings->setComposerKeywords('composer,keywords');
        $settings = $this->configuration->overwriteSettings($settings, __DIR__ . '/stubs/config/complete.stub');

        $expectedSettings = new Settings();
        $expectedSettings->setProjectName('example-project');
        $expectedSettings->setTestingFramework('phpspec');
        $expectedSettings->setLicense('MIT');
        $expectedSettings->setNamespace('Namespace');
        $expectedSettings->setGitInit(true);
        $expectedSettings->setPhpcsConfiguration(true);
        $expectedSettings->setComposerKeywords('composer,keywords');
        $expectedSettings->setVagrantfile(true);
        $expectedSettings->setEditorConfig(true);
        $expectedSettings->setPhpVersion('5.4');
        $expectedSettings->setEnvironmentFiles(true);
        $expectedSettings->setLgtmConfiguration(true);
        $expectedSettings->setGithubTemplates(true);
        $expectedSettings->setGithubDocs(true);
        $expectedSettings->setCodeOfConduct(true);

        $this->assertInstanceOf('Construct\Settings', $settings);
        $this->assertEquals(
            $expectedSettings,
            $settings,
            "Configuration wasn't transformed into expected Settings object."
        );
    }

    public function test_github_config_implicates_github_templates_and_docs()
    {
        $settings = new Settings();
        $settings->setProjectName('example-project');
        $settings->setComposerKeywords('composer,keywords');
        $settings = $this->configuration->overwriteSettings($settings, __DIR__ . '/stubs/config/complete.github.stub');

        $expectedSettings = new Settings();
        $expectedSettings->setProjectName('example-project');
        $expectedSettings->setTestingFramework('phpspec');
        $expectedSettings->setLicense('MIT');
        $expectedSettings->setNamespace('Namespace');
        $expectedSettings->setGitInit(true);
        $expectedSettings->setPhpcsConfiguration(true);
        $expectedSettings->setComposerKeywords('composer,keywords');
        $expectedSettings->setVagrantfile(true);
        $expectedSettings->setEditorConfig(true);
        $expectedSettings->setPhpVersion('5.4');
        $expectedSettings->setEnvironmentFiles(true);
        $expectedSettings->setLgtmConfiguration(true);
        $expectedSettings->setGithubTemplates(true);
        $expectedSettings->setGithubDocs(true);
        $expectedSettings->setCodeOfConduct(true);

        $this->assertEquals(
            $expectedSettings,
            $settings,
            "Configuration wasn't transformed into expected Settings object."
        );
    }
}
