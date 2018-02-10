<?php

declare(strict_types = 1);

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
        $defaults = $this->defaults;
        $composerFile = 'composer';

        if ($this->settings->getTestingFramework() === $defaults->getTestingFrameworks()[0]) {
            $composerFile .= '.' . $this->settings->getTestingFramework();
        }
        $file = $this->filesystem->get(realpath(__DIR__ . '/../stubs/composer/' . $composerFile . '.stub'));
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

        $composer = json_decode($content, true);
        $composer = array_merge($composer, $this->getScriptsAndTheirDescriptions());
        $content = json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $content .= "\n";

        if ($this->settings->withCliFramework()) {
            $composer = json_decode($content, true);
            $composer['bin'] = ["bin/cli-script"];
            $content = json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $content .= "\n";
        }

        $this->filesystem->put($this->settings->getProjectLower() . '/' . 'composer.json', $content);
    }

    /**
     * Returns the default and settings dependent Composer scripts
     * and their descriptions.
     *
     * @return array
     */
    private function getScriptsAndTheirDescriptions()
    {
        $defaults = $this->defaults;

        $scripts = [
            'configure-commit-template' => 'git config --add commit.template .gitmessage',
        ];
        $descriptions = [
            'configure-commit-template' => 'Configures a local Git commit message template.',
        ];

        if ($this->settings->withPhpcsConfiguration()) {
            $scripts['cs-fix'] = 'php-cs-fixer fix . -vv || true';
            $descriptions['cs-fix'] = 'Fixes existing coding standard violations.';

            $scripts['cs-lint'] = 'php-cs-fixer fix --diff --stop-on-violation --verbose --dry-run';
            $descriptions['cs-lint'] = 'Checks for coding standard violations.';
        }

        if ($this->settings->getTestingFramework() === $defaults->getTestingFrameworks()[0]) {
            $scripts['test'] = $this->settings->getTestingFramework();
            $descriptions['test'] = 'Runs all tests.';
        }

        if ($this->settings->getTestingFramework() === $defaults->getTestingFrameworks()[1]) {
            $scripts['test'] = $this->settings->getTestingFramework();
            $descriptions['test'] = 'Runs all features.';
        }

        if ($this->settings->getTestingFramework() === $defaults->getTestingFrameworks()[2]) {
            $scripts['test'] = $this->settings->getTestingFramework() . " run --format=pretty";
            $descriptions['test'] = 'Runs all specs.';
        }

        if ($this->settings->getTestingFramework() === $defaults->getTestingFrameworks()[3]) {
            $scripts['test'] = "codecept run";
            $descriptions['test'] = 'Runs all tests.';
        }

        $this->scriptHelper = $this->container->get('Construct\Helpers\Script');

        if ($this->scriptHelper->isComposerVersionAvailable()) {
            return [
                'scripts' => $scripts,
                'scripts-descriptions' => $descriptions,
            ];
        }

        return [
            'scripts' => $scripts,
        ];
    }
}
