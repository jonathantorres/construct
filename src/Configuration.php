<?php

namespace Construct;

use Construct\Helpers\Filesystem;
use Construct\Settings;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

class Configuration
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

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

        if (isset($configuration['construct-with']['github'])) {
            $configuration['construct-with']['github-templates'] = true;
            $configuration['construct-with']['github-docs'] = true;
        }

        if (isset($configuration['test-framework'])) {
            $settings->setTestingFramework($configuration['test-framework']);
        }

        if (isset($configuration['license'])) {
            $settings->setLicense($configuration['license']);
        }

        if (isset($configuration['namespace'])) {
            $settings->setNamespace($configuration['namespace']);
        }

        if (isset($configuration['construct-with']['git'])) {
            $settings->setGitInit(true);
        }

        if (isset($configuration['construct-with']['phpcs'])) {
            $settings->setPhpcsConfiguration(true);
        }

        if (isset($configuration['construct-with']['vagrant'])) {
            $settings->setVagrantfile(true);
        }

        if (isset($configuration['construct-with']['editor-config'])) {
            $settings->setEditorConfig(true);
        }

        if (isset($configuration['php'])) {
            $settings->setPhpVersion((string) $configuration['php']);
        }

        if (isset($configuration['construct-with']['env'])) {
            $settings->setEnvironmentFiles(true);
        }

        if (isset($configuration['construct-with']['lgtm'])) {
            $settings->setLgtmConfiguration(true);
        }

        if (isset($configuration['construct-with']['github-templates'])) {
            $settings->setGithubTemplates(true);
        }

        if (isset($configuration['construct-with']['code-of-conduct'])) {
            $settings->setCodeOfConduct(true);
        }

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

    /**
     * Get settings derived from the configuration file.
     *
     * @param string                        $configurationFile Path to the configuration file.
     * @param string                        $projectName       Name of the project.
     * @param string                        $keywords          Composer keywords.
     * @param \Construct\Helpers\Filesystem $filesystemHelper
     *
     * @return \Construct\Settings
     */
    // public function getSettings($configurationFile, $projectName, $keywords, $filesystemHelper)
    // {
    //     if (!$filesystemHelper->isFile($configurationFile)) {
    //         $exceptionMessage = "Configuration file '$configurationFile' is not existent.";
    //         throw new RuntimeException($exceptionMessage);
    //     }

    //     if (!$filesystemHelper->isReadable($configurationFile)) {
    //         $exceptionMessage = "Configuration file '$configurationFile' is not readable.";
    //         throw new RuntimeException($exceptionMessage);
    //     }

    //     $configuration = Yaml::parse($filesystemHelper->get($configurationFile));
    //     $defaults = new Defaults();

    //     if (isset($configuration['construct-with'])) {
    //         $configuration['construct-with'] = array_flip($configuration['construct-with']);
    //     }

    //     if (isset($configuration['construct-with']['github'])) {
    //         $configuration['construct-with']['github-templates'] = true;
    //         $configuration['construct-with']['github-docs'] = true;
    //     }

    //     return new Settings(
    //         $projectName,
    //         isset($configuration['test-framework']) ? $configuration['test-framework'] : $defaults->testingFrameworks[0],
    //         isset($configuration['license']) ? $configuration['license'] : $defaults->licenses[0],
    //         isset($configuration['namespace']) ? $configuration['namespace'] : null,
    //         isset($configuration['construct-with']['git']) ? true : false,
    //         isset($configuration['construct-with']['phpcs']) ? true : false,
    //         $keywords,
    //         isset($configuration['construct-with']['vagrant']) ? true : false,
    //         isset($configuration['construct-with']['editor-config']) ? true : false,
    //         isset($configuration['php']) ? (string) $configuration['php'] : PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION,
    //         isset($configuration['construct-with']['env']) ? true : false,
    //         isset($configuration['construct-with']['lgtm']) ? true : false,
    //         isset($configuration['construct-with']['github-templates']) ? true : false,
    //         isset($configuration['construct-with']['code-of-conduct']) ? true : false,
    //         isset($configuration['construct-with']['github-docs']) ? true : false
    //     );
    // }
}
