<?php

namespace Construct\Tests\Helpers;

use Construct\Defaults;
use Construct\Helpers\Filesystem;
use Construct\Helpers\Str;
use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
    protected $filesystem;
    protected $defaults;
    protected $str;

    protected function setUp(): void
    {
        $this->str = new Str();
        $this->defaults = new Defaults();
        $this->filesystem = new Filesystem($this->defaults);
    }

    public function test_get_home_directory()
    {
        if ($this->str->isWindows()) {
            $this->assertEquals(
                getenv('userprofile'),
                $this->filesystem->getHomeDirectory()
            );
        } else {
            $this->assertEquals(
                getenv('HOME'),
                $this->filesystem->getHomeDirectory()
            );
            putenv('userprofile=abc');
            $this->assertEquals(
                getenv('userprofile'),
                $this->filesystem->getHomeDirectory('WIN')
            );
        }
    }

    public function test_get_default_configuration_file()
    {
        if ($this->str->isWindows()) {
            $this->assertEquals(
                getenv('userprofile') . DIRECTORY_SEPARATOR . $this->defaults->getConfigurationFile(),
                $this->filesystem->getDefaultConfigurationFile()
            );
        } else {
            $this->assertEquals(
                getenv('HOME') . DIRECTORY_SEPARATOR . $this->defaults->getConfigurationFile(),
                $this->filesystem->getDefaultConfigurationFile()
            );
        }
    }

    public function test_has_default_configuration_file()
    {
        $this->assertFalse($this->filesystem->hasDefaultConfigurationFile());
    }
}
