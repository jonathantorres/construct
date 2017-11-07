<?php

declare(strict_types = 1);

namespace Construct\Constructors;

class GitIgnore extends Constructor implements ConstructorContract
{
    /**
     * Generate gitignore file.
     *
     * @return void
     */
    public function run()
    {
        $gitIgnores = $this->gitAttributes->getGitIgnores();

        sort($gitIgnores, SORT_STRING | SORT_FLAG_CASE);

        $content = '';

        foreach ($gitIgnores as $ignore) {
            $content .= $ignore . "\n";
        }

        $this->filesystem->put($this->settings->getProjectLower() . '/' . '.gitignore', $content);
        $this->gitAttributes->addExportIgnore('.gitignore');
    }
}
