<?php namespace JonathanTorres\Construct;

use Illuminate\Filesystem\Filesystem;
use JonathanTorres\Construct\Helpers\Git;
use JonathanTorres\Construct\Helpers\Str;

class Construct
{

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
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
     * @param \Illuminate\Filesystem\Filesystem     $file
     * @param \JonathanTorres\Construct\Helpers\Str $str
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
     *
     * @return void
     */
    public function generate(Settings $settings, Git $git)
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

        $this->travis();
        $this->license($git);
        $this->composer($git);
        $this->projectClass();
        $this->projectTest();
        $this->gitattributes();
        $this->composerInstall();

        if ($this->settings->withGitInit()) {
            $this->gitInit();
        }

        if ($this->settings->withVagrantFile()) {
            $this->vagrant();
        }
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
        $readme = $this->file->get(__DIR__ . '/stubs/README.txt');
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
        $content = $this->file->get(__DIR__ . '/stubs/CONTRIBUTING.txt');

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
        $changelog = $this->file->get(__DIR__ . '/stubs/CHANGELOG.txt');
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
        $this->file->copy(__DIR__ . '/stubs/gitignore.txt', $this->projectLower . '/' . '.gitignore');
        $this->exportIgnores[] = '.gitignore';
    }

    /**
     * Generate files for the selected testing framework.
     *
     * @return void
     */
    protected function testing()
    {
        switch ($this->settings->getTestingFramework()) {
            case 'phpunit':
                $this->phpunit();
                break;

            case 'behat':
                $this->behat();
                break;

            case 'phpspec':
                $this->phpspec();
                break;

            case 'codeception':
                $this->codeception();
                break;

            default:
                $this->phpunit();
                break;
        }
    }

    /**
     * Generate PHP CS Fixer configuration file.
     *
     * @return void
     */
    protected function phpcs()
    {
        $this->file->copy(
            __DIR__ . '/stubs/phpcs.txt',
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
        $file = $this->file->get(__DIR__ . '/stubs/travis.txt');
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
            __DIR__ . '/stubs/licenses/' . strtolower($this->settings->getLicense()) . '.txt'
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
        $file = $this->file->get(__DIR__ . '/stubs/composer.txt');
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
        ];

        $content = str_replace($stubs, $values, $file);

        $this->file->put($this->projectLower . '/' . 'composer.json', $content);
    }

    /**
     * Generate project class file.
     *
     * @return void
     */
    protected function projectClass()
    {
        $file = $this->file->get(__DIR__ . '/stubs/Project.txt');

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
     * Generate project test file.
     *
     * @return void
     */
    protected function projectTest()
    {
        $file = $this->file->get(__DIR__ . '/stubs/ProjectTest.txt');

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

        $content = $this->file->get(__DIR__ . '/stubs/gitattributes.txt');

        foreach ($this->exportIgnores as $ignore) {
            $content .= PHP_EOL . '/' . $ignore . ' export-ignore';
        }

        $this->file->put($this->projectLower . '/' . '.gitattributes', $content);
    }

    /**
     * Do an initial composer install in constructed project.
     *
     * @return void
     */
    protected function composerInstall()
    {
        if ($this->file->isDirectory($this->projectLower)) {
            $command = 'cd ' . $this->projectLower . ' && composer install';
            exec($command);
        }
    }

    /**
     * Initialize an empty git repo.
     *
     * @return void
     */
    protected function gitInit()
    {
        if ($this->file->isDirectory($this->projectLower)) {
            $command = 'cd ' . $this->projectLower . ' && git init';
            exec($command);
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
            __DIR__ . '/stubs/Vagrantfile.txt',
            $this->projectLower . '/' . 'Vagrantfile'
        );

        $this->exportIgnores[] = 'Vagrantfile';
    }

    /**
     * Generate phpunit file/settings.
     *
     * @return void
     */
    protected function phpunit()
    {
        $this->testingVersion = '4.7.*';

        $file = $this->file->get(__DIR__ . '/stubs/phpunit.txt');
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
}
