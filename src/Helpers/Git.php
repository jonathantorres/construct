<?php

namespace Construct\Helpers;

class Git
{
    /**
     * Initialize an empty git repo.
     *
     * @param string $folder
     *
     * @return void
     */
    public function init($folder)
    {
        $command = 'cd ' . $folder . ' && git init';

        exec($command);
    }

    /**
     * Tries to determine the configured git user, returns a default when failing to do so.
     *
     * @return array
     */
    public function getUser()
    {
        $user = [
            'name' => 'Some name',
            'email' => 'some@email.com'
        ];

        $command = 'git config --get-regexp "^user.*"';
        exec($command, $keyValueLines, $returnValue);

        if ($returnValue === 0) {
            foreach ($keyValueLines as $keyValueLine) {
                list($key, $value) = explode(' ', $keyValueLine, 2);
                $key = str_replace('user.', '', $key);
                $user[$key] = $value;
            }
        }

        return $user;
    }
}
