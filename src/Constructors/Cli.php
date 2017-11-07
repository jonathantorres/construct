<?php

declare(strict_types = 1);

namespace Construct\Constructors;

class Cli extends Constructor implements ConstructorContract
{
    /**
     * Add the CLI framework as a Composer requirement and create CLI entry script.
     *
     * @return void
     */
    public function run()
    {
        if ($this->settings->withCliFramework()) {
            $this->filesystem->makeDirectory($this->settings->getProjectLower() . '/bin');
            $this->filesystem->copy(
                __DIR__ . '/../stubs/cli-script.stub',
                $this->settings->getProjectLower() . '/bin/cli-script'
            );

            $appveyorConfiguration = $this->filesystem->get(
                __DIR__ . '/../stubs/appveyor.stub'
            );

            $minorPhpVersion = $this->str->toMinorVersion($this->settings->getPhpVersion());

            $content = str_replace('{php_version}', $minorPhpVersion, $appveyorConfiguration);
            $this->filesystem->put($this->settings->getProjectLower() . '/' . '.appveyor.yml', $content);
            $this->gitAttributes->addExportIgnore('.appveyor.yml');
            $this->composer->addRequirement($this->settings->getCliFramework());
        }
    }
}
