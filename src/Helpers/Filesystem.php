<?php

declare(strict_types = 1);

namespace Construct\Helpers;

use Construct\Defaults;

class Filesystem
{
    /**
     * The project's default settings.
     *
     * @var \Construct\Defaults
     */
    private $defaults;

    /**
     * Initialize filesystem helper.
     *
     * @param \Construct\Defaults $defaults
     */
    public function __construct(Defaults $defaults)
    {
        $this->defaults = $defaults;
    }

    /**
     * Create a directory
     *
     * @param string  $path
     * @param boolean $recursive Defaults to false.
     *
     * @return boolean
     */
    public function makeDirectory(string $path, bool $recursive = false)
    {
        return mkdir($path, 0777, $recursive);
    }

    /**
     * Check if the path is a directory.
     *
     * @param string $path
     *
     * @return boolean
     */
    public function isDirectory(string $path)
    {
        return is_dir($path);
    }

    /**
     * Check if the path is a file.
     *
     * @param string $path
     *
     * @return boolean
     */
    public function isFile(string $path): bool
    {
        return is_file($path);
    }

    /**
     * Check if the path is readable.
     *
     * @param string $path
     *
     * @return boolean
     */
    public function isReadable(string $path): bool
    {
        return is_readable($path);
    }

    /**
     * Get the home directory.
     *
     * @param string $os
     *
     * @return string
     */
    public function getHomeDirectory(string $os = PHP_OS): string
    {
        if (strtoupper(substr($os, 0, 3)) !== 'WIN') {
            return getenv('HOME');
        }

        return getenv('userprofile');
    }

    /**
     * Get the default construct configuration file.
     *
     * @return string
     */
    public function getDefaultConfigurationFile(): string
    {
        return $this->getHomeDirectory()
            . DIRECTORY_SEPARATOR
            . $this->defaults->getConfigurationFile();
    }

    /**
     * Determine if system has a default configuration file.
     *
     * @return boolean
     */
    public function hasDefaultConfigurationFile(): bool
    {
        return $this->isFile($this->getDefaultConfigurationFile());
    }

    /**
     * Copy the given file a new location.
     *
     * @param string $path
     * @param string $target
     *
     * @return boolean
     */
    public function copy(string $path, string $target)
    {
        return copy($path, $target);
    }

    /**
     * Move the given file to a new location.
     *
     * @param string $path
     * @param string $target
     *
     * @return boolean
     */
    public function move(string $path, string $target)
    {
        $this->copy($path, $target);

        unlink($path);
    }

    /**
     * Get the contents of a file.
     *
     * @param string $path
     *
     * @return string
     */
    public function get(string $path)
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
    public function put(string $path, $contents)
    {
        return file_put_contents($path, $contents);
    }
}
