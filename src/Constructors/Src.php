<?php

namespace Construct\Constructors;

use League\Container\Container;

class Src extends Constructor implements ConstructorContract
{
    /**
     * Folder to store source files.
     *
     * @var string
     */
    protected $srcPath = 'src';

    /**
     * This constructor creates the project's root folder
     * and the src folder inside of it.
     *
     * @return void
     */
    public function run()
    {
        $this->filesystem->makeDirectory($this->settings->getProjectLower());
        $this->filesystem->makeDirectory($this->settings->getProjectLower() . '/' . $this->srcPath);
    }
}
