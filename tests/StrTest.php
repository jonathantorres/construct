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
        $this->assertTrue($this->str->isValid('vendor/project-name'));
        $this->assertTrue($this->str->isValid('vendor/project.name'));
        $this->assertTrue($this->str->isValid('Vendor/Project'));
        $this->assertFalse($this->str->isValid('someproject'));
        $this->assertFalse($this->str->isValid('some//project'));
        $this->assertFalse($this->str->isValid('some/_project'));
        $this->assertFalse($this->str->isValid('some\project'));
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

    public function testToCamelCase()
    {
        $this->assertSame('fooBar', $this->str->toCamelCase('foo_bar'));
        $this->assertSame('foozBall', $this->str->toCamelCase('fooz-ball'));
        $this->assertSame('volleyBall', $this->str->toCamelCase('volley ball'));

        $this->assertSame('FooBar', $this->str->toCamelCase('foo_bar', true));
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
