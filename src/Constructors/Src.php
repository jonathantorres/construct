<?php

namespace Construct\Constructors;

use Construct\Exceptions\ProjectDirectoryToBeAlreadyExists;

class Src extends Constructor implements ConstructorContract
{
    /**
     * Folder to store source files.
     *
     * @var string
     */
    private $srcPath = 'src';

    /**
     * Checks whether the project directory to be already exist.
     *
     * @return boolean
     */
    private function projectDirectoryExists()
    {
        return $this->filesystem->isDirectory(
            $this->settings->getProjectLower()
        );
    }

    /**
     * This constructor creates the project's root folder
     * and the src folder inside of it.
     *
     * @throws ProjectDirectoryToBeAlreadyExists
     * @return void
     */
    public function run()
    {
        if ($this->projectDirectoryExists()) {
            throw new ProjectDirectoryToBeAlreadyExists();
        }
        $this->filesystem->makeDirectory($this->settings->getProjectLower());
        $this->filesystem->makeDirectory($this->settings->getProjectLower() . '/' . $this->srcPath);
    }
}
