<?php namespace JonathanTorres\Construct\Tests;

use JonathanTorres\Construct\Helpers\Str;
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

    public function testNamespaceWithProjectNameAndSingleSlashes()
    {
        $this->assertSame('Vendor\\Project', $this->str->createNamespace('vendor/project', true));
        $this->assertSame('Vendor\\ProjectName', $this->str->createNamespace('vendor/project-name', true));
        $this->assertSame('Vendor\\ProjectName', $this->str->createNamespace('vendor/project.name', true));
        $this->assertSame('Vendor\\Project', $this->str->createNamespace('Vendor/Project', true));
    }

    public function testNamespaceWithProjectNameAndDoubleSlashes()
    {
        $this->assertSame('Vendor\\\\Project', $this->str->createNamespace('vendor/project', true, true));
        $this->assertSame('Vendor\\\\ProjectName', $this->str->createNamespace('vendor/project-name', true, true));
        $this->assertSame('Vendor\\\\ProjectName', $this->str->createNamespace('vendor/project.name', true, true));
        $this->assertSame('Vendor\\\\Project', $this->str->createNamespace('Vendor/Project', true, true));
    }

    public function testNamespaceWithProvidedInputAndSingleSlashes()
    {
        $this->assertSame('Project\\Namespace', $this->str->createNamespace('project\namespace', false));
        $this->assertSame('Project\\Namespace', $this->str->createNamespace('Project\Namespace', false));
    }

    public function testNamespaceWithProvidedInputAndDoubleSlashes()
    {
        $this->assertSame('Project\\\\Namespace', $this->str->createNamespace('project\namespace', false, true));
        $this->assertSame('Project\\\\Namespace', $this->str->createNamespace('Project\Namespace', false, true));
    }

    public function testNamespaceWithProvidedInputWithSingleName()
    {
        $this->assertSame('Namespace', $this->str->createNamespace('namespace'));
    }
}
