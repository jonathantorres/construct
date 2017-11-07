<?php

declare(strict_types = 1);

namespace Construct;

class GitAttributes
{
    /**
     * The files to ignore on exporting.
     *
     * @var array
     */
    private $exportIgnores = [];

    /**
     * The directories and files to ignore in Git repositories.
     *
     * @var array
     */
    private $gitIgnores = ['/vendor', 'composer.lock'];

    /**
     * Get the export ignores
     *
     * @return array
     */
    public function getExportIgnores(): array
    {
        return $this->exportIgnores;
    }

    /**
     * Add a file to the export ignores
     *
     * @param string $ignore
     *
     * @return void
     */
    public function addExportIgnore(string $ignore)
    {
        $this->exportIgnores[] = $ignore;
    }

    /**
     * Remove a file from the export ignores
     *
     * @param string $ignore
     *
     * @return void
     */
    public function removeExportIgnore($ignore)
    {
        unset($this->exportIgnores[$ignore]);
    }

    /**
     * Get the directories and files to ignore on git repositories.
     *
     * @return array
     */
    public function getGitIgnores(): array
    {
        return $this->gitIgnores;
    }

    /**
     * Add a file to the .gitignore
     *
     * @param string $ignore
     *
     * @return void
     */
    public function addGitIgnore(string $ignore)
    {
        $this->gitIgnores[] = $ignore;
    }
}
