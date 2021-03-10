<?php

namespace Construct\Tests\Commands;

use Construct\Commands\InteractiveCommand;
use Construct\Construct;
use Construct\Tests\CommandTester;
use League\Container\Container;
use Mockery;
use PHPUnit\Framework\TestCase as PHPUnit;
use Symfony\Component\Console\Application;

class InteractiveCommandTest extends PHPUnit
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_generate_project_interactive()
    {
        $this->markTestSkipped('The same value is being passed to the ask() method.');

        $helper = Mockery::mock('Symfony\Component\Console\Helper\QuestionHelper');
        $helper->shouldReceive('getName');
        $helper->shouldReceive('setHelperSet');
        $helper->shouldReceive('ask')->andReturn('jonathantorres/logger');

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
        $container = new Container();
        $container->add('Construct\Helpers\Filesystem')->withArgument('Construct\Defaults');
        $container->add('Construct\Helpers\Git');
        $container->add('Construct\Helpers\Script')->withArgument('Construct\Helpers\Str');
        $container->add('Construct\Helpers\Str');
        $container->add('Construct\Helpers\Travis')->withArgument('Construct\Helpers\Str');
        $container->add('Construct\Configuration')->withArgument('Construct\Helpers\Filesystem');
        $container->add('Construct\Defaults');
        $container->share('Construct\Settings');
        $container->share('Construct\GitAttributes');
        $container->share('Construct\Composer');

        $construct = new Construct($container);
        $app = new Application();
        $app->add(new InteractiveCommand($construct));

        return $app;
    }
}
