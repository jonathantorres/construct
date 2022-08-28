<?php

namespace Construct\Tests\Helpers;

use Construct\Helpers\Str;
use PHPUnit\Framework\TestCase;

class StrTest extends TestCase
{
    protected $str;

    protected function setUp()
    {
        $this->str = new Str();
    }

    public function test_valid_project_name()
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

    public function test_contains()
    {
        $this->assertTrue($this->str->contains('vendor/php-project', 'php'));
        $this->assertTrue($this->str->contains('vendor/project-php', 'php'));
        $this->assertFalse($this->str->contains('vendor/project', 'php'));
        $this->assertTrue($this->str->contains('vendor/test-project-test', 'test'));
        $this->assertTrue($this->str->contains('vendor/project-test', 'test'));
    }

    public function test_to_lower()
    {
        $this->assertSame('jonathantorres', $this->str->toLower('JonathanTorres'));
        $this->assertSame('jonathantorres', $this->str->toLower('JONATHANTORRES'));
    }

    public function test_to_studly()
    {
        $this->assertSame('Jonathan', $this->str->toStudly('jonathan'));
    }

    public function test_to_camel_case()
    {
        $this->assertSame('fooBar', $this->str->toCamelCase('foo_bar'));
        $this->assertSame('foozBall', $this->str->toCamelCase('fooz-ball'));
        $this->assertSame('volleyBall', $this->str->toCamelCase('volley ball'));
        $this->assertSame('FooBar', $this->str->toCamelCase('foo_bar', true));
        $this->assertSame('FoozBall', $this->str->toCamelCase('fooz-ball', true));
        $this->assertSame('VolleyBall', $this->str->toCamelCase('volley ball', true));
    }

    public function test_split()
    {
        $result = [
            'vendor' => 'vendor',
            'project' => 'project',
        ];

        $this->assertInternalType('array', $this->str->split('vendor/project'));
        $this->assertSame($result, $this->str->split('vendor/project'));
    }

    public function test_namespace_with_project_name_and_single_slashes()
    {
        $this->assertSame('Vendor\\Project', $this->str->createNamespace('vendor/project', true));
        $this->assertSame('Vendor\\ProjectName', $this->str->createNamespace('vendor/project-name', true));
        $this->assertSame('Vendor\\ProjectName', $this->str->createNamespace('vendor/project.name', true));
        $this->assertSame('Vendor\\Project', $this->str->createNamespace('Vendor/Project', true));
    }

    public function test_namespace_with_project_name_and_double_slashes()
    {
        $this->assertSame('Vendor\\\\Project', $this->str->createNamespace('vendor/project', true, true));
        $this->assertSame('Vendor\\\\ProjectName', $this->str->createNamespace('vendor/project-name', true, true));
        $this->assertSame('Vendor\\\\ProjectName', $this->str->createNamespace('vendor/project.name', true, true));
        $this->assertSame('Vendor\\\\Project', $this->str->createNamespace('Vendor/Project', true, true));
    }

    public function test_namespace_with_provided_input_and_single_slashes()
    {
        $this->assertSame('Project\\Namespace', $this->str->createNamespace('project\namespace', false));
        $this->assertSame('Project\\Namespace', $this->str->createNamespace('Project\Namespace', false));
    }

    public function test_namespace_with_provided_input_and_double_slashes()
    {
        $this->assertSame('Project\\\\Namespace', $this->str->createNamespace('project\namespace', false, true));
        $this->assertSame('Project\\\\Namespace', $this->str->createNamespace('Project\Namespace', false, true));
    }

    public function test_namespace_with_provided_input_with_single_name()
    {
        $this->assertSame('Namespace', $this->str->createNamespace('namespace'));
    }

    public function test_is_windows()
    {
        if ($this->str->isWindows()) {
            $this->assertTrue($this->str->isWindows());
        } else {
            $this->assertFalse($this->str->isWindows());
        }

        $this->assertTrue($this->str->isWindows('WIn'));
        $this->assertFalse($this->str->isWindows('Darwin'));
    }

    /**
     * @dataProvider keywordsProvider
     */
    public function test_to_quoted_keywords($keywordsList, $expectedQuotedKeywords)
    {
        $this->assertEquals(
            $expectedQuotedKeywords,
            $this->str->toQuotedKeywords($keywordsList)
        );
    }

    public function test_php_version_is_valid()
    {
        $this->assertTrue($this->str->phpVersionIsValid('5.6.0'));
        $this->assertTrue($this->str->phpVersionIsValid('5.6.10'));
        $this->assertTrue($this->str->phpVersionIsValid('7.0.0'));
        $this->assertTrue($this->str->phpVersionIsValid('7.1.0'));
        $this->assertTrue($this->str->phpVersionIsValid('5.6'));
        $this->assertTrue($this->str->phpVersionIsValid('7.0'));
        $this->assertTrue($this->str->phpVersionIsValid('7.1'));
        $this->assertFalse($this->str->phpVersionIsValid('invalid'));
    }

    /**
     * @return array
     */
    public function keywordsProvider()
    {
        return [
            'keywords_with_whitespace' => ['test, php,vagrant,    provision', '"test", "php", "vagrant", "provision"'],
            'keyword_single' => ['fooo', '"fooo"'],
            'keyword_empty_string' => ['  ', ""],
            'keyword_null' => [null, ""],
        ];
    }

    /**
     * @dataProvider versionProvider
     */
    public function test_returns_expected_minor_version($expected, $version)
    {
        $this->assertEquals($expected, $this->str->toMinorVersion($version));
    }

    /**
     * @return array
     */
    public function versionProvider()
    {
        return [
            '5.6.1' => ['5.6', '5.6.1'],
            '7.1.17' => ['7.1', '7.1.17'],
        ];
    }
}
