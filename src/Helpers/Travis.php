<?php

namespace Construct\Helpers;

use Construct\Defaults;

class Travis
{
    /**
     * String helper.
     *
     * @var \Construct\Helpers\Str
     */
    private $stringHelper;

    public function __construct()
    {
        $this->stringHelper = new Str();
    }

    /**
     * Get project php versions that will be run on travis ci.
     *
     * @param string $projectPhpVersion
     *
     * @return array
     */
    public function phpVersionsToTest($projectPhpVersion)
    {
        $supportedPhpVersions = (new Defaults)->phpVersions;
        $versionsToTest = (new Defaults)->nonSemverPhpVersions;

        $phpVersionsToTest = array_filter($supportedPhpVersions, function ($supportedPhpVersion) use ($projectPhpVersion) {
            return version_compare(
                $this->stringHelper->toMinorversion($projectPhpVersion),
                $this->stringHelper->toMinorversion($supportedPhpVersion),
                '<='
            ) === true;
        });

        return array_merge($versionsToTest, $phpVersionsToTest);
    }

    /**
     * Generate string that specifies the php versions that will be run on travis.
     *
     * @param array   $phpVersions
     * @param boolean $setLintEnvironmentVariable
     *
     * @return string
     */
    public function phpVersionsToRun($phpVersions, $setLintEnvironmentVariable = false)
    {
        $runOn = '';
        $nonSemverVersions = (new Defaults)->nonSemverPhpVersions;
        $alreadySetLintEnvironmentVariable = false;

        for ($i = 0; $i < count($phpVersions); $i++) {
            $phpVersion = $phpVersions[$i];
            if (!in_array($phpVersions[$i], $nonSemverVersions)) {
                $phpVersion = $this->stringHelper->toMinorVersion($phpVersions[$i]);
            }

            if (count($i) !== 0) {
                $runOn .= '    ';
            }
            $runOn .= '- php: ' . $phpVersions[$i];

            if ($setLintEnvironmentVariable
                && count($phpVersions) == $i + 1
                && $alreadySetLintEnvironmentVariable == false
            ) {
                $alreadySetLintEnvironmentVariable = true;
                $runOn .= "\n      env:"
                    . "\n      - LINT=true";
            }

            if ($i !== (count($phpVersions) - 1)) {
                $runOn .= "\n";
            }
        }

        return $runOn;
    }
}
