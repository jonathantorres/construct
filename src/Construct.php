<?php

declare(strict_types = 1);

namespace Construct;

use Construct\Constructors\ConstructorContract;
use League\Container\Container;

class Construct
{
    /**
     * The container instance
     *
     * @var \League\Container\Container
     */
    private $container;

    /**
     * The construct command selections instance.
     *
     * @var \Construct\Settings
     */
    private $settings;

    /**
     * The filesystem helper.
     *
     * @var \Construct\Helpers\Filesystem
     */
    private $filesystem;

    /**
     * The registered constructors
     *
     * @var array
     */
    private $constructors = [];

    /**
     * Initialize.
     *
     * @param \League\Container\Container $container
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->settings = $container->get('Construct\Settings');
        $this->filesystem = $container->get('Construct\Helpers\Filesystem');
    }

    /**
     * Generates the project using the specified constructors.
     *
     * @throws ProjectDirectoryToBeAlreadyExists
     * @return void
     */
    public function generate()
    {
        $this->saveProjectNames();

        foreach ($this->constructors as $constructor) {
            $constructor->run();
        }

        if ($this->settings->withGitInit()) {
            $this->gitInit();
        }

        $this->composerInstall();

        $this->scripts();
    }

    /**
     * Adds a constructor.
     *
     * @param \Construct\Constructors\ConstructorContract $constructor
     *
     * @return void
     */
    public function addConstructor(ConstructorContract $constructor)
    {
        $this->constructors[] = $constructor;
    }

    /**
     * Returns the container instance.
     *
     * @return \League\Container\Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * Save versions of project names.
     *
     * @todo  Maybe refactor this somewhere else?
     *        This class shouldn't set project settings
     *
     * @return void
     */
    private function saveProjectNames()
    {
        $str = $this->container->get('Construct\Helpers\Str');
        $names = $str->split($this->settings->getProjectName());

        $this->settings->setVendorUpper($str->toStudly($names['vendor']));
        $this->settings->setVendorLower($str->toLower($names['vendor']));
        $this->settings->setProjectUpper($str->toStudly($names['project']));
        $this->settings->setProjectLower($str->toLower($names['project']));
    }

    /**
     * Initialize an empty git repo.
     *
     * @return void
     */
    private function gitInit()
    {
        $git = $this->container->get('Construct\Helpers\Git');

        if ($this->filesystem->isDirectory($this->settings->getProjectLower())) {
            $git->init($this->settings->getProjectLower());
        }
    }

    /**
     * Do an initial composer install and require the set packages
     * in the constructed project.
     *
     * @return void
     */
    private function composerInstall()
    {
        $script = $this->container->get('Construct\Helpers\Script');
        $composer = $this->container->get('Construct\Composer');

        if ($this->filesystem->isDirectory($this->settings->getProjectLower())) {
            $script->runComposerInstallAndRequirePackages(
                $this->settings->getProjectLower(),
                $composer->getDevelopmentRequirements(),
                $composer->getRequirements()
            );
        }
    }

    /**
     * Run any extra scripts.
     *
     * @return void
     */
    private function scripts()
    {
        $script = $this->container->get('Construct\Helpers\Script');
        $testingFramework = $this->settings->getTestingFramework();

        if ($this->filesystem->isDirectory($this->settings->getProjectLower())) {
            if ($testingFramework === 'behat') {
                $script->initBehat($this->settings->getProjectLower());
            }

            if ($testingFramework === 'codeception') {
                $script->bootstrapCodeception($this->settings->getProjectLower());
            }
        }
    }
}
