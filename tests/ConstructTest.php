<?php

namespace Construct\Tests;

use Construct\Construct;
use Construct\Constructors\Cli;
use Construct\Constructors\CodeOfConduct;
use Construct\Constructors\Composer;
use Construct\Constructors\Docs;
use Construct\Constructors\EditorConfig;
use Construct\Constructors\EnvironmentFiles;
use Construct\Constructors\GitAttributes;
use Construct\Constructors\GitHubDocs;
use Construct\Constructors\GitHubTemplates;
use Construct\Constructors\GitIgnore;
use Construct\Constructors\GitMessage;
use Construct\Constructors\LgtmFiles;
use Construct\Constructors\License;
use Construct\Constructors\PhpCs;
use Construct\Constructors\ProjectClass;
use Construct\Constructors\Src;
use Construct\Constructors\Tests;
use Construct\Constructors\Travis;
use Construct\Constructors\Vagrant;
use League\Container\Container;
use Mockery;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ConstructTest extends TestCase
{
    protected $container;
    protected $construct;
    protected $filesystem;
    protected $str;
    protected $gitHelper;
    protected $scriptHelper;

    protected function setUp()
    {
        $gitUser = [
            'name' => 'Jonathan Torres',
            'email' => 'jonathantorres41@gmail.com',
        ];

        $this->container = new Container();
        $this->container->add('Construct\Helpers\Filesystem')->withArgument('Construct\Defaults');
        $this->container->add('Construct\Helpers\Git', Mockery::mock('Construct\Helpers\Git'));
        $this->container->add('Construct\Helpers\Script', Mockery::mock('Construct\Helpers\Script'));
        $this->container->add('Construct\Helpers\Str');
        $this->container->add('Construct\Helpers\Travis')->withArgument('Construct\Helpers\Str');
        $this->container->add('Construct\Configuration')->withArgument('Construct\Helpers\Filesystem');
        $this->container->add('Construct\Defaults');
        $this->container->share('Construct\Settings');
        $this->container->share('Construct\GitAttributes');
        $this->container->share('Construct\Composer');

        $this->filesystem = $this->container->get('Construct\Helpers\Filesystem');
        $this->str = $this->container->get('Construct\Helpers\Str');
        $this->scriptHelper = $this->container->get('Construct\Helpers\Script');
        $this->gitHelper = $this->container->get('Construct\Helpers\Git');
        $this->gitHelper->shouldReceive('getUser')->twice()->withNoArgs()->andReturn($gitUser);
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

    public function test_basic_project_is_generated()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        // $settings->setGitInit(null);
        // $settings->setPhpcsConfiguration(null);
        // $settings->setComposerKeywords(null);
        // $settings->setVagrantfile(null);
        // $settings->setEditorConfig(null);
        $settings->setPhpVersion('5.6.0');
        // $settings->setEnvironmentFiles(null);
        // $settings->setLgtmConfiguration(null);
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
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
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('behat');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages(['behat/behat']);
        $this->scriptHelper->shouldReceive('initBehat')->once()->with('logger')->andReturnNull();
        $this->construct->generate();
        $this->assertSame($this->getStub('composer.behat'), $this->getFile('composer.json'));
    }

    public function test_project_generation_with_codeception()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('codeception');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages(
            ['codeception/codeception']
        );
        $this->scriptHelper->shouldReceive('bootstrapCodeception')->once()->with('logger')->andReturnNull();
        $this->construct->generate();
        $this->assertSame($this->getStub('composer.codeception'), $this->getFile('composer.json'));
    }

    public function test_project_generation_with_phpspec()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpspec');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages(
            ['phpspec/phpspec']
        );

        $this->construct->generate();
        $this->assertSame($this->getStub('phpspec'), $this->getFile('phpspec.yml.dist'));
        $this->assertSame($this->getStub('composer.phpspec'), $this->getFile('composer.json'));
        $this->assertSame($this->getStub('gitattributes.phpspec'), $this->getFile('.gitattributes'));
        $this->assertTrue(is_dir(__DIR__ . '/../logger/specs'));
        $this->assertSame($this->getStub('gitignore.phpspec'), $this->getFile('.gitignore'));
    }

    public function test_project_generation_with_apache_license()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('Apache-2.0');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
        $this->assertSame($this->getStub('LICENSE.Apache'), $this->getFile('LICENSE.md'));
    }

    public function test_project_generation_with_gpl2_license()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('GPL-2.0');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
        $this->assertSame($this->getStub('LICENSE.Gpl2'), $this->getFile('LICENSE.md'));
    }

    public function test_project_generation_with_gpl3_license()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('GPL-3.0');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
        $this->assertSame($this->getStub('LICENSE.Gpl3'), $this->getFile('LICENSE.md'));
    }

    public function test_project_generation_with_specified_namespace()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Terminus\Maximus\Logger');
        $settings->setPhpVersion('5.6.0');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
        $this->assertSame($this->getStub('with-namespace/composer'), $this->getFile('composer.json'));
        $this->assertSame($this->getStub('with-namespace/Logger'), $this->getFile('src/Logger.php'));
        $this->assertSame($this->getStub('with-namespace/LoggerTest'), $this->getFile('tests/LoggerTest.php'));
    }

    public function test_project_generation_with_git_repository()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setGitInit(true);
        $settings->setPhpVersion('5.6.0');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();
        $this->gitHelper->shouldReceive('init')->once()->with('logger')->andReturnNull();
        $this->construct->generate();
    }

    public function test_project_generation_with_coding_standards_fixer()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpcsConfiguration(true);
        $settings->setPhpVersion('5.6.0');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages(
            ['phpunit/phpunit', 'friendsofphp/php-cs-fixer']
        );

        $this->construct->generate();

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
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setComposerKeywords('some,another,keyword');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
        $this->assertSame($this->getStub('composer.keywords'), $this->getFile('composer.json'));
    }

    public function test_project_generation_with_vagrant()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setVagrantfile(true);
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
        $this->assertSame($this->getStub('with-vagrant/Vagrantfile'), $this->getFile('Vagrantfile'));
        $this->assertSame($this->getStub('with-vagrant/gitattributes'), $this->getFile('.gitattributes'));
    }

    public function test_project_generation_with_editor_config()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setEditorConfig(true);
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
        $this->assertSame($this->getStub('with-editorconfig/editorconfig'), $this->getFile('.editorconfig'));
        $this->assertSame($this->getStub('with-editorconfig/gitattributes'), $this->getFile('.gitattributes'));
    }

    public function test_project_generation_with_php56()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
        $this->assertSame($this->getStub('composer.php56'), $this->getFile('composer.json'));
        $this->assertSame($this->getStub('travis.php56'), $this->getFile('.travis.yml'));
    }

    public function test_project_generation_with_php7()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('7.0.0');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
        $this->assertSame($this->getStub('composer.php7'), $this->getFile('composer.json'));
        $this->assertSame($this->getStub('travis.php7'), $this->getFile('.travis.yml'));
    }

    public function test_project_generation_with_php71()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('7.1.2');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
        $this->assertSame($this->getStub('composer.php71'), $this->getFile('composer.json'));
        $this->assertSame($this->getStub('travis.php71'), $this->getFile('.travis.yml'));
    }

    public function test_project_generation_with_php72()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('7.2.0');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
        $this->assertSame($this->getStub('composer.php72'), $this->getFile('composer.json'));
        $this->assertSame($this->getStub('travis.php72'), $this->getFile('.travis.yml'));
    }

    /**
     * @ticket 170 (https://github.com/jonathantorres/construct/issues/170)
     */
    public function test_project_generation_with_phpunit6_stub()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('7.1.2');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
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
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('7.0');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
        $this->assertSame(
            $this->getStub('LoggerTest'),
            $this->getFile('tests/LoggerTest.php')
        );
    }

    public function test_project_generation_with_environment_files()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setEnvironmentFiles(true);
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages(
            ['phpunit/phpunit'],
            ['vlucas/phpdotenv']
        );

        $this->construct->generate();
        $this->assertSame($this->getStub('with-env/env'), $this->getFile('.env'));
        $this->assertSame($this->getStub('with-env/env'), $this->getFile('.env.example'));
        $this->assertSame($this->getStub('with-env/gitattributes'), $this->getFile('.gitattributes'));
        $this->assertSame($this->getStub('with-env/composer'), $this->getFile('composer.json'));
        $this->assertSame($this->getStub('with-env/gitignore'), $this->getFile('.gitignore'));
    }

    public function test_project_generation_with_lgtm_configuration()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setLgtmConfiguration(true);
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
        $this->assertSame($this->getStub('with-lgtm/maintainers'), $this->getFile('MAINTAINERS'));
        $this->assertSame($this->getStub('with-lgtm/lgtm'), $this->getFile('.lgtm'));
        $this->assertSame($this->getStub('with-lgtm/gitattributes'), $this->getFile('.gitattributes'));
    }

    public function test_project_generation_with_github_templates()
    {
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setGithubTemplates(true);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
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
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(true);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
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
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(true);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
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
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setGithubTemplates(true);
        $settings->setCodeOfConduct(true);
        $settings->setGithubDocs(false);
        $settings->setCliFramework(null);

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages();

        $this->construct->generate();
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
        $settings = $this->container->get('Construct\Settings');
        $settings->setProjectName('jonathantorres/logger');
        $settings->setTestingFramework('phpunit');
        $settings->setLicense('MIT');
        $settings->setNamespace('Vendor\Project');
        $settings->setPhpVersion('5.6.0');
        $settings->setGithubTemplates(false);
        $settings->setCodeOfConduct(false);
        $settings->setGithubDocs(false);
        $settings->setCliFramework('symfony/console');

        $this->construct = new Construct($this->container);
        $this->setConstructors();
        $this->setScriptHelperComposerInstallExpectationWithPackages(
            ['phpunit/phpunit'],
            [$settings->getCliFramework()]
        );

        $this->construct->generate();

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
     * Set the constructors.
     *
     * @return void
     */
    private function setConstructors()
    {
        $this->construct->addConstructor(new Src($this->construct->getContainer()));
        $this->construct->addConstructor(new Docs($this->construct->getContainer()));
        $this->construct->addConstructor(new Tests($this->construct->getContainer()));
        $this->construct->addConstructor(new Cli($this->construct->getContainer()));
        $this->construct->addConstructor(new PhpCs($this->construct->getContainer()));
        $this->construct->addConstructor(new Vagrant($this->construct->getContainer()));
        $this->construct->addConstructor(new EditorConfig($this->construct->getContainer()));
        $this->construct->addConstructor(new EnvironmentFiles($this->construct->getContainer()));
        $this->construct->addConstructor(new LgtmFiles($this->construct->getContainer()));
        $this->construct->addConstructor(new GitHubTemplates($this->construct->getContainer()));
        $this->construct->addConstructor(new GitHubDocs($this->construct->getContainer()));
        $this->construct->addConstructor(new CodeOfConduct($this->construct->getContainer()));
        $this->construct->addConstructor(new Travis($this->construct->getContainer()));
        $this->construct->addConstructor(new License($this->construct->getContainer()));
        $this->construct->addConstructor(new Composer($this->construct->getContainer()));
        $this->construct->addConstructor(new ProjectClass($this->construct->getContainer()));
        $this->construct->addConstructor(new GitIgnore($this->construct->getContainer()));
        $this->construct->addConstructor(new GitMessage($this->construct->getContainer()));
        $this->construct->addConstructor(new GitAttributes($this->construct->getContainer()));
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
