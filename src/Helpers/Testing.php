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
}
