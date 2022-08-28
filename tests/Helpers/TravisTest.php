<?php

namespace Construct\Tests\Helpers;

use Construct\Defaults;
use Construct\Helpers\Str;
use Construct\Helpers\Travis;
use PHPUnit\Framework\TestCase;

class TravisTest extends TestCase
{
    protected $travis;

    protected function setUp()
    {
        $this->travis = new Travis(new Str());
    }

    /**
     * @test
     */
    public function it_should_return_all_versions_to_test_on_a_php56_project()
    {
        $versionsToTest = $this->travis->phpVersionsToTest('5.6.0');
        $versionsExpected = [
            'hhvm',
            'nightly',
            '5.6',
            '7.0',
            '7.1',
            '7.2',
        ];

        $this->assertEquals($versionsToTest, $versionsExpected);
    }

    /**
     * @test
     */
    public function it_should_generate_string_of_all_versions_to_run_on_a_php56_project()
    {
        $versionsToRun = $this->travis->phpVersionsToRun([
            'hhvm',
            '5.6',
            '7.0',
            '7.1',
            '7.2',
        ]);

        $stringExpected = <<<CONTENT
    - php: hhvm
    - php: 5.6
    - php: 7.0
    - php: 7.1
    - php: 7.2
CONTENT;

        $this->assertEquals($versionsToRun, $stringExpected);
    }

    /**
     * @test
     */
    public function it_should_generate_string_of_all_versions_to_run_on_a_php56_project_with_lint_env()
    {
        $versionsToRun = $this->travis->phpVersionsToRun([
            'hhvm',
            '5.6',
            '7.0',
            '7.1',
            '7.2',
        ], true);

        $stringExpected = <<<CONTENT
    - php: hhvm
    - php: 5.6
    - php: 7.0
    - php: 7.1
    - php: 7.2
      env:
      - LINT=true
CONTENT;

        $this->assertEquals($versionsToRun, $stringExpected);
    }

    /**
     * @test
     */
    public function it_should_return_all_versions_to_test_on_a_php5616_project()
    {
        $versionsToTest = $this->travis->phpVersionsToTest('5.6.16');

        $versionsExpected = [
            'hhvm',
            'nightly',
            '5.6',
            '7.0',
            '7.1',
            '7.2',
        ];

        $this->assertEquals($versionsToTest, $versionsExpected);
    }

    /**
     * @test
     */
    public function it_should_return_all_versions_to_test_on_a_php7_0_project()
    {
        $versionsToTest = $this->travis->phpVersionsToTest('7.0.0');
        $versionsExpected = [
            'hhvm',
            'nightly',
            '7.0',
            '7.1',
            '7.2',
        ];

        $this->assertEquals($versionsToTest, $versionsExpected);
    }

    /**
     * @test
     */
    public function it_should_generate_string_of_all_versions_to_run_on_a_php7_0_project()
    {
        $versionsToRun = $this->travis->phpVersionsToRun([
            'hhvm',
            '7.0',
            '7.1',
            '7.2',
        ]);

        $stringExpected = <<<CONTENT
    - php: hhvm
    - php: 7.0
    - php: 7.1
    - php: 7.2
CONTENT;

        $this->assertEquals($versionsToRun, $stringExpected);
    }

    /**
     * @test
     */
    public function it_should_return_all_versions_to_test_on_a_php7_1_project()
    {
        $versionsToTest = $this->travis->phpVersionsToTest('7.1.0');
        $versionsExpected = [
            'hhvm',
            'nightly',
            '7.1',
            '7.2',
        ];

        $this->assertEquals($versionsToTest, $versionsExpected);
    }

    /**
     * @test
     */
    public function it_should_generate_string_of_all_versions_to_run_on_a_php7_1_project()
    {
        $versionsToRun = $this->travis->phpVersionsToRun([
            'hhvm',
            '7.1',
            '7.2',
        ]);

        $stringExpected = <<<CONTENT
    - php: hhvm
    - php: 7.1
    - php: 7.2
CONTENT;

        $this->assertEquals($versionsToRun, $stringExpected);
    }

    /**
     * @test
     */
    public function it_should_generate_string_of_all_versions_to_run_on_a_php7_2_project()
    {
        $versionsToRun = $this->travis->phpVersionsToRun([
            'hhvm',
            '7.2',
        ]);

        $stringExpected = <<<CONTENT
    - php: hhvm
    - php: 7.2
CONTENT;

        $this->assertEquals($versionsToRun, $stringExpected);
    }

    /**
     * @test
     */
    public function it_should_generate_string_of_all_versions_to_run_on_a_php7_1_project_with_lint_env()
    {
        $versionsToRun = $this->travis->phpVersionsToRun([
            'hhvm',
            '7.1',
            '7.2',
        ], true);

        $stringExpected = <<<CONTENT
    - php: hhvm
    - php: 7.1
    - php: 7.2
      env:
      - LINT=true
CONTENT;

        $this->assertEquals($versionsToRun, $stringExpected);
    }

    /**
     * @test
     */
    public function it_should_generate_string_of_all_versions_to_run_on_a_php7_2_project_with_lint_env()
    {
        $versionsToRun = $this->travis->phpVersionsToRun([
            'hhvm',
            '7.2',
        ], true);

        $stringExpected = <<<CONTENT
    - php: hhvm
    - php: 7.2
      env:
      - LINT=true
CONTENT;

        $this->assertEquals($versionsToRun, $stringExpected);
    }

    /**
     * @test
     */
    public function it_should_generate_versions_with_adding_lint_env_on_non_semver_versions()
    {
        $versionsToRun = $this->travis->phpVersionsToRun((new Defaults)->getNonSemverPhpVersions(), true);

        $stringExpected = <<<CONTENT
    - php: hhvm
    - php: nightly
      env:
      - LINT=true
CONTENT;

        $this->assertEquals($versionsToRun, $stringExpected);
    }

    /**
     * @test
     */
    public function it_should_generate_versions_with_adding_lint_env_to_existing_env_var()
    {
        $versionsToRun = $this->travis->phpVersionsToRun([
            '5.5',
            '5.6',
        ], true);

        $stringExpected = <<<CONTENT
    - php: 5.5
    - php: 5.6
      env:
      - LINT=true
CONTENT;

        $this->assertEquals($versionsToRun, $stringExpected);
    }
}
