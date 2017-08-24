<?php

namespace Construct\Constructors;

class Composer extends Constructor implements ConstructorContract
{
    /**
     * Generate composer.json file.
     *
     * @return void
     */
    public function run()
    {
        $composerFile = 'composer.' . $this->settings->getTestingFramework();
        $file = $this->filesystem->get(__DIR__ . '/../stubs/composer/' . $composerFile . '.stub');
        $git = $this->container->get('Construct\Helpers\Git');
        $user = $git->getUser();

        $stubs = [
            '{project_upper}',
            '{project_lower}',
            '{vendor_lower}',
            '{vendor_upper}',
            '{testing}',
            '{namespace}',
            '{license}',
            '{author_name}',
            '{author_email}',
            '{keywords}',
            '{php_version}',
        ];

        $values = [
            $this->settings->getProjectUpper(),
            $this->settings->getProjectLower(),
            $this->settings->getVendorLower(),
            $this->settings->getVendorUpper(),
            $this->settings->getTestingFramework(),
            $this->createNamespace(true),
            $this->settings->getLicense(),
            $user['name'],
            $user['email'],
            $this->str->toQuotedKeywords($this->settings->getComposerKeywords()),
            $this->settings->getPhpVersion(),
        ];

        $content = str_replace($stubs, $values, $file);

        if ($this->settings->withPhpcsConfiguration()) {
            $composer = json_decode($content, true);
            $composer['scripts']['cs-fix'] = 'php-cs-fixer fix . -vv || true';
            $composer['scripts']['cs-lint'] = 'php-cs-fixer fix --diff --stop-on-violation --verbose --dry-run';
            $content = json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $content .= "\n";
        }

        if ($this->settings->withCliFramework()) {
            $composer = json_decode($content, true);
            $composer['bin'] = ["bin/cli-script"];
            $content = json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $content .= "\n";
        }

        $this->filesystem->put($this->settings->getProjectLower() . '/' . 'composer.json', $content);
    }
}
