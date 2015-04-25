<?php namespace JonathanTorres\Construct\Tests;

use Illuminate\Filesystem\Filesystem;
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
        $this->filesystem->shouldReceive('makeDirectory')->times(3)->andReturnNull()->getMock();
        $this->filesystem->shouldReceive('copy')->times(2)->andReturnNull()->getMock();
        $this->filesystem->shouldReceive('get')->times(5)->andReturnNull()->getMock();
        $this->filesystem->shouldReceive('put')->times(5)->andReturnNull()->getMock();

        $app = new Application();
        $app->add(new Construct($this->filesystem, new Str()));

        $command = $app->find('generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'name' => 'vendor/project']);

        $this->assertSame('Project "vendor/project" created.' . PHP_EOL, $commandTester->getDisplay());
    }
}
