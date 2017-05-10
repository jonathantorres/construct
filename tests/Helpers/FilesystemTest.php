<?php

namespace JonathanTorres\Construct\Tests\Helpers;

use JonathanTorres\Construct\Defaults;
use JonathanTorres\Construct\Helpers\Filesystem;
use JonathanTorres\Construct\Helpers\Str;
use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
    protected $filesystem;
    protected $str;

    protected function setUp()
    {
        $this->str = new Str();
        $this->filesystem = new Filesystem();
    }

    public function testGetHomeDirectory()
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

    public function testGetDefaultConfigurationFile()
    {
        if ($this->str->isWindows()) {
            $this->assertEquals(
                getenv('userprofile') . DIRECTORY_SEPARATOR . Defaults::CONFIGURATION_FILE,
                $this->filesystem->getDefaultConfigurationFile()
            );
        } else {
            $this->assertEquals(
                getenv('HOME') . DIRECTORY_SEPARATOR . Defaults::CONFIGURATION_FILE,
                $this->filesystem->getDefaultConfigurationFile()
            );
        }
    }

    public function testHasDefaultConfigurationFile()
    {
        $this->assertFalse($this->filesystem->hasDefaultConfigurationFile());
    }
}
