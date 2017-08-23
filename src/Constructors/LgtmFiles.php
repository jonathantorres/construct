<?php

namespace Construct\Constructors;

class LgtmFiles extends Constructor implements ConstructorContract
{
    /**
     * Generate LGTM configuration files.
     *
     * @return void
     */
    public function run()
    {
        if ($this->settings->withLgtmConfiguration()) {
            $this->filesystem->copy(
                __DIR__ . '/../stubs/MAINTAINERS.stub',
                $this->settings->getProjectLower() . '/' . 'MAINTAINERS'
            );

            $this->filesystem->copy(
                __DIR__ . '/../stubs/lgtm.stub',
                $this->getProjectLower() . '/' . '.lgtm'
            );

            $this->gitAttributes->addExportIgnore('MAINTAINERS');
            $this->gitAttributes->addExportIgnore('.lgtm');
        }
    }
}
