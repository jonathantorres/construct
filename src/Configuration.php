<?php

namespace Construct;

use Symfony\Component\Yaml\Yaml;

class Configuration
{
    /**
     * Get settings derived from the configuration file.
     *
     * @param string                                       $configurationFile Path to the configuration file.
     * @param string                                       $projectName       Name of the project.
     * @param string                                       $keywords          Composer keywords.
     * @param \Construct\Helpers\Filesystem $filesystemHelper
     *
     * @return \Construct\Settings
     */
    public static function getSettings($configurationFile, $projectName, $keywords, $filesystemHelper)
    {
        if (!$filesystemHelper->isFile($configurationFile)) {
            $exceptionMessage = "Configuration file '$configurationFile' is not existent.";
            throw new \RuntimeException($exceptionMessage);
        }

        if (!$filesystemHelper->isReadable($configurationFile)) {
            $exceptionMessage = "Configuration file '$configurationFile' is not readable.";
            throw new \RuntimeException($exceptionMessage);
        }

        $configuration = Yaml::parse($filesystemHelper->get($configurationFile));
        $defaults = new Defaults();

        if (isset($configuration['construct-with'])) {
            $configuration['construct-with'] = array_flip($configuration['construct-with']);
        }

        if (isset($configuration['construct-with']['github'])) {
            $configuration['construct-with']['github-templates'] = true;
            $configuration['construct-with']['github-docs'] = true;
        }

        return new Settings(
            $projectName,
            isset($configuration['test-framework']) ? $configuration['test-framework'] : $defaults->testingFrameworks[0],
            isset($configuration['license']) ? $configuration['license'] : $defaults->licenses[0],
            isset($configuration['namespace']) ? $configuration['namespace'] : null,
            isset($configuration['construct-with']['git']) ? true : false,
            isset($configuration['construct-with']['phpcs']) ? true : false,
            $keywords,
            isset($configuration['construct-with']['vagrant']) ? true : false,
            isset($configuration['construct-with']['editor-config']) ? true : false,
            isset($configuration['php']) ? (string) $configuration['php'] : PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION,
            isset($configuration['construct-with']['env']) ? true : false,
            isset($configuration['construct-with']['lgtm']) ? true : false,
            isset($configuration['construct-with']['github-templates']) ? true : false,
            isset($configuration['construct-with']['code-of-conduct']) ? true : false,
            isset($configuration['construct-with']['github-docs']) ? true : false
        );
    }
}
