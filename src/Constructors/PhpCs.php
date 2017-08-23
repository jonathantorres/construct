<?php

namespace Construct\Constructors;

class PhpCs extends Constructor implements ConstructorContract
{
    /**
     * Generate PHP CS Fixer configuration file and add package
     * to the development requirements.
     *
     * @return void
     */
    public function run()
    {
        if ($this->settings->withPhpcsConfiguration()) {
            $this->composer->addDevelopmentRequirement('friendsofphp/php-cs-fixer');

            $this->filesystem->copy(
                __DIR__ . '/../stubs/phpcs.stub',
                $this->settings->getProjectLower() . '/' . '.php_cs'
            );

            $this->gitAttributes->addGitIgnore('.php_cs.cache');
            $this->gitAttributes->addExportIgnore('.php_cs');
        }
    }
}
