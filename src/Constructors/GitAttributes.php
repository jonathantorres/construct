<?php

declare(strict_types = 1);

namespace Construct\Constructors;

class GitAttributes extends Constructor implements ConstructorContract
{
    /**
     * Generate .gitattributes file.
     *
     * @return void
     */
    public function run()
    {
        $this->gitAttributes->addExportIgnore('.gitattributes');

        $exportIgnores = $this->gitAttributes->getExportIgnores();

        sort($exportIgnores);

        $content = $this->filesystem->get(__DIR__ . '/../stubs/gitattributes.stub');

        foreach ($exportIgnores as $ignore) {
            $content .= "\n" . $ignore . ' export-ignore';
        }

        $content .= "\n";

        $this->filesystem->put($this->settings->getProjectLower() . '/' . '.gitattributes', $content);
    }
}
