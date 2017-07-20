<?php

namespace Construct\Tests\Commands;

use Construct\Commands\InteractiveCommand;
use Construct\Helpers\Str;
use Mockery;
use PHPUnit\Framework\TestCase as PHPUnit;
use Symfony\Component\Console\Application;
use Construct\Tests\CommandTester;

class InteractiveCommandTest extends PHPUnit
{
    protected $filesystem;
    protected $construct;

    protected function setUp()
    {
        $this->construct = Mockery::mock('Construct\Construct');
        $this->filesystem = Mockery::mock('Construct\Helpers\Filesystem');
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
