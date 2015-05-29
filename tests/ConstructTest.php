<?php namespace JonathanTorres\Construct\Tests;

use Illuminate\Filesystem\Filesystem;
use JonathanTorres\Construct\Commands\ConstructCommand;
use JonathanTorres\Construct\Construct;
use JonathanTorres\Construct\Str;
use Mockery;
use PHPUnit_Framework_TestCase as PHPUnit;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ConstructTest extends PHPUnit
{
    protected $filesystem;

    protected function setUp()
    {
        $this->filesystem = Mockery::mock('Illuminate\Filesystem\Filesystem');
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    public function testProjectGeneration()
    {
        $this->setMocks();

        $app = $this->setApplication();
        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'name' => 'vendor/project']);

        $this->assertSame('Project "vendor/project" constructed.' . PHP_EOL, $commandTester->getDisplay());
    }

    public function testProjectGenerationWithUnknownTestingFramework()
    {
        $this->setMocks();

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

    public function testProjectGenerationWithUnknownLicense()
    {
        $this->setMocks();

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

    public function testProjectGenerationWithPhpCs()
    {
        $this->setMocks(2);

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

    protected function setApplication()
    {
        $app = new Application();
        $construct = new Construct($this->filesystem, new Str());
        $app->add(new ConstructCommand($construct, new Str()));

        return $app;
    }

    /**
     * @param integer $copyTimes Defaults to 1.
     */
    protected function setMocks($copyTimes = 1)
    {
        $this->filesystem->shouldReceive('makeDirectory')->times(3)->andReturnNull()->getMock();
        $this->filesystem->shouldReceive('copy')->times($copyTimes)->andReturnNull()->getMock();
        $this->filesystem->shouldReceive('get')->times(10)->andReturnNull()->getMock();
        $this->filesystem->shouldReceive('put')->times(10)->andReturnNull()->getMock();
    }
}
