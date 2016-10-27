<?php

namespace JonathanTorres\Construct\Helpers;

use JonathanTorres\Construct\Defaults;

class Travis
{
    /**
     * Returns the minor version of the given version.
     *
     * @param  string $version
     * @return string
     */
    private function toMinorVersion($version)
    {
        list($major, $minor) = explode('.', $version);

        return $major . '.' . $minor;
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
                $this->toMinorversion($projectPhpVersion),
                $this->toMinorversion($supportedPhpVersion),
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
                $phpVersion = $this->toMinorVersion($phpVersions[$i]);
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
                $runOn .= PHP_EOL;
            }
        }

        return $runOn;
    }
}
