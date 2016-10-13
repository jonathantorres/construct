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
     * @param array $phpVersions
     *
     * @return string
     */
    public function phpVersionsToRun($phpVersions)
    {
        $versionsWithXdebugExtension = (new Defaults)->phpVersionsWithXdebugExtension;
        $runOn = '';
        $nonSemverVersions = (new Defaults)->nonSemverPhpVersions;

        for ($i = 0; $i < count($phpVersions); $i++) {
            $phpVersion = $phpVersions[$i];
            if (!in_array($phpVersions[$i], $nonSemverVersions)) {
                $phpVersion = $this->toMinorVersion($phpVersions[$i]);
            }

            if (count($i) !== 0) {
                $runOn .= '    ';
            }
            $runOn .= '- php: ' . $phpVersions[$i];
            if (in_array($phpVersion, $versionsWithXdebugExtension)) {
                $runOn .= "\n      env:"
                    . "\n      - DISABLE_XDEBUG=true";
            }

            if ($i !== (count($phpVersions) - 1)) {
                $runOn .= PHP_EOL;
            }
        }

        return $runOn;
    }
}
