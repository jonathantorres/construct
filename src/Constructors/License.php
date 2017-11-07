<?php

declare(strict_types = 1);

namespace Construct\Constructors;

class License extends Constructor implements ConstructorContract
{
    /**
     * Generate LICENSE.md file.
     *
     * @return void
     */
    public function run()
    {
        $git = $this->container->get('Construct\Helpers\Git');
        $file = $this->filesystem->get(
            __DIR__ . '/../stubs/licenses/' . strtolower($this->settings->getLicense()) . '.stub'
        );

        $user = $git->getUser();

        $content = str_replace(
            ['{year}', '{author_name}'],
            [(new \DateTime())->format('Y'), $user['name']],
            $file
        );

        $this->filesystem->put($this->settings->getProjectLower() . '/' . 'LICENSE.md', $content);
        $this->gitAttributes->addExportIgnore('LICENSE.md');
    }
}
