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
        $versionsToTest = ['hhvm', 'nightly'];

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
        $runOn = '';

        for ($i = 0; $i < count($phpVersions); $i++) {
            $runOn .= '  - ' . $phpVersions[$i];

            if ($i !== (count($phpVersions) - 1)) {
                $runOn .= PHP_EOL;
            }
        }

        return $runOn;
    }
}
