<?php

namespace Construct\Tests;

use Construct\Construct;
use Construct\Helpers\Filesystem;
use Construct\Helpers\Str;
use Construct\Settings;
use Mockery;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ConstructTest extends TestCase
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
        $this->scriptHelper = Mockery::mock('Construct\Helpers\Script');
        $this->gitHelper = Mockery::mock('Construct\Helpers\Git');
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
     * Sets the Mockery expectation for runComposerInstallAndRequirePackages
     * of the script helper mock.
     *
     * @param  array $developmentPackages The development packages to inject. Defaults to ['phpunit/phpunit'].
     * @param  array $packages            The non development packages to inject.
     * @return void
     */
    private function setScriptHelperComposerInstallExpectationWithPackages(
        array $developmentPackages = ['phpunit/phpunit'],
        array $packages = []
    ) {
        $this->scriptHelper
            ->shouldReceive('runComposerInstallAndRequirePackages')
            ->once()
            ->with('logger', $developmentPackages, $packages)
            ->andReturnNull();
    }

    public function test_basic_project_is_generated()
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
        $this->assertSame($this->getStub('gitmessage'), $this->getFile('.gitmessage'));
        $this->assertSame($this->getStub('Logger'), $this->getFile('src/Logger.php'));
        $this->assertSame($this->getStub('LoggerTest'), $this->getFile('tests/LoggerTest.php'));
    }

    public function test_project_generation_with_behat()
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

    public function test_project_generation_with_codeception()
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

    public function test_project_generation_with_phpspec()
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
        $this->assertSame($this->getStub('phpspec'), $this->getFile('phpspec.yml.dist'));
        $this->assertSame($this->getStub('composer.phpspec'), $this->getFile('composer.json'));
        $this->assertSame($this->getStub('gitattributes.phpspec'), $this->getFile('.gitattributes'));
        $this->assertTrue(is_dir(__DIR__ . '/../logger/specs'));
        $this->assertSame($this->getStub('gitignore.phpspec'), $this->getFile('.gitignore'));
    }

    public function test_project_generation_with_apache_license()
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

    public function test_project_generation_with_gpl2_license()
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

    public function test_project_generation_with_gpl3_license()
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

    public function test_project_generation_with_specified_namespace()
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

    public function test_project_generation_with_git_repository()
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

    public function test_project_generation_with_coding_standards_fixer()
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
        $this->assertSame($this->getStub('with-phpcs/travis'), $this->getFile('.travis.yml'));
        $this->assertSame(
            $this->getStub('with-phpcs/CONTRIBUTING'),
            $this->getFile('CONTRIBUTING.md')
        );
    }

    public function test_project_generation_with_composer_keywords()
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

    public function test_project_generation_with_vagrant()
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

    public function test_project_generation_with_editor_config()
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

    public function test_project_generation_with_php54()
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

    public function test_project_generation_with_php55()
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

    public function test_project_generation_with_php56()
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

    public function test_project_generation_with_php7()
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

    public function test_project_generation_with_php71()
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
            '7.1.2',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('composer.php71'), $this->getFile('composer.json'));
        $this->assertSame($this->getStub('travis.php71'), $this->getFile('.travis.yml'));
    }

    /**
     * @ticket 170 (https://github.com/jonathantorres/construct/issues/170)
     */
    public function test_project_generation_with_phpunit6_stub()
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
            '7.1.2',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame(
            $this->getStub('LoggerTest'),
            $this->getFile('tests/LoggerTest.php')
        );
    }

    /**
     * @ticket 192 (https://github.com/jonathantorres/construct/issues/192)
     */
    public function test_project_generation_with_phpunit6_stub_on_php70()
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
            '7.0',
            null,
            null
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame(
            $this->getStub('LoggerTest'),
            $this->getFile('tests/LoggerTest.php')
        );
    }

    public function test_project_generation_with_environment_files()
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
            ['phpunit/phpunit'],
            ['vlucas/phpdotenv']
        );

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);
        $this->assertSame($this->getStub('with-env/env'), $this->getFile('.env'));
        $this->assertSame($this->getStub('with-env/env'), $this->getFile('.env.example'));
        $this->assertSame($this->getStub('with-env/gitattributes'), $this->getFile('.gitattributes'));
        $this->assertSame($this->getStub('with-env/composer'), $this->getFile('composer.json'));
        $this->assertSame($this->getStub('with-env/gitignore'), $this->getFile('.gitignore'));
    }

    public function test_project_generation_with_lgtm_configuration()
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

    public function test_project_generation_with_github_templates()
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
        $this->assertSame(
            $this->getStub('with-github-templates/CONTRIBUTING'),
            $this->getFile('.github/CONTRIBUTING.md')
        );
    }

    public function test_project_generation_with_github_docs()
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

    public function test_project_generation_with_code_of_conduct()
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

    public function test_project_generation_with_code_of_conduct_and_github_templates()
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

    public function test_project_generation_with_default_cli_framework()
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
            null,
            false,
            false,
            false,
            'symfony/console'
        );

        $this->setScriptHelperComposerInstallExpectationWithPackages(
            ['phpunit/phpunit'],
            [$settings->getCliFramework()]
        );

        $this->construct->generate($settings, $this->gitHelper, $this->scriptHelper);

        $this->assertSame(
            $this->getStub('with-cli/composer'),
            $this->getFile('composer.json')
        );
        $this->assertSame(
            $this->getStub('with-cli/cli-script'),
            $this->getFile('bin/cli-script')
        );
        $this->assertSame(
            $this->getStub('with-cli/appveyor'),
            $this->getFile('.appveyor.yml')
        );
        $this->assertSame(
            $this->getStub('with-cli/gitattributes'),
            $this->getFile('.gitattributes')
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
