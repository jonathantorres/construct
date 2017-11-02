<?php

declare(strict_types = 1);

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
    public function getRequirements(): array
    {
        return $this->requirements;
    }

    /**
     * Add a composer requirement
     *
     * @param string $requirement
     *
     * @return void
     */
    public function addRequirement(string $requirement)
    {
        $this->requirements[] = $requirement;
    }

    /**
     * Get the composer development requirements/packages
     *
     * @return array
     */
    public function getDevelopmentRequirements(): array
    {
        return $this->developmentRequirements;
    }

    /**
     * Add a composer development requirement
     *
     * @param string $requirement
     *
     * @return void
     */
    public function addDevelopmentRequirement(string $requirement)
    {
        $this->developmentRequirements[] = $requirement;
    }
}
