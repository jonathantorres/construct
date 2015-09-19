<?php

namespace JonathanTorres\Construct\Helpers;

class Script
{
    /**
     * Do an initial composer install in constructed project.
     *
     * @param string $folder
     *
     * @return void
     */
    public function runComposerInstall($folder)
    {
        $command = 'cd ' . $folder . ' && composer install';

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
