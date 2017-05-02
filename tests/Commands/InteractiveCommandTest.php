<?php

namespace JonathanTorres\Construct\Tests\Commands;

use JonathanTorres\Construct\Commands\InteractiveCommand;
use JonathanTorres\Construct\Helpers\Str;
use Mockery;
use PHPUnit\Framework\TestCase as PHPUnit;
use Symfony\Component\Console\Application;
use JonathanTorres\Construct\Tests\CommandTester;

class InteractiveCommandTest extends PHPUnit
{
    protected $filesystem;
    protected $construct;

    protected function setUp()
    {
        $this->construct = Mockery::mock('JonathanTorres\Construct\Construct');
        $this->filesystem = Mockery::mock('JonathanTorres\Construct\Helpers\Filesystem');
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    public function testGenerateProjectInteractive()
    {
        $helper = Mockery::mock('Symfony\Component\Console\Helper\QuestionHelper');
        $helper->shouldReceive('getName');
        $helper->shouldReceive('setHelperSet');
        $helper->shouldReceive('ask')->andReturn('jonathantorres/logger');
        $this->construct->shouldReceive('generate')->once()->andReturnNull();

        $app = $this->createApplication();
        $command = $app->find('generate:interactive');
        $command->getHelperSet()->set($helper, 'question');

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);
        $expectedMessage = <<<CONTENT
Creating your project...
Project "jonathantorres/logger" constructed.

CONTENT;

        $this->assertEquals($expectedMessage, $commandTester->getDisplay(true));
    }

    protected function createApplication()
    {
        $app = new Application();
        $app->add(new InteractiveCommand($this->construct, new Str()));

        return $app;
    }
}
