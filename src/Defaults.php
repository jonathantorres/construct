<?php

namespace JonathanTorres\Construct;

class Defaults
{
    /**
     * The available open source licenses. (more: http://choosealicense.com/licenses)
     *
     * @var array
     */
    public $licenses = ['MIT', 'Apache-2.0', 'GPL-2.0', 'GPL-3.0'];

    /**
     * The available testing frameworks.
     *
     * @var array
     */
    public $testingFrameworks = ['phpunit', 'behat', 'phpspec', 'codeception'];

    /**
     * Available php versions to test on travis.
     *
     * @var array
     */
    public $phpVersions = ['5.4', '5.5', '5.6', '7.0', '7.1'];

    /**
     * Available php files to test on appveyor.
     *
     * @var array
     */
    public $phpAppVeyorVersions = [
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
    public $nonSemverPhpVersions = ['hhvm', 'nightly'];

    const CLI_FRAMEWORK = 'symfony/console';
    const TEST_FRAMEWORK = 'phpunit';
    const LICENSE = 'MIT';
    const PROJECT_NAMESPACE = 'Vendor\Project';
    const CONFIGURATION_FILE = '.construct';
}
