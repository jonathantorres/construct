<?php

declare(strict_types = 1);

namespace Construct\Constructors;

class ProjectClass extends Constructor implements ConstructorContract
{
    /**
     * Generate project class file.
     *
     * @return void
     */
    public function run()
    {
        $file = $this->filesystem->get(__DIR__ . '/../stubs/Project.stub');

        $stubs = [
            '{project_upper}',
            '{vendor_upper}',
            '{namespace}',
        ];

        $values = [
            $this->settings->getProjectUpper(),
            $this->settings->getVendorUpper(),
            $this->createNamespace()
        ];

        $content = str_replace($stubs, $values, $file);

        $this->filesystem->put($this->settings->getProjectLower() . '/src/' . $this->settings->getProjectUpper() . '.php', $content);
    }
}
