<?php

namespace JonathanTorres\Construct\Helpers;

class Testing
{
    /**
     * Initialize behat.
     *
     * @param string $folder
     *
     * @return void
     */
    public function behat($folder)
    {
        $command = 'cd ' . $folder . ' && vendor/bin/behat --init';

        exec($command);
    }

    /**
     * Bootstrap codeception.
     *
     * @param string $folder
     *
     * @return void
     */
    public function codeception($folder)
    {
        $command = 'cd ' . $folder . ' && vendor/bin/codecept bootstrap';

        exec($command);
    }
}
