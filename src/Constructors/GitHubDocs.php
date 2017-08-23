<?php

namespace Construct\Constructors;

class GitHubDocs extends Constructor implements ConstructorContract
{
    /**
     * Generate GitHub documentation files.
     *
     * @return void
     */
    public function run()
    {
        if ($this->settings->withGithubDocs()) {
            $this->filesystem->makeDirectory(
                $this->settings->getProjectLower() . '/docs',
                true
            );

            $this->filesystem->put(
                $this->settings->getProjectLower() . '/docs/index.md',
                ''
            );

            $this->gitAttributes->addExportIgnore('docs/');
        }
    }
}
