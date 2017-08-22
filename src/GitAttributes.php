<?php

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
    public function getExportIgnores()
    {
        return $this->exportIgnores;
    }

    /**
     * Add a file to the export ignores
     *
     * @param string $ignore
     *
     * @return  void
     */
    public function addExportIgnore($ignore)
    {
        $this->exportIgnores[] = $ignore;
    }

    /**
     * Get the directories and files to ignore on git repositories.
     *
     * @return array
     */
    public function getGitIgnores()
    {
        return $this->gitIgnores;
    }
}
