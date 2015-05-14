<?php namespace JonathanTorres\Construct;

use Illuminate\Filesystem\Filesystem;
use JonathanTorres\Construct\Str;

class Construct
{

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     **/
    protected $file;

    /**
     * String helper.
     *
     * @var \JonathanTorres\Construct\Str
     **/
    protected $str;

    /**
     * Folder to store source files.
     *
     * @var string
     **/
    protected $srcPath = 'src';

    /**
     * Entered project name.
     *
     * @var string
     **/
    protected $projectName;

    /**
     * The files to ignore on exporting.
     *
     * @var array
     **/
    protected $exportIgnores = [];

    /**
     * The selected testing framework.
     *
     * @var string
     **/
    protected $testing;

    /**
     * The selected license.
     *
     * @var string
     **/
    protected $license;

    /**
     * The selected testing framework version.
     *
     * @var string
     **/
    protected $testingVersion;

    /**
     * Warn if the specified testing framework is not known.
     *
     * @var boolean
     **/
    protected $testingWarning = false;

    /**
     * Camel case version of vendor name.
     * ex: JonathanTorres
     *
     * @var string
     **/
    protected $vendorUpper;

    /**
     * Lower case version of vendor name.
     * ex: jonathantorres
     *
     * @var string
     **/
    protected $vendorLower;

    /**
     * Camel case version of project name.
     * ex: Construct
     *
     * @var string
     **/
    protected $projectUpper;

    /**
     * Lower case version of project name.
     * ex: construct
     *
     * @var string
     **/
    protected $projectLower;

    /**
     * Initialize.
     *
     * @param \Illuminate\Filesystem\Filesystem $file
     * @param \JonathanTorres\Construct\Str $str
     *
     * @return void
     **/
    public function __construct(Filesystem $file, Str $str)
    {
        $this->file = $file;
        $this->str = $str;
    }

    /**
     * Generate project.
     *
     * @param string $projectName The entered project name.
     * @param string $testing The entered testing framework.
     *
     * @return boolean
     **/
    public function generate($projectName, $testing, $license)
    {
        $this->projectName = $projectName;
        $this->testing = $testing;
        $this->license = $license;

        $this->saveNames();
        $this->root();
        $this->src();
        $this->readme();
        $this->testing();
        $this->gitignore();
        $this->travis();
        $this->license();
        $this->composer();
        $this->projectClass();
        $this->projectTest();
        $this->gitattributes();

        return $this->testingWarning;
    }

    /**
     * Save versions of project names.
     *
     * @return void
     **/
    protected function saveNames()
    {
        $names = $this->str->split($this->projectName);

        $this->vendorLower = $this->str->toLower($names['vendor']);
        $this->vendorUpper = $this->str->toStudly($names['vendor']);
        $this->projectLower = $this->str->toLower($names['project']);
        $this->projectUpper = $this->str->toStudly($names['project']);
    }

    /**
     * Generate files for the selected testing framework.
     *
     * @return void
     **/
    protected function testing()
    {
        switch ($this->testing) {
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
                $this->testingWarning = true;
                $this->phpunit();
                break;
        }
    }

    /**
     * Create project root folder.
     *
     * @return void
     **/
    protected function root()
    {
        $this->file->makeDirectory($this->projectLower);
    }

    /**
     * Create 'src' folder.
     *
     * @return void
     **/
    protected function src()
    {
        $this->file->makeDirectory($this->projectLower . '/' . $this->srcPath);
    }

    /**
     * Generate gitignore file.
     *
     * @return void
     **/
    protected function gitignore()
    {
        $this->file->copy(__DIR__ . '/stubs/gitignore.txt', $this->projectLower . '/' . '.gitignore');
    }

    /**
     * Generate gitattributes file.
     *
     * @return void
     **/
    protected function gitattributes()
    {
        $content = $this->file->get(__DIR__ . '/stubs/gitattributes.txt');

        foreach ($this->exportIgnores as $ignore) {
            $content .= PHP_EOL . '/' . $ignore . ' export-ignore';
        }

        $this->file->put($this->projectLower . '/' . '.gitattributes', $content);
    }

    /**
     * Generate README file.
     *
     * @return void
     **/
    protected function readme()
    {
        $file = $this->file->get(__DIR__ . '/stubs/README.txt');
        $content = str_replace('{project_upper}', $this->projectUpper, $file);

        $this->file->put($this->projectLower . '/' . 'README.md', $content);
    }

    /**
     * Generate phpunit file/settings.
     *
     * @return void
     **/
    protected function phpunit()
    {
        $this->testingVersion = '4.6.*';

        $file = $this->file->get(__DIR__ . '/stubs/phpunit.txt');
        $content = str_replace('{project_upper}', $this->projectUpper, $file);

        $this->file->put($this->projectLower . '/' . 'phpunit.xml.dist', $content);
        $this->exportIgnores[] = 'phpunit.xml.dist';
    }

    /**
     * Generate phpspec file/settings.
     *
     * @return void
     **/
    protected function phpspec()
    {
        $this->testingVersion = '~2.0';
    }

    /**
     * Generate behat file/settings.
     *
     * @return void
     **/
    protected function behat()
    {
        $this->testingVersion = '~3.0';
    }

    /**
     * Generate codeception file/settings.
     *
     * @return void
     **/
    protected function codeception()
    {
        $this->testingVersion = '2.0.*';
    }

    /**
     * Generate .travis.yml file.
     *
     * @return void
     **/
    protected function travis()
    {
        $file = $this->file->get(__DIR__ . '/stubs/travis.txt');
        $content = str_replace('{testing}', $this->testing, $file);

        $this->file->put($this->projectLower . '/' . '.travis.yml', $content);
    }

    /**
     * Generate LICENSE.md file.
     *
     * @return void
     */
    protected function license()
    {
        $file = $this->file->get(__DIR__ . '/stubs/licenses/'.strtolower($this->license).'.txt');
        $content = str_replace(['{year}', '{name}'], [(new \DateTime())->format('Y'), $this->vendorUpper], $file);

        $this->file->put($this->projectLower . '/' . 'LICENSE.md', $content);
    }

    /**
     * Generate composer file.
     *
     * @return void
     **/
    protected function composer()
    {
        $file = $this->file->get(__DIR__ . '/stubs/composer.txt');
        $stubs = ['{project_upper}', '{project_lower}', '{vendor_lower}', '{vendor_upper}', '{testing}', '{testing_version}', '{license}'];
        $values = [$this->projectUpper, $this->projectLower, $this->vendorLower, $this->vendorUpper, $this->testing, $this->testingVersion, $this->license];

        $content = str_replace($stubs, $values, $file);

        $this->file->put($this->projectLower . '/' . 'composer.json', $content);
    }

    /**
     * Generate project class file.
     *
     * @return void
     **/
    protected function projectClass()
    {
        $file = $this->file->get(__DIR__ . '/stubs/Project.txt');
        $content = str_replace(['{project_upper}', '{vendor_upper}'], [$this->projectUpper, $this->vendorUpper], $file);

        $this->file->put($this->projectLower . '/' . $this->srcPath . '/' . $this->projectUpper . '.php', $content);
    }

    /**
     * Generate project test file.
     *
     * @return void
     **/
    protected function projectTest()
    {
        $file = $this->file->get(__DIR__ . '/stubs/ProjectTest.txt');
        $stubs = [
            '{project_upper}',
            '{project_camel_case}',
            '{vendor_upper}'
        ];
        $values = [
            $this->projectUpper,
            $this->str->toCamelCase($this->projectLower),
            $this->vendorUpper
        ];

        $content = str_replace($stubs, $values, $file);

        $this->file->makeDirectory($this->projectLower . '/tests');
        $this->file->put($this->projectLower . '/tests/' . $this->projectUpper . 'Test.php', $content);
    }
}
