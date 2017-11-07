<?php

declare(strict_types = 1);

namespace Construct\Constructors;

class Tests extends Constructor implements ConstructorContract
{
    /**
     * Generate files for the selected testing framework.
     *
     * @return void
     */
    public function run()
    {
        $testingFramework = $this->settings->getTestingFramework();

        $this->{$testingFramework}();
    }

    /**
     * Generate phpunit test/file/settings and add package
     * to the development requirements.
     *
     * @return void
     */
    private function phpunit()
    {
        $this->phpunitTest();
        $this->composer->addDevelopmentRequirement('phpunit/phpunit');

        $file = $this->filesystem->get(__DIR__ . '/../stubs/phpunit.stub');
        $content = str_replace('{project_upper}', $this->settings->getProjectUpper(), $file);

        $this->filesystem->put($this->settings->getProjectLower() . '/' . 'phpunit.xml.dist', $content);
        $this->gitAttributes->addExportIgnore('phpunit.xml.dist');
        $this->gitAttributes->addGitIgnore('phpunit.xml');
    }

    /**
     * Generate phpunit test file.
     *
     * @return void
     */
    private function phpunitTest()
    {
        $file = $this->filesystem->get(__DIR__ . '/../stubs/ProjectTest.stub');

        $stubs = [
            '{project_upper}',
            '{project_camel_case}',
            '{vendor_upper}',
            '{namespace}',
        ];

        $values = [
            $this->settings->getProjectUpper(),
            $this->str->toCamelCase($this->settings->getProjectLower()),
            $this->settings->getVendorUpper(),
            $this->createNamespace(),
        ];

        $content = str_replace($stubs, $values, $file);

        $this->filesystem->makeDirectory($this->settings->getProjectLower() . '/tests');
        $this->filesystem->put($this->settings->getProjectLower() . '/tests/' . $this->settings->getProjectUpper() . 'Test.php', $content);
        $this->gitAttributes->addExportIgnore('tests/');
    }

    /**
     * Generate phpspec config file, create a specs directory and
     * add package to development requirements.
     *
     * @return void
     */
    private function phpspec()
    {
        $this->composer->addDevelopmentRequirement('phpspec/phpspec');

        $file = $this->filesystem->get(__DIR__ . '/../stubs/phpspec.stub');
        $content = str_replace('{namespace}', $this->createNamespace(), $file);

        $this->filesystem->makeDirectory($this->settings->getProjectLower() . '/specs');
        $this->gitAttributes->addExportIgnore('specs/');

        $this->filesystem->put($this->settings->getProjectLower() . '/' . 'phpspec.yml.dist', $content);
        $this->gitAttributes->addExportIgnore('phpspec.yml.dist');
        $this->gitAttributes->addGitIgnore('phpspec.yml');
    }

    /**
     * Add behat to development requirements.
     *
     * @return void
     */
    private function behat()
    {
        $this->composer->addDevelopmentRequirement('behat/behat');
    }

    /**
     * Add codeception to development requirements.
     *
     * @return void
     */
    private function codeception()
    {
        $this->composer->addDevelopmentRequirement('codeception/codeception');
    }
}
