<?php

namespace JonathanTorres\Construct;

use JonathanTorres\Construct\Helpers\Filesystem;
use JonathanTorres\Construct\Helpers\Git;
use JonathanTorres\Construct\Helpers\Script;
use JonathanTorres\Construct\Helpers\Str;

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
     * The selected testing framework version.
     *
     * @var string
     */
    protected $testingVersion;

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
     * @param \JonathanTorres\Construct\Settings    $settings The command settings made by the user.
     * @param \JonathanTorres\Construct\Helpers\Git $git      The git helper.
     * @param \JonathanTorres\Construct\Helpers\Git $script   Script helper.
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
        $this->gitignore();
        $this->testing();

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

        $this->travis();
        $this->license($git);
        $this->composer($git);
        $this->projectClass();
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
        $readme = $this->file->get(__DIR__ . '/stubs/README.stub');
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
        $content = $this->file->get(__DIR__ . '/stubs/CONTRIBUTING.stub');

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
     * Generate gitignore file.
     *
     * @return void
     */
    protected function gitignore()
    {
        $this->file->copy(__DIR__ . '/stubs/gitignore.stub', $this->projectLower . '/' . '.gitignore');
        $this->exportIgnores[] = '.gitignore';
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
     * Generate PHP CS Fixer configuration file.
     *
     * @return void
     */
    protected function phpcs()
    {
        $this->file->copy(
            __DIR__ . '/stubs/phpcs.stub',
            $this->projectLower . '/' . '.php_cs'
        );

        $this->exportIgnores[] = '.php_cs';
    }

    /**
     * Generate .travis.yml file.
     *
     * @return void
     */
    protected function travis()
    {
        $file = $this->file->get(__DIR__ . '/stubs/travis.stub');
        $content = str_replace(
            '{testing}',
            $this->settings->getTestingFramework(),
            $file
        );

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
            '{testing_version}',
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
            $this->testingVersion,
            $this->createNamespace(true),
            $this->settings->getLicense(),
            $user['name'],
            $user['email'],
            $this->str->toQuotedKeywords($this->settings->getComposerKeywords()),
            $this->settings->getPhpVersion(),
        ];

        $content = str_replace($stubs, $values, $file);

        if ($this->settings->withEnvironmentFiles()) {
            $composer = json_decode($content, true);
            $composer['require-dev']['vlucas/phpdotenv'] = '~2.1';
            $content = json_encode($composer, JSON_PRETTY_PRINT);
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
        $file = $this->file->get(__DIR__ . '/stubs/ProjectTest.stub');

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
        $this->exportIgnores[] = 'tests';
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
            $content .= PHP_EOL . '/' . $ignore . ' export-ignore';
        }

        $content .= PHP_EOL;

        $this->file->put($this->projectLower . '/' . '.gitattributes', $content);
    }

    /**
     * Do an initial composer install in constructed project.
     *
     * @param JonathanTorres\Construct\Helpers\Script $script
     *
     * @return void
     */
    protected function composerInstall(Script $script)
    {
        if ($this->file->isDirectory($this->projectLower)) {
            $script->runComposerInstall($this->projectLower);
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
     * Generate .env environment files.
     *
     * @return void
     **/
    protected function environmentFiles()
    {
        $this->file->copy(
            __DIR__ . '/stubs/env.stub',
            $this->projectLower . '/' . '.env'
        );

        $this->file->copy(
            __DIR__ . '/stubs/env.stub',
            $this->projectLower . '/' . '.env.example'
        );

        $this->exportIgnores[] = '.env';
    }

    /**
     * Generate phpunit test/file/settings.
     *
     * @return void
     */
    protected function phpunit()
    {
        $this->phpunitTest();
        $this->testingVersion = '4.8.*';

        $file = $this->file->get(__DIR__ . '/stubs/phpunit.stub');
        $content = str_replace('{project_upper}', $this->projectUpper, $file);

        $this->file->put($this->projectLower . '/' . 'phpunit.xml.dist', $content);
        $this->exportIgnores[] = 'phpunit.xml.dist';
    }

    /**
     * Generate phpspec file/settings.
     *
     * @return void
     */
    protected function phpspec()
    {
        $this->testingVersion = '~2.0';

        $file = $this->file->get(__DIR__ . '/stubs/phpspec.stub');
        $content = str_replace('{namespace}', $this->createNamespace(), $file);

        $this->file->put($this->projectLower . '/' . 'phpspec.yml', $content);
        $this->exportIgnores[] = 'phpspec.yml';
    }

    /**
     * Generate behat file/settings.
     *
     * @return void
     */
    protected function behat()
    {
        $this->testingVersion = '~3.0';
    }

    /**
     * Generate codeception file/settings.
     *
     * @return void
     */
    protected function codeception()
    {
        $this->testingVersion = '2.1.*';
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
