<?php

namespace Construct\Constructors;

class EditorConfig extends Constructor implements ConstructorContract
{
    /**
     * Generate EditorConfig configuration file.
     *
     * @return void
     */
    public function run()
    {
        if ($this->settings->withEditorConfig()) {
            $this->filesystem->copy(
                __DIR__ . '/../stubs/editorconfig.stub',
                $this->settings->getProjectLower() . '/' . '.editorconfig'
            );

            $this->gitAttributes->addExportIgnore('.editorconfig');
        }
    }
}
