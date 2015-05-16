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

        $output = 'Warning: "idontexist" is not a known testing framework, yet. Using phpunit by default.' . PHP_EOL .
                  'Project "vendor/project" created.' . PHP_EOL;

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

        $output = 'Warning: "noidealicense" is not a known license, yet. Using MIT by default.' . PHP_EOL
            . 'Project "vendor/project" constructed.' . PHP_EOL;

        $this->assertSame($output, $commandTester->getDisplay());
    }

    protected function setApplication()
    {
        $app = new Application();
        $construct = new Construct($this->filesystem, new Str());
        $app->add(new ConstructCommand($construct, new Str()));

        return $app;
    }

    protected function setMocks()
    {
        $this->filesystem->shouldReceive('makeDirectory')->times(3)->andReturnNull()->getMock();
        $this->filesystem->shouldReceive('copy')->once()->andReturnNull()->getMock();
        $this->filesystem->shouldReceive('get')->times(8)->andReturnNull()->getMock();
        $this->filesystem->shouldReceive('put')->times(8)->andReturnNull()->getMock();
    }
}
