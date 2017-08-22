<?php

namespace Construct\Constructors;

class Docs extends Constructor implements ConstructorContract
{
    /**
     * Adds README.md CONTRIBUTING.md and the CHANGELOG.md files.
     *
     * @return void
     */
    public function run()
    {
        $this->readme();
        $this->contributing();
        $this->changelog();
    }

    /**
     * Generate README.md file.
     *
     * @return void
     */
    private function readme()
    {
        if ($this->settings->withCodeOfConduct() === false && $this->settings->withGithubTemplates() === false) {
            $readme = $this->filesystem->get(__DIR__ . '/../stubs/README.stub');
        } elseif ($this->settings->withCodeOfConduct() === false && $this->settings->
            withGithubTemplates() === true) {
            $readme = $this->filesystem->get(__DIR__ . '/../stubs/README.GITHUB.TEMPLATES.stub');
        } elseif ($this->settings->withCodeOfConduct() === true && $this->settings->
            withGithubTemplates() === false) {
            $readme = $this->filesystem->get(__DIR__ . '/../stubs/README.CONDUCT.stub');
        } else {
            $readme = $this->filesystem->get(__DIR__ . '/../stubs/README.CONDUCT.GITHUB.TEMPLATES.stub');
        }

        $stubs = [
            '{project_upper}',
            '{license}',
            '{vendor_lower}',
            '{project_lower}'
        ];

        $values = [
            $this->settings->getProjectUpper(),
            $this->settings->getLicense(),
            $this->settings->getVendorLower(),
            $this->settings->getProjectLower()
        ];

        $content = str_replace($stubs, $values, $readme);

        $this->filesystem->put($this->settings->getProjectLower() . '/' . 'README.md', $content);
        $this->gitAttributes->addExportIgnore('README.md');
    }

    /**
     * Generate CONTRIBUTING.md file.
     *
     * @return void
     */
    private function contributing()
    {
        if ($this->settings->withPhpcsConfiguration()) {
            $contributing = $this->filesystem->get(__DIR__ . '/../stubs/CONTRIBUTING.PHPCS.stub');
        } else {
            $contributing = $this->filesystem->get(__DIR__ . '/../stubs/CONTRIBUTING.stub');
        }

        $placeholder = ['{project_lower}', '{git_message_path}'];
        $replacements = [$this->settings->getProjectLower(), '.gitmessage'];

        if ($this->settings->withGithubTemplates()) {
            $replacements = [$this->settings->getProjectLower(), '../.gitmessage'];
        }

        $content = str_replace($placeholder, $replacements, $contributing);

        $this->filesystem->put($this->settings->getProjectLower() . '/' . 'CONTRIBUTING.md', $content);
        $this->gitAttributes->addExportIgnore('CONTRIBUTING.md');
    }

    /**
     * Generate CHANGELOG.md file.
     *
     * @return void
     */
    protected function changelog()
    {
        $changelog = $this->filesystem->get(__DIR__ . '/../stubs/CHANGELOG.stub');
        $content = str_replace(
            '{creation_date}',
            (new \DateTime())->format('Y-m-d'),
            $changelog
        );

        $this->filesystem->put($this->settings->getProjectLower() . '/' . 'CHANGELOG.md', $content);
        $this->gitAttributes->addExportIgnore('CHANGELOG.md');
    }
}
