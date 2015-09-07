<?php

namespace JonathanTorres\Construct\Helpers;

class Filesystem
{
    /**
     * Create a directory
     *
     * @param string $path
     *
     * @return boolean
     */
    public function makeDirectory($path)
    {
        return mkdir($path);
    }

    /**
     * Check if the path is a directory.
     *
     * @param string $path
     *
     * @return boolean
     */
    public function isDirectory($path)
    {
        return is_dir($path);
    }

    /**
     * Copy the given file a new location.
     *
     * @param string $path
     * @param string $target
     *
     * @return boolean
     */
    public function copy($path, $target)
    {
        return copy($path, $target);
    }

    /**
     * Get the contents of a file.
     *
     * @param string $path
     *
     * @return string
     */
    public function get($path)
    {
        return file_get_contents($path);
    }

    /**
     * Write the contents of a file.
     *
     * @param string $path
     * @param string $contents
     *
     * @return int
     */
    public function put($path, $contents)
    {
        return file_put_contents($path, $contents);
    }
}
