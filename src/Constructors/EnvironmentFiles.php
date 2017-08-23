<?php

namespace Construct\Constructors;

class EnvironmentFiles extends Constructor implements ConstructorContract
{
    /**
     * Generate .env environment files and add package
     * to the development requirements.
     *
     * @return void
     */
    public function run()
    {
        if ($this->settings->withEnvironmentFiles()) {
            $this->composer->addRequirement('vlucas/phpdotenv');

            $this->filesystem->copy(
                __DIR__ . '/../stubs/env.stub',
                $this->settings->getProjectLower() . '/' . '.env'
            );

            $this->filesystem->copy(
                __DIR__ . '/../stubs/env.stub',
                $this->settings->getProjectLower() . '/' . '.env.example'
            );

            $this->gitAttributes->addExportIgnore('.env');
            $this->gitAttributes->addGitIgnore('.env');
        }
    }
}
