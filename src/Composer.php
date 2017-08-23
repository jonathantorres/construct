<?php

namespace Construct;

class Composer
{
    /**
     * The Composer requirements/packages.
     *
     * @var array
     */
    private $requirements = [];

    /**
     * The Composer development requirements/packages.
     *
     * @var array
     */
    private $developmentRequirements = [];

    /**
     * Get the composer requirements/packages
     *
     * @return array
     */
    public function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * Add a composer requirement
     *
     * @param string $requirement
     *
     * @return  void
     */
    public function addComposerRequirement($requirement)
    {
        $this->requirements[] = $requirement;
    }

    /**
     * Get the composer development requirements/packages
     *
     * @return array
     */
    public function getDevelopmentRequirements()
    {
        return $this->developmentRequirements;
    }

    /**
     * Add a composer development requirement
     *
     * @param string $requirement
     *
     * @return  void
     */
    public function addDevelopmentRequirement($requirement)
    {
        $this->developmentRequirements[] = $requirement;
    }
}
