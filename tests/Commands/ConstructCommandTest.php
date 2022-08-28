<?php

namespace Construct\Tests\Commands;

use Construct\Commands\ConstructCommand;
use Construct\Construct;
use Construct\Defaults;
use Construct\Tests\CommandTester;
use League\Container\Container;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;

class ConstructCommandTest extends TestCase
{
    protected $filesystem;
    protected $defaults;
    protected $systemPhpVersion;

    protected function setUp()
    {
        $this->filesystem = Mockery::mock('Construct\Helpers\Filesystem');
        $this->defaults = new Defaults();
        $this->systemPhpVersion = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    public function test_project_generation()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(3, 3, 0, 11);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'name' => 'vendor/project']);

        $this->assertSame('Project "vendor/project" constructed.' . PHP_EOL, $commandTester->getDisplay());
    }

    public function test_project_generation_with_invalid_project_name()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'someinvalidname',
        ]);

        $output = 'Warning: "someinvalidname" is not a valid project name, please use "vendor/project"' . PHP_EOL;

        $this->assertSame($output, $commandTester->getDisplay());
    }

    public function test_project_generation_with_php_in_project_name()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(3, 3);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'name' => 'vendor/php-project']);

        $output = 'Warning: If you are about to create a micro-package "vendor/php-project" ' .
                  'should optimally not contain a "php" notation in the project name.' . PHP_EOL .
                  'Project "vendor/php-project" constructed.' . PHP_EOL;

        $this->assertSame($output, $commandTester->getDisplay());
    }

    public function test_project_generation_with_unknown_license()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(3, 3);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--license' => 'noidealicense',
        ]);

        $output = 'Warning: "noidealicense" is not a supported license. Using MIT.' . PHP_EOL
            . 'Project "vendor/project" constructed.' . PHP_EOL;

        $this->assertSame($output, $commandTester->getDisplay());
    }

    public function test_project_generation_with_unknown_testing_framework()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(3, 3);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--test' => 'idontexist',
        ]);

        $output = 'Warning: "idontexist" is not a supported testing framework. Using phpunit.' . PHP_EOL .
                  'Project "vendor/project" constructed.' . PHP_EOL;

        $this->assertSame($output, $commandTester->getDisplay());
    }

    public function test_project_generation_with_a_specified_testing_framework()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(2, 3, 0, 9, 10);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--test' => 'behat'
        ]);

        $output = 'Initialized behat.' . PHP_EOL . 'Project "vendor/project" constructed.' . PHP_EOL;
        $this->assertSame($output, $commandTester->getDisplay());
    }

    public function test_project_generation_with_a_specified_testing_framework_via_alias()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(2, 3, 0, 9, 10);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--test-framework' => 'behat'
        ]);

        $output = 'Initialized behat.' . PHP_EOL . 'Project "vendor/project" constructed.' . PHP_EOL;
        $this->assertSame($output, $commandTester->getDisplay());
    }

    public function test_project_generation_with_specified_php_version()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(3, 3);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--php' => '5.6.0'
        ]);

        $output = '';

        // show warning on php versions less than 5.6.0
        if (version_compare($this->systemPhpVersion, '5.6.0', '<')) {
            $output .= 'Warning: "5.6.0" is greater than your installed php version. Using version ' . $this->systemPhpVersion . PHP_EOL;
        }

        $output .= 'Project "vendor/project" constructed.' . PHP_EOL;

        $this->assertSame($output, $commandTester->getDisplay());
    }

    public function test_project_generation_with_an_invalid_php_version()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(3, 3);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--php' => 'invalid'
        ]);

        $output = 'Warning: "invalid" is not a valid php version. Using version ' . $this->systemPhpVersion . PHP_EOL .
                  'Project "vendor/project" constructed.' . PHP_EOL;
        $this->assertSame($output, $commandTester->getDisplay());
    }

    public function test_project_generation_with_a_specified_license()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(3, 3);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--license' => 'Apache-2.0'
        ]);

        $this->assertSame('Project "vendor/project" constructed.' . PHP_EOL, $commandTester->getDisplay());
    }

    public function test_project_generation_with_a_specified_namespace()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(3, 3);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--namespace' => 'JonathanTorres\\MyAwesomeProject'
        ]);

        $this->assertSame('Project "vendor/project" constructed.' . PHP_EOL, $commandTester->getDisplay());
    }

    public function test_project_generation_with_an_initialized_github_repo()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(3, 4);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--git' => true
        ]);

        $output = 'Initialized git repo in "project".' . PHP_EOL . 'Project "vendor/project" constructed.' . PHP_EOL;
        $this->assertSame($output, $commandTester->getDisplay());
    }

    public function test_project_generation_with_phpcs()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(3, 3, 1);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--phpcs' => true
        ]);

        $this->assertSame('Project "vendor/project" constructed.' . PHP_EOL, $commandTester->getDisplay());
    }

    public function test_project_generation_with_specified_composer_keywords()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(3, 3);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--keywords' => 'some,project,keywords'
        ]);

        $this->assertSame('Project "vendor/project" constructed.' . PHP_EOL, $commandTester->getDisplay());
    }

    public function test_project_generation_with_vagrant()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(3, 3, 1);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--vagrant' => true
        ]);

        $this->assertSame('Project "vendor/project" constructed.' . PHP_EOL, $commandTester->getDisplay());
    }

    public function test_project_generation_with_editor_config()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(3, 3, 1);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--editor-config' => true
        ]);

        $this->assertSame('Project "vendor/project" constructed.' . PHP_EOL, $commandTester->getDisplay());
    }

    /**
     * @ticket 212 (https://github.com/jonathantorres/construct/issues/212)
     */
    public function test_project_generation_with_existing_directory()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->filesystem->shouldReceive('isDirectory')->times(1)->andReturn(true);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'name' => 'vendor/project']);

        $output = 'Warning: "vendor/project" would be constructed into existing directory "project". '
            . 'Aborting further construction.' . PHP_EOL;

        $this->assertSame($output, $commandTester->getDisplay());
    }

    public function test_project_generation_with_environment_files()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(3, 3, 2);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--env' => true
        ]);

        $this->assertSame('Project "vendor/project" constructed.' . PHP_EOL, $commandTester->getDisplay());
    }

    public function test_project_generation_with_github_templates()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(4, 3, 0, 13, 14);
        $this->filesystem->shouldReceive('move')->times(1)->andReturnNull();

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--github-templates' => true
        ]);

        $this->assertSame('Project "vendor/project" constructed.' . PHP_EOL, $commandTester->getDisplay());
    }

    public function test_project_generation_with_github_docs()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(4, 3);
        $this->filesystem->shouldReceive('put')->times(1)->andReturn(1);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--github-docs' => true
        ]);

        $this->assertSame('Project "vendor/project" constructed.' . PHP_EOL, $commandTester->getDisplay());
    }

    public function test_project_generation_with_cli()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(4, 3, 1, 12, 13);
        $this->filesystem->shouldReceive('put')->times(0);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--cli-framework' => null
        ]);

        $this->assertSame('Project "vendor/project" constructed.' . PHP_EOL, $commandTester->getDisplay());
    }

    public function test_project_generation_with_invalid_cli_package_name()
    {
        $this->filesystem->shouldReceive('getDefaultConfigurationFile');
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile');
        $this->setMocks(4, 3, 1, 12, 13);
        $this->filesystem->shouldReceive('put')->times(0);

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project',
            '--cli-framework' => 'abc'
        ]);

        $output = 'Warning: "abc" is not a valid Composer package name. Using "symfony/console" instead.'
                  . PHP_EOL . 'Project "vendor/project" constructed.' . PHP_EOL;

        $this->assertSame($output, $commandTester->getDisplay());
    }

    public function test_project_generation_from_configuration()
    {
        $configuration = dirname(__DIR__) . '/stubs/config/complete.stub';

        $this->filesystem->shouldReceive('getDefaultConfigurationFile')->andReturn($configuration);
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile')->andReturn(true);
        $this->filesystem->shouldReceive('isFile')->andReturn(true);
        $this->filesystem->shouldReceive('isReadable')->andReturn(true);
        $this->filesystem->shouldReceive('get')->with($configuration)->andReturn(file_get_contents($configuration));
        $this->setMocks(5, 4, 8, 12, 14);
        $this->filesystem->shouldReceive('move')->times(1)->andReturnNull();

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project-from-config',
            '--config' => dirname(__DIR__) . '/stubs/config/complete.stub'
        ]);

        $expectedCommandDisplay = 'Initialized git repo in "project-from-config".' . PHP_EOL
            . 'Project "vendor/project-from-config" constructed.' . PHP_EOL;

        $this->assertSame(
            $expectedCommandDisplay,
            $commandTester->getDisplay()
        );
    }

    public function test_project_generation_with_github_alias()
    {
        $configuration = dirname(__DIR__) . '/stubs/config/complete.github.stub';

        $this->filesystem->shouldReceive('getDefaultConfigurationFile')->andReturn($configuration);
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile')->andReturn(true);
        $this->filesystem->shouldReceive('isFile')->andReturn(true);
        $this->filesystem->shouldReceive('isReadable')->andReturn(true);
        $this->filesystem->shouldReceive('get')->with($configuration)->andReturn(file_get_contents($configuration));
        $this->setMocks(5, 4, 8, 12, 14);
        $this->filesystem->shouldReceive('move')->times(1)->andReturnNull();

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project-from-config',
            '--config' => dirname(__DIR__) . '/stubs/config/complete.github.stub'
        ]);

        $expectedCommandDisplay = 'Initialized git repo in "project-from-config".' . PHP_EOL
            . 'Project "vendor/project-from-config" constructed.' . PHP_EOL;

        $this->assertSame(
            $expectedCommandDisplay,
            $commandTester->getDisplay()
        );
    }

    /**
     * @ticket 126 (https://github.com/jonathantorres/construct/issues/126)
     */
    public function test_project_generation_from_configuration_with_invalid_settings()
    {
        $configuration = dirname(__DIR__) . '/stubs/config/invalid_settings.stub';

        $this->filesystem->shouldReceive('getDefaultConfigurationFile')->andReturn($configuration);
        $this->filesystem->shouldReceive('hasDefaultConfigurationFile')->andReturn(true);
        $this->filesystem->shouldReceive('isFile')->andReturn(true);
        $this->filesystem->shouldReceive('isReadable')->andReturn(true);
        $this->filesystem->shouldReceive('get')->with($configuration)->andReturn(file_get_contents($configuration));
        $this->setMocks(4, 4, 8, 13, 14);
        $this->filesystem->shouldReceive('move')->times(1)->andReturnNull();

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'vendor/project-from-config',
            '--config' => dirname(__DIR__) . '/stubs/config/invalid_settings.stub'
        ]);

        $expectedWarnings = 'Warning: "DOO_WUTCH_YA_LIKE" is not a supported license. '
            . 'Using {license}.' . PHP_EOL
            . 'Warning: "rspec" is not a supported testing framework. '
            . 'Using {testing_framework}.' . PHP_EOL
            . 'Warning: "2.a" is not a valid php version. '
            . 'Using version {php_version}' . PHP_EOL;

        $expectedWarnings = str_replace(
            ['{license}', '{testing_framework}', '{php_version}'],
            [$this->defaults->getLicense(), $this->defaults->getTestingFramework(), $this->systemPhpVersion],
            $expectedWarnings
        );

        $expectedCommandDisplay = $expectedWarnings . 'Initialized git repo in "project-from-config".' . PHP_EOL
            . 'Project "vendor/project-from-config" constructed.' . PHP_EOL;

        $this->assertSame(
            $expectedCommandDisplay,
            $commandTester->getDisplay()
        );
    }

    /**
     * @group integration
     */
    public function test_executable()
    {
        $constructCommand = 'php bin/construct --no-ansi';
        exec($constructCommand, $output, $returnValue);

        $this->assertStringStartsWith(
            'Construct',
            $output[1],
            'Expected application name not present.'
        );

        $this->assertEquals(0, $returnValue);
    }

    /**
     * Set the main command application.
     *
     * @return \Symfony\Component\Console\Application
     */
    private function setApplication()
    {
        $container = new Container();
        $container->add('Construct\Helpers\Filesystem', $this->filesystem);
        $container->add('Construct\Helpers\Git');
        $container->add('Construct\Helpers\Script')->withArgument('Construct\Helpers\Str');
        $container->add('Construct\Helpers\Str');
        $container->add('Construct\Helpers\Travis')->withArgument('Construct\Helpers\Str');
        $container->add('Construct\Configuration')->withArgument('Construct\Helpers\Filesystem');
        $container->add('Construct\Defaults');
        $container->share('Construct\Settings');
        $container->share('Construct\GitAttributes');
        $container->share('Construct\Composer');

        $app = new Application();
        $construct = new Construct($container);
        $app->add(new ConstructCommand($construct));

        return $app;
    }

    /**
     * Set filesystem helper mocks.
     *
     * @param int $makeDirectoryTimes
     * @param int $isDirectoryTimes
     * @param int $copyTimes
     * @param int $getTimes
     * @param int $putTimes
     *
     * @return void
     */
    private function setMocks($makeDirectoryTimes = 3, $isDirectoryTimes = 1, $copyTimes = 0, $getTimes = 11, $putTimes = 12)
    {
        $this->filesystem->shouldReceive('makeDirectory')->times($makeDirectoryTimes)->andReturn(true);
        $this->filesystem->shouldReceive('isDirectory')->times($isDirectoryTimes)->andReturn(false);
        $this->filesystem->shouldReceive('copy')->times($copyTimes)->andReturn(true);
        $this->filesystem->shouldReceive('get')->times($getTimes)->andReturn('{"foo": "bar"}');
        $this->filesystem->shouldReceive('put')->times($putTimes)->andReturn(1);
    }
}
