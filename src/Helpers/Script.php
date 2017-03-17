<?php

namespace JonathanTorres\Construct\Helpers;

class Script
{
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
        $folder,
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
     * Generate default behat context.
     *
     * @param string $folder
     *
     * @return void
     */
    public function initBehat($folder)
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
    public function bootstrapCodeception($folder)
    {
        $command = 'cd ' . $folder . ' && vendor/bin/codecept bootstrap';

        exec($command);
    }
}
