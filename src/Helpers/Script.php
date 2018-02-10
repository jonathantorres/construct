<?php

declare(strict_types = 1);

namespace Construct\Helpers;

class Script
{
    /**
     * String helper.
     *
     * @var \Construct\Helpers\Str
     */
    private $str;

    /**
     * Initialize Script helper.
     *
     * @param \Construct\Helpers\Str $str
     */
    public function __construct(Str $str)
    {
        $this->str = $str;
    }

    /**
     * Do an initial composer install in constructed project and require
     * the development and non development packages.
     *
     * @param string $folder              The folder to execute the command(s) in.
     * @param array  $developmentPackages The development packages to require.
     * @param array  $packages            The packages to require.
     *
     * @return void
     */
    public function runComposerInstallAndRequirePackages(
        string $folder,
        array $developmentPackages,
        array $packages = []
    ) {
        $command = 'cd ' . $folder . ' && composer install';

        if (count($developmentPackages) > 0) {
            $command .= ' && composer require --dev ' . implode(' ', $developmentPackages);
        }

        if (count($packages) > 0) {
            $command .= ' && composer require ' . implode(' ', $packages);
        }

        exec($command);
    }

    /**
     * Checks if a given Composer version or greater is
     * available on the runtime system.
     *
     * @param  string  $version Defaults to version 1.6.0.
     * @return boolean
     */
    public function isComposerVersionAvailable($version = '1.6.0')
    {
        $requiredMinorVersion = $this->str->toMinorVersion($version);
        $command = 'composer --version';

        exec($command, $version, $returnValue);

        if ($returnValue === 0) {
            $availableMinorVersion = $this->str->toMinorVersion(
                explode(' ', $version[0])[2]
            );

            return version_compare(
                $requiredMinorVersion,
                $availableMinorVersion,
                '<='
            ) === true;
        }

        return true;
    }

    /**
     * Generate default behat context.
     *
     * @param string $folder
     *
     * @return void
     */
    public function initBehat(string $folder)
    {
        $command = 'cd ' . $folder . ' && vendor/bin/behat --init';

        exec($command);
    }

    /**
     * Generate default codeception suites.
     *
     * @param string $folder
     *
     * @return void
     */
    public function bootstrapCodeception(string $folder)
    {
        $command = 'cd ' . $folder . ' && vendor/bin/codecept bootstrap';

        exec($command);
    }
}
