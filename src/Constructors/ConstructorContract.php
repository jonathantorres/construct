<?php

declare(strict_types = 1);

namespace Construct\Constructors;

interface ConstructorContract
{
    /**
     * Run the constructor.
     *
     * @return void
     */
    public function run();
}
