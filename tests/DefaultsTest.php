<?php

namespace Construct\Tests;

use Construct\Defaults;
use PHPUnit\Framework\TestCase;

class DefaultsTest extends TestCase
{
    public function test_installed_php_version_is_used_as_default()
    {
        $defaults = new Defaults();
        $this->assertSame(
            $defaults->getSystemPhpVersion(),
            PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION
        );
    }
}
