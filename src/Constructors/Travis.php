<?php

namespace Construct\Constructors;

use Construct\Helpers\Travis as TravisHelper;

class Travis extends Constructor implements ConstructorContract
{
    /**
     * Generate .travis.yml file.
     *
     * @return void
     */
    public function run()
    {
        $travisHelper = new TravisHelper($this->str);

        if ($this->settings->withPhpcsConfiguration()) {
            $file = $this->filesystem->get(__DIR__ . '/../stubs/travis.phpcs.stub');
            $phpVersionsToRunOnTravis = $travisHelper->phpVersionsToRun(
                $travisHelper->phpVersionsToTest($this->settings->getPhpVersion()),
                true
            );
        } else {
            $file = $this->filesystem->get(__DIR__ . '/../stubs/travis.stub');
            $phpVersionsToRunOnTravis = $travisHelper->phpVersionsToRun(
                $travisHelper->phpVersionsToTest($this->settings->getPhpVersion())
            );
        }

        $content = str_replace('{phpVersions}', $phpVersionsToRunOnTravis, $file);

        $this->filesystem->put($this->settings->getProjectLower() . '/' . '.travis.yml', $content);
        $this->gitAttributes->addExportIgnore('.travis.yml');
    }
}
