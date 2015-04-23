<?php namespace JonathanTorres\Construct\Tests;

use JonathanTorres\Construct\Str;
use PHPUnit_Framework_TestCase as PHPUnit;

class StrTest extends PHPUnit
{
    protected $str;

    protected function setUp()
    {
        $this->str = new Str();
    }

    public function testProjectName()
    {
        $this->assertTrue($this->str->isValid('vendor/project'));
        $this->assertTrue($this->str->isValid('Vendor/Project'));
        $this->assertFalse($this->str->isValid('someproject'));
        $this->assertFalse($this->str->isValid('some//project'));
    }

    public function testToLower()
    {
        $this->assertSame('jonathantorres', $this->str->toLower('JonathanTorres'));
        $this->assertSame('jonathantorres', $this->str->toLower('JONATHANTORRES'));
    }

    public function testToStudly()
    {
        $this->assertSame('Jonathan', $this->str->toStudly('jonathan'));
    }

    public function testSplit()
    {
        $result = [
            'vendor' => 'vendor',
            'project' => 'project',
        ];

        $this->assertInternalType('array', $this->str->split('vendor/project'));
        $this->assertSame($result, $this->str->split('vendor/project'));
    }
}
