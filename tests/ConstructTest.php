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
    protected $str;
    protected $gitHelper;
    protected $scriptHelper;
    protected $gitUser = [
        'name' => 'Jonathan Torres',
        'email' => 'jonathantorres41@gmail.com',
    ];

    protected function setUp()
    {
        $this->filesystem = new Filesystem;
        $this->str = new Str;
        $this->construct = new Construct(new Filesystem, $this->str);
        $this->scriptHelper = Mockery::mock('JonathanTorres\Construct\Helpers\Script');
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

        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($path);
    }

    /**
     * Sets the Mockery expectation for runComposerInstallAndRequireDevelopmentPackages
     * of the script helper mock.
     *
     * @param  array $packages The require dev packages to inject. Defaults to
     *                         ['phpunit/phpunit'].
     * @return void
     */
    private function setScriptHelperComposerInstallExpectationWithPackages(
        array $packages = ['phpunit/phpunit']
    ) {
        $this->scriptHelper
            ->shouldReceive('runComposerInstallAndRequireDevelopmentPackages')
            ->once()
            ->with('logger', $packages)
            ->andReturnNull();
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
            null,
            '5.6.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

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
            null,
            '5.6.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages(['behat/behat']);
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
            null,
            '5.6.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages(
            ['codeception/codeception']
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
            null,
            '5.6.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages(
            ['phpspec/phpspec']
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
            null,
            '5.6.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

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
            null,
            '5.6.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

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
            null,
            '5.6.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

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
            null,
            '5.6.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

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
            null,
            '5.6.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();
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
            null,
            '5.6.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages(
            ['phpunit/phpunit', 'friendsofphp/php-cs-fixer']
        );

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);

        if (!$this->str->isWindows()) {
            $this->assertSame(
                $this->getStub('with-phpcs/composer'),
                $this->getFile('composer.json')
            );
        }

        $this->assertSame($this->getStub('with-phpcs/phpcs'), $this->getFile('.php_cs'));
        $this->assertSame($this->getStub('with-phpcs/gitattributes'), $this->getFile('.gitattributes'));
        $this->assertSame(
            $this->getStub('with-phpcs/CONTRIBUTING'),
            $this->getFile('CONTRIBUTING.md')
        );
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
            null,
            '5.6.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

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
            null,
            '5.6.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

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
            true,
            '5.6.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('with-editorconfig/editorconfig'), $this->getFile('.editorconfig'));
        $this->assertSame($this->getStub('with-editorconfig/gitattributes'), $this->getFile('.gitattributes'));
    }

    public function testProjectGenerationWithPhp54()
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
            null,
            '5.4.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('composer.php54'), $this->getFile('composer.json'));
        $this->assertSame($this->getStub('travis.php54'), $this->getFile('.travis.yml'));
    }

    public function testProjectGenerationWithPhp55()
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
            null,
            '5.5.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('composer.php55'), $this->getFile('composer.json'));
        $this->assertSame($this->getStub('travis.php55'), $this->getFile('.travis.yml'));
    }

    public function testProjectGenerationWithPhp56()
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
            null,
            '5.6.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('composer.php56'), $this->getFile('composer.json'));
        $this->assertSame($this->getStub('travis.php56'), $this->getFile('.travis.yml'));
    }

    public function testProjectGenerationWithPhp7()
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
            null,
            '7.0.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('composer.php7'), $this->getFile('composer.json'));
        $this->assertSame($this->getStub('travis.php7'), $this->getFile('.travis.yml'));
    }

    public function testProjectGenerationWithEnvironmentFiles()
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
            false,
            '5.6.0',
            true,
            false
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages(
            ['phpunit/phpunit', 'vlucas/phpdotenv']
        );

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('with-env/env'), $this->getFile('.env'));
        $this->assertSame($this->getStub('with-env/env'), $this->getFile('.env.example'));
        $this->assertSame($this->getStub('with-env/gitattributes'), $this->getFile('.gitattributes'));
        $this->assertSame($this->getStub('with-env/composer'), $this->getFile('composer.json'));
        $this->assertSame($this->getStub('with-env/gitignore'), $this->getFile('.gitignore'));
    }

    public function testProjectGenerationWithLgtmConfiguration()
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
            false,
            '5.6.0',
            null,
            true
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('with-lgtm/maintainers'), $this->getFile('MAINTAINERS'));
        $this->assertSame($this->getStub('with-lgtm/lgtm'), $this->getFile('.lgtm'));
        $this->assertSame($this->getStub('with-lgtm/gitattributes'), $this->getFile('.gitattributes'));
    }

    public function testProjectGenerationWithGitHubTemplates()
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
            false,
            '5.6.0',
            null,
            false,
            true
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame(
            $this->getStub('with-github-templates/issue_template'),
            $this->getFile('.github/ISSUE_TEMPLATE.md')
        );
        $this->assertSame(
            $this->getStub('with-github-templates/pull_request_template'),
            $this->getFile('.github/PULL_REQUEST_TEMPLATE.md')
        );
        $this->assertSame(
            $this->getStub('with-github-templates/gitattributes'),
            $this->getFile('.gitattributes')
        );
        $this->assertSame(
            $this->getStub('with-github-templates/README'),
            $this->getFile('README.md')
        );
    }

    public function testProjectGenerationWithGitHubDocs()
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
            false,
            '5.6.0',
            null,
            false,
            false,
            false,
            true
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertTrue(
            $this->filesystem->isDirectory(__DIR__ . '/../logger/docs')
        );
        $this->assertTrue(
            $this->filesystem->isFile(__DIR__ . '/../logger/docs/index.md')
        );
        $this->assertSame(
            $this->getStub('with-github-docs/gitattributes'),
            $this->getFile('.gitattributes')
        );
    }

    public function testProjectGenerationWithCodeOfConduct()
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
            false,
            '5.6.0',
            null,
            false,
            false,
            true
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame(
            $this->getStub('with-code-of-conduct/CONDUCT'),
            $this->getFile('CONDUCT.md')
        );
        $this->assertSame(
            $this->getStub('with-code-of-conduct/README'),
            $this->getFile('README.md')
        );
        $this->assertSame(
            $this->getStub('with-code-of-conduct/gitattributes'),
            $this->getFile('.gitattributes')
        );
    }

    public function testProjectGenerationWithCodeOfConductAndGitHubTemplates()
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
            false,
            '5.6.0',
            null,
            false,
            true,
            true
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame(
            $this->getStub('with-code-of-conduct/CONDUCT'),
            $this->getFile('CONDUCT.md')
        );
        $this->assertSame(
            $this->getStub('with-code-of-conduct-and-github-templates/README'),
            $this->getFile('README.md')
        );
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
