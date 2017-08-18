<?php

namespace Construct;

use Construct\Helpers\Filesystem;
use Construct\Settings;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

class Configuration
{
    /**
     * The filesystem helper.
     *
     * @var \Construct\Helpers\Filesystem
     */
    private $filesystem;

    /**
     * Initialize configuration.
     *
     * @param \Construct\Helpers\Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Overwrite the passed in settings with the settings set on the configuration file.
     *
     * @param \Construct\Settings $settings
     * @param string              $configurationFile
     *
     * @return \Construct\Settings
     */
    public function overwriteSettings(Settings $settings, $configurationFile)
    {
        if (!$this->filesystem->isFile($configurationFile)) {
            $exceptionMessage = "Configuration file '$configurationFile' is not existent.";
            throw new RuntimeException($exceptionMessage);
        }

        if (!$this->filesystem->isReadable($configurationFile)) {
            $exceptionMessage = "Configuration file '$configurationFile' is not readable.";
            throw new RuntimeException($exceptionMessage);
        }

        $configuration = Yaml::parse($this->filesystem->get($configurationFile));

        // main config settings
        if (isset($configuration['construct-with'])) {
            $configuration['construct-with'] = array_flip($configuration['construct-with']);
        }

        // construct with GitHub files
        if (isset($configuration['construct-with']['github'])) {
            $configuration['construct-with']['github-templates'] = true;
            $configuration['construct-with']['github-docs'] = true;
        }

        // set the testing framework
        if (isset($configuration['test-framework'])) {
            $settings->setTestingFramework($configuration['test-framework']);
        }

        // set the open source license
        if (isset($configuration['license'])) {
            $settings->setLicense($configuration['license']);
        }

        // set the namespace
        if (isset($configuration['namespace'])) {
            $settings->setNamespace($configuration['namespace']);
        }

        // initialize an empty git repo?
        if (isset($configuration['construct-with']['git'])) {
            $settings->setGitInit(true);
        }

        // construct with a .phpcs configuration
        if (isset($configuration['construct-with']['phpcs'])) {
            $settings->setPhpcsConfiguration(true);
        }

        // construct with a Vagrantfile
        if (isset($configuration['construct-with']['vagrant'])) {
            $settings->setVagrantfile(true);
        }

        // construct with an .editorconfig file
        if (isset($configuration['construct-with']['editor-config'])) {
            $settings->setEditorConfig(true);
        }

        // set the php version
        if (isset($configuration['php'])) {
            $settings->setPhpVersion((string) $configuration['php']);
        }

        // construct with an environment file
        if (isset($configuration['construct-with']['env'])) {
            $settings->setEnvironmentFiles(true);
        }

        // construct with an lgtm configuration
        if (isset($configuration['construct-with']['lgtm'])) {
            $settings->setLgtmConfiguration(true);
        }

        // construct with GitHub template files
        if (isset($configuration['construct-with']['github-templates'])) {
            $settings->setGithubTemplates(true);
        }

        // construct with a code of conduct file
        if (isset($configuration['construct-with']['code-of-conduct'])) {
            $settings->setCodeOfConduct(true);
        }

        // construct with a GitHub docs file
        if (isset($configuration['construct-with']['github-docs'])) {
            $settings->setGithubDocs(true);
        }

        return $settings;
    }

    /**
     * Determine if a configuration is applicable.
     *
     * @param  string  The default or a command line provided configuration file.
     *
     * @return boolean
     */
    public function isApplicable($configuration)
    {
        if ($configuration === $this->filesystem->getDefaultConfigurationFile()
            && $this->filesystem->hasDefaultConfigurationFile()) {
            return true;
        }

        if ($configuration !== $this->filesystem->getDefaultConfigurationFile()) {
            return true;
        }

        return false;
    }
}
