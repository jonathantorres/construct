<?php

namespace Construct\Tests\Commands;

use Construct\Commands\InteractiveCommand;
use Mockery;
use PHPUnit\Framework\TestCase as PHPUnit;
use Symfony\Component\Console\Application;
use Construct\Tests\CommandTester;

class InteractiveCommandTest extends PHPUnit
{
    protected $construct;

    protected function setUp()
    {
        $this->construct = Mockery::mock('Construct\Construct');
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    public function test_generate_project_interactive()
    {
        // @todo
        $this->markTestSkipped('Fixes on mocking objects.');

        $helper = Mockery::mock('Symfony\Component\Console\Helper\QuestionHelper');
        $helper->shouldReceive('getName');
        $helper->shouldReceive('setHelperSet');
        $helper->shouldReceive('ask')->andReturn('jonathantorres/logger');

        $container = Mockery::mock('League\Container\Container');
        $container->shouldReceive('get');

        $this->construct->shouldReceive('getContainer')->andReturn($container);
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
        $app->add(new InteractiveCommand($this->construct));

        return $app;
    }
}
