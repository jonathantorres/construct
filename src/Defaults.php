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
    public $phpVersions = ['5.4', '5.5', '5.6', '7.0'];

    const TEST_FRAMEWORK = 'phpunit';
    const LICENSE = 'MIT';
    const PROJECT_NAMESPACE = 'Vendor\Project';
}
