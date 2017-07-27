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
    private $phpVersions = ['5.4', '5.5', '5.6', '7.0', '7.1'];

    /**
     * Available php files to test on appveyor.
     *
     * @var array
     */
    private $phpAppVeyorVersions = [
        '5.4' => 'php-5.4.45-nts-Win32-VC9-x86.zip',
        '5.5' => 'php-5.5.37-nts-Win32-VC11-x86.zip',
        '5.6' => 'php-5.6.29-nts-Win32-VC11-x86.zip',
        '7.0' => 'php-7.0.17-nts-Win32-VC14-x86.zip',
        '7.1' => 'php-7.1.3-nts-Win32-VC14-x64.zip',
    ];

    /**
     * Php versions without a semver scheme.
     *
     * @var array
     */
    private $nonSemverPhpVersions = ['hhvm', 'nightly'];

    private $cliFramework = 'symfony/console';

    private $testingFramework = 'phpunit';

    private $license = 'MIT';

    private $projectNamespace = 'Vendor\Project';

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

    public function getLicenses()
    {
        return $this->licenses;
    }

    public function getTestingFrameworks()
    {
        return $this->testingFrameworks;
    }

    public function getPhpVersions()
    {
        return $this->phpVersions;
    }

    public function getPhpAppVeyorVersions()
    {
        return $this->phpAppVeyorVersions;
    }

    public function getNonSemverPhpVersions()
    {
        return $this->nonSemverPhpVersions;
    }

    public function getCliFramework()
    {
        return $this->cliFramework;
    }

    public function getTestingFramework()
    {
        return $this->testingFramework;
    }

    public function getLicense()
    {
        return $this->license;
    }

    public function getProjectNamespace()
    {
        return $this->projectNamespace;
    }

    public function getConfigurationFile()
    {
        return $this->configurationFile;
    }

    public function getSystemPhpVersion()
    {
        return $this->systemPhpVersion;
    }
}
