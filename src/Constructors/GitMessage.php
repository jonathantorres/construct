<?php

declare(strict_types = 1);

namespace Construct\Constructors;

class GitMessage extends Constructor implements ConstructorContract
{
    /**
     * Copy .gitmessage stub file.
     *
     * @return void
     */
    public function run()
    {
        $this->filesystem->put(
            $this->settings->getProjectLower() . '/' . '.gitmessage',
            $this->filesystem->get(__DIR__ . '/../stubs/gitmessage.stub')
        );

        $this->gitAttributes->addExportIgnore('.gitmessage');
    }
}
