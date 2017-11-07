<?php

declare(strict_types = 1);

namespace Construct\Constructors;

class Vagrant extends Constructor implements ConstructorContract
{
    /**
     * Generate Vagrant file.
     *
     * @return void
     */
    public function run()
    {
        if ($this->settings->withVagrantFile()) {
            $this->filesystem->copy(
                __DIR__ . '/../stubs/Vagrantfile.stub',
                $this->settings->getProjectLower() . '/' . 'Vagrantfile'
            );

            $this->gitAttributes->addExportIgnore('Vagrantfile');
        }
    }
}
