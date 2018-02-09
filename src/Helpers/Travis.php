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
    private $str;

    /**
     * Initialize Travis helper.
     *
     * @param \Construct\Helpers\Str $str
     */
    public function __construct(Str $str)
    {
        $this->str = $str;
    }

    /**
     * Get project php versions that will be run on travis ci.
     *
     * @param string $projectPhpVersion
     *
     * @return array
     */
    public function phpVersionsToTest($projectPhpVersion): array
    {
        $supportedPhpVersions = (new Defaults)->getPhpVersions();
        $versionsToTest = (new Defaults)->getNonSemverPhpVersions();

        $phpVersionsToTest = array_filter($supportedPhpVersions, function ($supportedPhpVersion) use ($projectPhpVersion) {
            return version_compare(
                $this->str->toMinorversion($projectPhpVersion),
                $this->str->toMinorversion($supportedPhpVersion),
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
    public function phpVersionsToRun($phpVersions, $setLintEnvironmentVariable = false): string
    {
        $runOn = '';
        $nonSemverVersions = (new Defaults)->getNonSemverPhpVersions();
        $alreadySetLintEnvironmentVariable = false;

        for ($i = 0; $i < count($phpVersions); $i++) {
            $phpVersion = $phpVersions[$i];

            if (!in_array($phpVersions[$i], $nonSemverVersions)) {
                $phpVersion = $this->str->toMinorVersion($phpVersions[$i]);
            }

            if ($i >= 0) {
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
