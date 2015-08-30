<?php

namespace JonathanTorres\Construct\Helpers;

class Composer
{
    /**
     * Do an initial composer install in constructed project.
     *
     * @param string $folder
     *
     * @return void
     */
    public function install($folder)
    {
        $command = 'cd ' . $folder . ' && composer install';

        exec($command);
    }
}
