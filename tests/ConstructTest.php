<?php

namespace JonathanTorres\Construct\Tests;

use JonathanTorres\Construct\Construct;
use JonathanTorres\Construct\Helpers\Filesystem;
use JonathanTorres\Construct\Helpers\Str;
use JonathanTorres\Construct\Settings;
use Mockery;
use PHPUnit_Framework_TestCase as PHPUnit;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ConstructTest extends PHPUnit
{
    protected $construct;
    protected $filesystem;
    protected $gitHelper;
    protected $scriptHelper;
    protected $gitUser = [
        'name' => 'Jonathan Torres',
        'email' => 'jonathantorres41@gmail.com',
    ];

    protected function setUp()
    {
        $this->filesystem = new Filesystem;
        $this->construct = new Construct(new Filesystem, new Str);
        $this->scriptHelper = Mockery::mock('JonathanTorres\Construct\Helpers\Script');
        $this->scriptHelper->shouldReceive('runComposerInstall')->once()->with('logger')->andReturnNull();
        $this->gitHelper = Mockery::mock('JonathanTorres\Construct\Helpers\Git');
        $this->gitHelper->shouldReceive('getUser')->twice()->withNoArgs()->andReturn($this->gitUser);
    }

    /**
     * Clean up created project directory.
     *
     * @return void
     */
    protected function tearDown()
    {
        $path = __DIR__ . '/../logger';
        $iterator = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($path);
    }

    public function testBasicProjectIsGenerated()
    {
        $settings = new Settings(
            'jonathantorres/logger',
            'phpunit',
            'MIT',
            'Vendor\Project',
            null,
            null,
            null,
            null,
            null
        );

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('README'), $this->getFile('README.md'));
        $this->assertSame($this->getStub('phpunit'), $this->getFile('phpunit.xml.dist'));
        $this->assertSame($this->getStub('LICENSE'), $this->getFile('LICENSE.md'));
        $this->assertSame($this->getStub('CONTRIBUTING'), $this->getFile('CONTRIBUTING.md'));
        $this->assertSame($this->getStub('composer'), $this->getFile('composer.json'));
        $this->assertSame($this->getChangelogStub(), $this->getFile('CHANGELOG.md'));
        $this->assertSame($this->getStub('travis'), $this->getFile('.travis.yml'));
        $this->assertSame($this->getStub('gitignore'), $this->getFile('.gitignore'));
        $this->assertSame($this->getStub('gitattributes'), $this->getFile('.gitattributes'));
        $this->assertSame($this->getStub('Logger'), $this->getFile('src/Logger.php'));
        $this->assertSame($this->getStub('LoggerTest'), $this->getFile('tests/LoggerTest.php'));
    }

    public function testProjectGenerationWithBehat()
    {
        $settings = new Settings(
            'jonathantorres/logger',
            'behat',
            'MIT',
            'Vendor\Project',
            null,
            null,
            null,
            null,
            null
        );

        $this->scriptHelper->shouldReceive('initBehat')->once()->with('logger')->andReturnNull();
        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('composer.behat'), $this->getFile('composer.json'));
    }

    public function testProjectGenerationWithCodeception()
    {
        $settings = new Settings(
            'jonathantorres/logger',
            'codeception',
            'MIT',
            'Vendor\Project',
            null,
            null,
            null,
            null,
            null
        );

        $this->scriptHelper->shouldReceive('bootstrapCodeception')->once()->with('logger')->andReturnNull();
        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('composer.codeception'), $this->getFile('composer.json'));
    }

    public function testProjectGenerationWithPhpSpec()
    {
        $settings = new Settings(
            'jonathantorres/logger',
            'phpspec',
            'MIT',
            'Vendor\Project',
            null,
            null,
            null,
            null,
            null
        );

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('phpspec'), $this->getFile('phpspec.yml'));
        $this->assertSame($this->getStub('composer.phpspec'), $this->getFile('composer.json'));
    }

    public function testProjectGenerationWithApacheLicense()
    {
        $settings = new Settings(
            'jonathantorres/logger',
            'phpunit',
            'Apache-2.0',
            'Vendor\Project',
            null,
            null,
            null,
            null,
            null
        );

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('LICENSE.Apache'), $this->getFile('LICENSE.md'));
    }

    public function testProjectGenerationWithGpl2License()
    {
        $settings = new Settings(
            'jonathantorres/logger',
            'phpunit',
            'GPL-2.0',
            'Vendor\Project',
            null,
            null,
            null,
            null,
            null
        );

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('LICENSE.Gpl2'), $this->getFile('LICENSE.md'));
    }

    public function testProjectGenerationWithGpl3License()
    {
        $settings = new Settings(
            'jonathantorres/logger',
            'phpunit',
            'GPL-3.0',
            'Vendor\Project',
            null,
            null,
            null,
            null,
            null
        );

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('LICENSE.Gpl3'), $this->getFile('LICENSE.md'));
    }

    public function testProjectGenerationWithSpecifiedNamespace()
    {
        $settings = new Settings(
            'jonathantorres/logger',
            'phpunit',
            'MIT',
            'Terminus\Maximus\Logger',
            null,
            null,
            null,
            null,
            null
        );

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('with-namespace/composer'), $this->getFile('composer.json'));
        $this->assertSame($this->getStub('with-namespace/Logger'), $this->getFile('src/Logger.php'));
        $this->assertSame($this->getStub('with-namespace/LoggerTest'), $this->getFile('tests/LoggerTest.php'));
    }

    public function testProjectGenerationWithGitRepository()
    {
        $settings = new Settings(
            'jonathantorres/logger',
            'phpunit',
            'MIT',
            'Vendor\Project',
            true,
            null,
            null,
            null,
            null
        );

        $this->gitHelper->shouldReceive('init')->once()->with('logger')->andReturnNull();
        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
    }

    public function testProjectGenerationWithCodingStandardsFixer()
    {
        $settings = new Settings(
            'jonathantorres/logger',
            'phpunit',
            'MIT',
            'Vendor\Project',
            null,
            true,
            null,
            null,
            null
        );

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('with-phpcs/phpcs'), $this->getFile('.php_cs'));
        $this->assertSame($this->getStub('with-phpcs/gitattributes'), $this->getFile('.gitattributes'));
    }

    public function testProjectGenerationWithComposerKeywords()
    {
        $settings = new Settings(
            'jonathantorres/logger',
            'phpunit',
            'MIT',
            'Vendor\Project',
            null,
            null,
            'some,another,keyword',
            null,
            null
        );

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('composer.keywords'), $this->getFile('composer.json'));
    }

    public function testProjectGenerationWithVagrant()
    {
        $settings = new Settings(
            'jonathantorres/logger',
            'phpunit',
            'MIT',
            'Vendor\Project',
            null,
            null,
            null,
            true,
            null
        );

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('with-vagrant/Vagrantfile'), $this->getFile('Vagrantfile'));
        $this->assertSame($this->getStub('with-vagrant/gitattributes'), $this->getFile('.gitattributes'));
    }

    public function testProjectGenerationWithEditorConfig()
    {
        $settings = new Settings(
            'jonathantorres/logger',
            'phpunit',
            'MIT',
            'Vendor\Project',
            null,
            null,
            null,
            null,
            true
        );

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('with-editorconfig/editorconfig'), $this->getFile('.editorconfig'));
        $this->assertSame($this->getStub('with-editorconfig/gitattributes'), $this->getFile('.gitattributes'));
    }

    /**
     * Get expected changelog file.
     *
     * @return string
     */
    private function getChangelogStub()
    {
        $changelog = $this->filesystem->get(__DIR__ . '/stubs/CHANGELOG.stub');

        return str_replace(
            '{creation_date}',
            (new \DateTime())->format('Y-m-d'),
            $changelog
        );
    }

    /**
     * Get a stub file.
     *
     * @param string $path
     *
     * @return string
     */
    private function getStub($path)
    {
        return $this->filesystem->get(__DIR__ . '/stubs/' . $path . '.stub');
    }

    /**
     * Get generated file.
     *
     * @param string $path
     *
     * @return string
     */
    private function getFile($path)
    {
        return $this->filesystem->get(__DIR__ . '/../logger/' . $path);
    }
}
