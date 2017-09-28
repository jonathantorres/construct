<?php

namespace Construct\Constructors;

class GitHubTemplates extends Constructor implements ConstructorContract
{
    /**
     * Generate GitHub template files.
     *
     * @return void
     */
    public function run()
    {
        if ($this->settings->withGithubTemplates()) {
            $this->filesystem->makeDirectory(
                $this->settings->getProjectLower() . '/.github',
                true
            );

            $templates = ['ISSUE_TEMPLATE', 'PULL_REQUEST_TEMPLATE'];

            $stubs = [
                '{license}',
            ];

            $values = [
                $this->settings->getLicense(),
            ];

            foreach ($templates as $template) {
                $templateContent = $this->filesystem->get(__DIR__ . '/../stubs/github/' . $template . '.stub');
                $content = str_replace($stubs, $values, $templateContent);

                $this->filesystem->put(
                    $this->settings->getProjectLower() . '/.github/' . $template . '.md',
                    $content
                );
            }

            $this->filesystem->move(
                $this->settings->getProjectLower() . '/CONTRIBUTING.md',
                $this->settings->getProjectLower() . '/.github/CONTRIBUTING.md'
            );

            $index = array_search('CONTRIBUTING.md', $this->gitAttributes->getExportIgnores());

            $this->gitAttributes->removeExportIgnore($index);
            $this->gitAttributes->addExportIgnore('.github/');
        }
    }
}
