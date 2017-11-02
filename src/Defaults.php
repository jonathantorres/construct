<?php

namespace Construct;

class Defaults
{
    /**
     * The available open source licenses. (more: http://choosealicense.com/licenses)
     *
     * @var array
     */
    private $licenses = ['MIT', 'Apache-2.0', 'GPL-2.0', 'GPL-3.0'];

    /**
     * The available testing frameworks.
     *
     * @var array
     */
    private $testingFrameworks = ['phpunit', 'behat', 'phpspec', 'codeception'];

    /**
     * Available php versions to test on travis.
     *
     * @var array
     */
    private $phpVersions = ['5.6', '7.0', '7.1', '7.2'];

    /**
     * Php versions without a semver scheme.
     *
     * @var array
     */
    private $nonSemverPhpVersions = ['hhvm', 'nightly'];

    /**
     * The default CLI framework.
     *
     * @var string
     */
    private $cliFramework = 'symfony/console';

    /**
     * The default testing framework.
     *
     * @var string
     */
    private $testingFramework = 'phpunit';

    /**
     * The default license.
     *
     * @var string
     */
    private $license = 'MIT';

    /**
     * The default project namespace.
     *
     * @var string
     */
    private $projectNamespace = 'Vendor\Project';

    /**
     * The default configuration file.
     *
     * @var string
     */
    private $configurationFile = '.construct';

    /**
     * Php version currently used on the system.
     *
     * @var string
     */
    private $systemPhpVersion = '';

    /**
     * Initialize Default values.
     *
     * @return void
     */
    public function __construct()
    {
        $this->systemPhpVersion = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
    }

    /**
     * Get the available open source licenses.
     *
     * @return string
     */
    public function getLicenses()
    {
        return $this->licenses;
    }

    /**
     * Get the supported testing frameworks.
     *
     * @return array
     */
    public function getTestingFrameworks()
    {
        return $this->testingFrameworks;
    }

    /**
     * Get the available php versions to test on Travis.
     *
     * @return array
     */
    public function getPhpVersions()
    {
        return $this->phpVersions;
    }

    /**
     * Get the php versions without a semver scheme.
     *
     * @return array
     */
    public function getNonSemverPhpVersions()
    {
        return $this->nonSemverPhpVersions;
    }

    /**
     * Get the default CLI Framework.
     *
     * @return string
     */
    public function getCliFramework()
    {
        return $this->cliFramework;
    }

    /**
     * Get the default testing framework.
     *
     * @return string
     */
    public function getTestingFramework()
    {
        return $this->testingFramework;
    }

    /**
     * Get the default license.
     *
     * @return string
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * Get the default project namespace.
     *
     * @return string
     */
    public function getProjectNamespace()
    {
        return $this->projectNamespace;
    }

    /**
     * Get the default name of the configuration file.
     *
     * @return string
     */
    public function getConfigurationFile()
    {
        return $this->configurationFile;
    }

    /**
     * Get the php version currently installed on the system.
     *
     * @return string
     */
    public function getSystemPhpVersion()
    {
        return $this->systemPhpVersion;
    }
}
