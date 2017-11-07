<?php

declare(strict_types = 1);

namespace Construct\Constructors;

class CodeOfConduct extends Constructor implements ConstructorContract
{
    /**
     * Generate Code of Conduct file.
     *
     * @return void
     */
    public function run()
    {
        if ($this->settings->withCodeOfConduct()) {
            $this->filesystem->copy(
                __DIR__ . '/../stubs/CONDUCT.stub',
                $this->settings->getProjectLower() . '/' . 'CONDUCT.md'
            );

            $this->gitAttributes->addExportIgnore('CONDUCT.md');
        }
    }
}
