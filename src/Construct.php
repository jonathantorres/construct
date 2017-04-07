<?php

namespace JonathanTorres\Construct;

use JonathanTorres\Construct\Helpers\Filesystem;
use JonathanTorres\Construct\Helpers\Git;
use JonathanTorres\Construct\Helpers\Script;
use JonathanTorres\Construct\Helpers\Str;
use JonathanTorres\Construct\Helpers\Travis;

class Construct
{
    /**
     * The filesystem helper.
     *
     * @var \JonathanTorres\Construct\Helpers\Filesystem
     */
    protected $file;

    /**
     * String helper.
     *
     * @var \JonathanTorres\Construct\Helpers\Str
     */
    protected $str;

    /**
     * The construct command selections instance.
     *
     * @var \JonathanTorres\Construct\Settings
     */
    protected $settings;

    /**
     * Folder to store source files.
     *
     * @var string
     */
    protected $srcPath = 'src';

    /**
     * The files to ignore on exporting.
     *
     * @var array
     */
    protected $exportIgnores = [];

    /**
     * The directories and files to ignore in Git repositories.
     *
     * @var array
     */
    protected $gitIgnores = ['/vendor', 'composer.lock'];

    /**
     * Camel case version of vendor name.
     * ex: JonathanTorres
     *
     * @var string
     */
    protected $vendorUpper;

    /**
     * Lower case version of vendor name.
     * ex: jonathantorres
     *
     * @var string
     */
    protected $vendorLower;

    /**
     * Camel case version of project name.
     * ex: Construct
     *
     * @var string
     */
    protected $projectUpper;

    /**
     * Lower case version of project name.
     * ex: construct
     *
     * @var string
     */
    protected $projectLower;

    /**
     * The Composer requirements/packages.
     *
     * @var array
     */
    protected $requirements = [];

    /**
     * The Composer development requirements/packages.
     *
     * @var array
     */
    protected $developmentRequirements = [];

    /**
     * Initialize.
     *
     * @param \JonathanTorres\Construct\Helpers\Filesystem $file
     * @param \JonathanTorres\Construct\Helpers\Str        $str
     *
     * @return void
     */
    public function __construct(Filesystem $file, Str $str)
    {
        $this->file = $file;
        $this->str = $str;
    }

    /**
     * Generate project.
     *
     * @param \JonathanTorres\Construct\Settings       $settings The command settings made by the user.
     * @param \JonathanTorres\Construct\Helpers\Git    $git      The git helper.
     * @param \JonathanTorres\Construct\Helpers\Script $script   The script helper.
     *
     * @return void
     */
    public function generate(Settings $settings, Git $git, Script $script)
    {
        $this->settings = $settings;

        $this->saveNames();
        $this->root();
        $this->src();
        $this->docs();
        $this->testing();

        if ($this->settings->withCliFramework()) {
            $this->cli();
        }

        if ($this->settings->withPhpcsConfiguration()) {
            $this->phpcs();
        }

        if ($this->settings->withVagrantFile()) {
            $this->vagrant();
        }

        if ($this->settings->withEditorConfig()) {
            $this->editorConfig();
        }

        if ($this->settings->withEnvironmentFiles()) {
            $this->environmentFiles();
        }

        if ($this->settings->withLgtmConfiguration()) {
            $this->lgtmFiles();
        }

        if ($this->settings->withGithubTemplates()) {
            $this->githubTemplates();
        }

        if ($this->settings->withGithubDocs()) {
            $this->githubDocs();
        }

        if ($this->settings->withCodeOfConduct()) {
            $this->codeOfConduct();
        }

        $this->travis();
        $this->license($git);
        $this->composer($git);
        $this->projectClass();
        $this->gitignore();
        $this->gitmessage();
        $this->gitattributes();

        if ($this->settings->withGitInit()) {
            $this->gitInit($git);
        }

        $this->composerInstall($script);
        $this->scripts($script);
    }

    /**
     * Save versions of project names.
     *
     * @return void
     */
    protected function saveNames()
    {
        $names = $this->str->split($this->settings->getProjectName());

        $this->vendorLower = $this->str->toLower($names['vendor']);
        $this->vendorUpper = $this->str->toStudly($names['vendor']);
        $this->projectLower = $this->str->toLower($names['project']);
        $this->projectUpper = $this->str->toStudly($names['project']);
    }

    /**
     * Create project root folder.
     *
     * @return void
     */
    protected function root()
    {
        $this->file->makeDirectory($this->projectLower);
    }

    /**
     * Create 'src' folder.
     *
     * @return void
     */
    protected function src()
    {
        $this->file->makeDirectory($this->projectLower . '/' . $this->srcPath);
    }

    /**
     * Add the CLI framework as a Composer requirement and create CLI entry script.
     *
     * @return void
     */
    protected function cli()
    {
        $this->file->makeDirectory($this->projectLower . '/bin');
        $this->file->copy(
            __DIR__ . '/stubs/cli-script.stub',
            $this->projectLower . '/bin/cli-script'
        );

        $this->requirements[] = $this->settings->getCliFramework();
    }

    /**
     * Generate documentation (README, CONTRIBUTING, CHANGELOG) files.
     *
     * @return void
     */
    protected function docs()
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
    protected function readme()
    {
        if ($this->settings->withCodeOfConduct() === false && $this->settings->withGithubTemplates() === false) {
            $readme = $this->file->get(__DIR__ . '/stubs/README.stub');
        } elseif ($this->settings->withCodeOfConduct() === false && $this->settings->
            withGithubTemplates() === true) {
            $readme = $this->file->get(__DIR__ . '/stubs/README.GITHUB.TEMPLATES.stub');
        } elseif ($this->settings->withCodeOfConduct() === true && $this->settings->
            withGithubTemplates() === false) {
            $readme = $this->file->get(__DIR__ . '/stubs/README.CONDUCT.stub');
        } else {
            $readme = $this->file->get(__DIR__ . '/stubs/README.CONDUCT.GITHUB.TEMPLATES.stub');
        }

        $stubs = [
            '{project_upper}',
            '{license}',
            '{vendor_lower}',
            '{project_lower}'
        ];

        $values = [
            $this->projectUpper,
            $this->settings->getLicense(),
            $this->vendorLower,
            $this->projectLower
        ];

        $content = str_replace($stubs, $values, $readme);

        $this->file->put($this->projectLower . '/' . 'README.md', $content);
        $this->exportIgnores[] = 'README.md';
    }

    /**
     * Generate CONTRIBUTING.md file.
     *
     * @return void
     */
    protected function contributing()
    {
        if ($this->settings->withPhpcsConfiguration()) {
            $contributing = $this->file->get(__DIR__ . '/stubs/CONTRIBUTING.PHPCS.stub');
        } else {
            $contributing = $this->file->get(__DIR__ . '/stubs/CONTRIBUTING.stub');
        }

        $placeholder = ['{project_lower}', '{git_message_path}'];
        $replacements = [$this->projectLower, '.gitmessage'];

        if ($this->settings->withGithubTemplates()) {
            $replacements = [$this->projectLower, '../.gitmessage'];
        }

        $content = str_replace($placeholder, $replacements, $contributing);

        $this->file->put($this->projectLower . '/' . 'CONTRIBUTING.md', $content);
        $this->exportIgnores[] = 'CONTRIBUTING.md';
    }

    /**
     * Generate CHANGELOG.md file.
     *
     * @return void
     */
    protected function changelog()
    {
        $changelog = $this->file->get(__DIR__ . '/stubs/CHANGELOG.stub');
        $content = str_replace(
            '{creation_date}',
            (new \DateTime())->format('Y-m-d'),
            $changelog
        );

        $this->file->put($this->projectLower . '/' . 'CHANGELOG.md', $content);
        $this->exportIgnores[] = 'CHANGELOG.md';
    }

    /**
     * Generate files for the selected testing framework.
     *
     * @return void
     */
    protected function testing()
    {
        $testingFramework = $this->settings->getTestingFramework();

        $this->{$testingFramework}();
    }

    /**
     * Generate PHP CS Fixer configuration file and add package
     * to the development requirements.
     *
     * @return void
     */
    protected function phpcs()
    {
        $this->developmentRequirements[] = 'friendsofphp/php-cs-fixer';

        $this->file->copy(
            __DIR__ . '/stubs/phpcs.stub',
            $this->projectLower . '/' . '.php_cs'
        );

        $this->gitIgnores[] = '.php_cs.cache';
        $this->exportIgnores[] = '.php_cs';
    }

    /**
     * Generate .travis.yml file.
     *
     * @return void
     */
    protected function travis()
    {
        $travisHelper = new Travis();

        if ($this->settings->withPhpcsConfiguration()) {
            $file = $this->file->get(__DIR__ . '/stubs/travis.phpcs.stub');
            $phpVersionsToRunOnTravis = $travisHelper->phpVersionsToRun(
                $travisHelper->phpVersionsToTest($this->settings->getPhpVersion()),
                true
            );
        } else {
            $file = $this->file->get(__DIR__ . '/stubs/travis.stub');
            $phpVersionsToRunOnTravis = $travisHelper->phpVersionsToRun(
                $travisHelper->phpVersionsToTest($this->settings->getPhpVersion())
            );
        }

        $content = str_replace('{phpVersions}', $phpVersionsToRunOnTravis, $file);

        $this->file->put($this->projectLower . '/' . '.travis.yml', $content);
        $this->exportIgnores[] = '.travis.yml';
    }

    /**
     * Generate LICENSE.md file.
     *
     * @param \JonathanTorres\Construct\Helpers\Git $git The git helper.
     *
     * @return void
     */
    protected function license(Git $git)
    {
        $file = $this->file->get(
            __DIR__ . '/stubs/licenses/' . strtolower($this->settings->getLicense()) . '.stub'
        );

        $user = $git->getUser();

        $content = str_replace(
            ['{year}', '{author_name}'],
            [(new \DateTime())->format('Y'), $user['name']],
            $file
        );

        $this->file->put($this->projectLower . '/' . 'LICENSE.md', $content);
        $this->exportIgnores[] = 'LICENSE.md';
    }

    /**
     * Generate composer file.
     *
     * @param \JonathanTorres\Construct\Helpers\Git $git The git helper.
     *
     * @return void
     */
    protected function composer(Git $git)
    {
        $composerFile = 'composer.' . $this->settings->getTestingFramework();

        $file = $this->file->get(__DIR__ . '/stubs/composer/' . $composerFile . '.stub');
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
            $this->projectUpper,
            $this->projectLower,
            $this->vendorLower,
            $this->vendorUpper,
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

        $this->file->put($this->projectLower . '/' . 'composer.json', $content);
    }

    /**
     * Generate project class file.
     *
     * @return void
     */
    protected function projectClass()
    {
        $file = $this->file->get(__DIR__ . '/stubs/Project.stub');

        $stubs = [
            '{project_upper}',
            '{vendor_upper}',
            '{namespace}',
        ];

        $values = [
            $this->projectUpper,
            $this->vendorUpper,
            $this->createNamespace()
        ];

        $content = str_replace($stubs, $values, $file);

        $this->file->put($this->projectLower . '/' . $this->srcPath . '/' . $this->projectUpper . '.php', $content);
    }

    /**
     * Generate phpunit test file.
     *
     * @return void
     */
    protected function phpunitTest()
    {
        if (version_compare($this->settings->getPhpVersion(), '7.0') >= 0) {
            $file = $this->file->get(__DIR__ . '/stubs/ProjectPhpUnit6Test.stub');
        } else {
            $file = $this->file->get(__DIR__ . '/stubs/ProjectTest.stub');
        }

        $stubs = [
            '{project_upper}',
            '{project_camel_case}',
            '{vendor_upper}',
            '{namespace}',
        ];

        $values = [
            $this->projectUpper,
            $this->str->toCamelCase($this->projectLower),
            $this->vendorUpper,
            $this->createNamespace(),
        ];

        $content = str_replace($stubs, $values, $file);

        $this->file->makeDirectory($this->projectLower . '/tests');
        $this->file->put($this->projectLower . '/tests/' . $this->projectUpper . 'Test.php', $content);
        $this->exportIgnores[] = 'tests/';
    }

    /**
     * Generate gitignore file.
     *
     * @return void
     */
    protected function gitignore()
    {
        sort($this->gitIgnores, SORT_STRING | SORT_FLAG_CASE);

        $content = '';

        foreach ($this->gitIgnores as $ignore) {
            $content .= $ignore . "\n";
        }

        $this->file->put($this->projectLower . '/' . '.gitignore', $content);

        $this->exportIgnores[] = '.gitignore';
    }

    /**
     * Generate gitattributes file.
     *
     * @return void
     */
    protected function gitattributes()
    {
        $this->exportIgnores[] = '.gitattributes';
        sort($this->exportIgnores);

        $content = $this->file->get(__DIR__ . '/stubs/gitattributes.stub');

        foreach ($this->exportIgnores as $ignore) {
            $content .= "\n" . $ignore . ' export-ignore';
        }

        $content .= "\n";

        $this->file->put($this->projectLower . '/' . '.gitattributes', $content);
    }

    /**
     * Copy .gitmessage stub file.
     *
     * @return void
     */
    protected function gitmessage()
    {
        $this->file->put(
            $this->projectLower . '/' . '.gitmessage',
            $this->file->get(__DIR__ . '/stubs/gitmessage.stub')
        );

        $this->exportIgnores[] = '.gitmessage';
    }

    /**
     * Do an initial composer install and require the set packages
     * in the constructed project.
     *
     * @param JonathanTorres\Construct\Helpers\Script $script
     *
     * @return void
     */
    protected function composerInstall(Script $script)
    {
        if ($this->file->isDirectory($this->projectLower)) {
            $script->runComposerInstallAndRequirePackages(
                $this->projectLower,
                $this->developmentRequirements,
                $this->requirements
            );
        }
    }

    /**
     * Run any extra scripts.
     *
     * @param JonathanTorres\Construct\Helpers\Script $script
     *
     * @return void
     */
    protected function scripts(Script $script)
    {
        $testingFramework = $this->settings->getTestingFramework();

        if ($this->file->isDirectory($this->projectLower)) {
            if ($testingFramework === 'behat') {
                $script->initBehat($this->projectLower);
            }

            if ($testingFramework === 'codeception') {
                $script->bootstrapCodeception($this->projectLower);
            }
        }
    }

    /**
     * Initialize an empty git repo.
     *
     * @param JonathanTorres\Construct\Helpers\Git $git
     *
     * @return void
     */
    protected function gitInit(Git $git)
    {
        if ($this->file->isDirectory($this->projectLower)) {
            $git->init($this->projectLower);
        }
    }

    /**
     * Generate Vagrant file.
     *
     * @return void
     */
    protected function vagrant()
    {
        $this->file->copy(
            __DIR__ . '/stubs/Vagrantfile.stub',
            $this->projectLower . '/' . 'Vagrantfile'
        );

        $this->exportIgnores[] = 'Vagrantfile';
    }

    /**
     * Generate EditorConfig configuration file.
     *
     * @return void
     **/
    protected function editorConfig()
    {
        $this->file->copy(
            __DIR__ . '/stubs/editorconfig.stub',
            $this->projectLower . '/' . '.editorconfig'
        );

        $this->exportIgnores[] = '.editorconfig';
    }

    /**
     * Generate .env environment files and add package
     * to the development requirements.
     *
     * @return void
     **/
    protected function environmentFiles()
    {
        $this->requirements[] = 'vlucas/phpdotenv';

        $this->file->copy(
            __DIR__ . '/stubs/env.stub',
            $this->projectLower . '/' . '.env'
        );

        $this->file->copy(
            __DIR__ . '/stubs/env.stub',
            $this->projectLower . '/' . '.env.example'
        );

        $this->exportIgnores[] = '.env';
        $this->gitIgnores[] = '.env';
    }

    /**
     * Generate LGTM configuration files.
     *
     * @return void
     */
    protected function lgtmFiles()
    {
        $this->file->copy(
            __DIR__ . '/stubs/MAINTAINERS.stub',
            $this->projectLower . '/' . 'MAINTAINERS'
        );

        $this->file->copy(
            __DIR__ . '/stubs/lgtm.stub',
            $this->projectLower . '/' . '.lgtm'
        );

        $this->exportIgnores[] = 'MAINTAINERS';
        $this->exportIgnores[] = '.lgtm';
    }

    /**
     * Generate GitHub template files.
     *
     * @return void
     */
    protected function githubTemplates()
    {
        $this->file->makeDirectory(
            $this->projectLower . '/.github',
            true
        );

        $templates = ['ISSUE_TEMPLATE', 'PULL_REQUEST_TEMPLATE'];

        foreach ($templates as $template) {
            $this->file->copy(
                __DIR__ . '/stubs/github/' . $template . '.stub',
                $this->projectLower . '/.github/' . $template . '.md'
            );
        }

        $this->file->move(
            $this->projectLower . '/CONTRIBUTING.md',
            $this->projectLower . '/.github/CONTRIBUTING.md'
        );

        $index = array_search('CONTRIBUTING.md', $this->exportIgnores);
        unset($this->exportIgnores[$index]);

        $this->exportIgnores[] = '.github/';
    }

    /**
     * Generate GitHub documentation files.
     *
     * @return void
     */
    protected function githubDocs()
    {
        $this->file->makeDirectory(
            $this->projectLower . '/docs',
            true
        );

        $this->file->put(
            $this->projectLower . '/docs/index.md',
            ''
        );

        $this->exportIgnores[] = 'docs/';
    }

    /**
     * Generate Code of Conduct file.
     *
     * @return void
     */
    protected function codeOfConduct()
    {
        $this->file->copy(
            __DIR__ . '/stubs/CONDUCT.stub',
            $this->projectLower . '/' . 'CONDUCT.md'
        );

        $this->exportIgnores[] = 'CONDUCT.md';
    }

    /**
     * Generate phpunit test/file/settings and add package
     * to the development requirements.
     *
     * @return void
     */
    protected function phpunit()
    {
        $this->phpunitTest();
        $this->developmentRequirements[] = 'phpunit/phpunit';

        $file = $this->file->get(__DIR__ . '/stubs/phpunit.stub');
        $content = str_replace('{project_upper}', $this->projectUpper, $file);

        $this->file->put($this->projectLower . '/' . 'phpunit.xml.dist', $content);
        $this->exportIgnores[] = 'phpunit.xml.dist';

        $this->gitIgnores[] = 'phpunit.xml';
    }

    /**
     * Generate phpspec config file, create a specs directory and
     * add package to development requirements.
     *
     * @return void
     */
    protected function phpspec()
    {
        $this->developmentRequirements[] = 'phpspec/phpspec';

        $file = $this->file->get(__DIR__ . '/stubs/phpspec.stub');
        $content = str_replace('{namespace}', $this->createNamespace(), $file);

        $this->file->makeDirectory($this->projectLower . '/specs');
        $this->exportIgnores[] = 'specs/';

        $this->file->put($this->projectLower . '/' . 'phpspec.yml.dist', $content);
        $this->exportIgnores[] = 'phpspec.yml.dist';

        $this->gitIgnores[] = 'phpspec.yml';
    }

    /**
     * Add behat to development requirements.
     *
     * @return void
     */
    protected function behat()
    {
        $this->developmentRequirements[] = 'behat/behat';
    }

    /**
     * Add codeception to development requirements.
     *
     * @return void
     */
    protected function codeception()
    {
        $this->developmentRequirements[] = 'codeception/codeception';
    }

    /**
     * Construct a correct project namespace name.
     *
     * @param boolean $useDoubleSlashes Whether or not to create the namespace with double slashes \\
     *
     * @return string
     */
    protected function createNamespace($useDoubleSlashes = false)
    {
        $namespace = $this->settings->getNamespace();
        $projectName = $this->settings->getProjectName();

        if ($namespace === 'Vendor\Project' || $namespace === $projectName) {
            return $this->str->createNamespace($projectName, true, $useDoubleSlashes);
        }

        return $this->str->createNamespace($namespace, false, $useDoubleSlashes);
    }

    /**
     * Get project root folder.
     *
     * @return string
     */
    public function getprojectLower()
    {
        return $this->projectLower;
    }
}
