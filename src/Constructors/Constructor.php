<?php

declare(strict_types = 1);

namespace Construct\Constructors;

use League\Container\Container;

abstract class Constructor
{
    /**
     * The container instance.
     *
     * @var \League\Container\Container
     */
    protected $container;

    /**
     * The construct command selections instance.
     *
     * @var \Construct\Settings
     */
    protected $settings;

    /**
     * String helper.
     *
     * @var \Construct\Helpers\Str
     */
    protected $str;

    /**
     * The filesystem helper.
     *
     * @var \Construct\Helpers\Filesystem
     */
    protected $filesystem;

    /**
     * The current git attributes.
     *
     * @var \Construct\GitAttributes
     */
    protected $gitAttributes;

    /**
     * The current composer requirements and dev requirements.
     *
     * @var \Construct\Composer
     */
    protected $composer;

    /**
     * Initialize.
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->settings = $container->get('Construct\Settings');
        $this->str = $container->get('Construct\Helpers\Str');
        $this->filesystem = $container->get('Construct\Helpers\Filesystem');
        $this->gitAttributes = $container->get('Construct\GitAttributes');
        $this->composer = $container->get('Construct\Composer');
    }

    /**
     * Construct a correct project namespace name.
     *
     * @param boolean $useDoubleSlashes Whether or not to create the namespace with double slashes \\
     *
     * @return string
     */
    protected function createNamespace(bool $useDoubleSlashes = false): string
    {
        $namespace = $this->settings->getNamespace();
        $projectName = $this->settings->getProjectName();

        if ($namespace === 'Vendor\Project' || $namespace === $projectName) {
            return $this->str->createNamespace($projectName, true, $useDoubleSlashes);
        }

        return $this->str->createNamespace($namespace, false, $useDoubleSlashes);
    }
}
