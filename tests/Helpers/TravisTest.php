<?php

use JonathanTorres\Construct\Helpers\Travis;
use PHPUnit_Framework_TestCase as PHPUnit;

class TravisTest extends PHPUnit
{
    protected $travis;

    protected function setUp()
    {
        $this->travis = new Travis();
    }

    /**
     * @test
     */
    public function it_should_return_all_versions_to_test_on_a_php54_project()
    {
        $versionsToTest = $this->travis->phpVersionsToTest('5.4.0');
        $versionsExpected = [
            'hhvm',
            'nightly',
            '5.4',
            '5.5',
            '5.5.9',
            '5.6',
            '7.0',
        ];

        $this->assertEquals($versionsToTest, $versionsExpected);
    }

    /**
     * @test
     */
    public function it_should_generate_string_of_all_versions_to_run_on_a_php54_project()
    {
        $versionsToRun = $this->travis->phpVersionsToRun([
            'hhvm',
            'nightly',
            '5.4',
            '5.5',
            '5.5.9',
            '5.6',
            '7.0',
        ]);
        $stringExpected = '  - hhvm' . PHP_EOL .
                          '  - nightly' . PHP_EOL .
                          '  - 5.4' . PHP_EOL .
                          '  - 5.5' . PHP_EOL .
                          '  - 5.5.9' . PHP_EOL .
                          '  - 5.6' . PHP_EOL .
                          '  - 7.0';

        $this->assertEquals($versionsToRun, $stringExpected);
    }

    /**
     * @test
     */
    public function it_should_return_all_versions_to_test_on_a_php55_project()
    {
        $versionsToTest = $this->travis->phpVersionsToTest('5.5.0');
        $versionsExpected = [
            'hhvm',
            'nightly',
            '5.5',
            '5.5.9',
            '5.6',
            '7.0',
        ];

        $this->assertEquals($versionsToTest, $versionsExpected);
    }

    /**
     * @test
     */
    public function it_should_generate_string_of_all_versions_to_run_on_a_php55_project()
    {
        $versionsToRun = $this->travis->phpVersionsToRun([
            'hhvm',
            '5.5',
            '5.5.9',
            '5.6',
            '7.0',
        ]);
        $stringExpected = '  - hhvm' . PHP_EOL .
                          '  - 5.5' . PHP_EOL .
                          '  - 5.5.9' . PHP_EOL .
                          '  - 5.6' . PHP_EOL .
                          '  - 7.0';

        $this->assertEquals($versionsToRun, $stringExpected);
    }

    /**
     * @test
     */
    public function it_should_return_all_versions_to_test_on_a_php559_project()
    {
        $versionsToTest = $this->travis->phpVersionsToTest('5.5.9');
        $versionsExpected = [
            'hhvm',
            'nightly',
            '5.5.9',
            '5.6',
            '7.0',
        ];

        $this->assertEquals($versionsToTest, $versionsExpected);
    }

    /**
     * @test
     */
    public function it_should_generate_string_of_all_versions_to_run_on_a_php559_project()
    {
        $versionsToRun = $this->travis->phpVersionsToRun([
            'hhvm',
            '5.5.9',
            '5.6',
            '7.0',
        ]);
        $stringExpected = '  - hhvm' . PHP_EOL .
                          '  - 5.5.9' . PHP_EOL .
                          '  - 5.6' . PHP_EOL .
                          '  - 7.0';

        $this->assertEquals($versionsToRun, $stringExpected);
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
        ]);
        $stringExpected = '  - hhvm' . PHP_EOL .
                          '  - 5.6' . PHP_EOL .
                          '  - 7.0';

        $this->assertEquals($versionsToRun, $stringExpected);
    }

    /**
     * @test
     */
    public function it_should_return_all_versions_to_test_on_a_php7_project()
    {
        $versionsToTest = $this->travis->phpVersionsToTest('7.0.0');
        $versionsExpected = [
            'hhvm',
            'nightly',
            '7.0',
        ];

        $this->assertEquals($versionsToTest, $versionsExpected);
    }

    /**
     * @test
     */
    public function it_should_generate_string_of_all_versions_to_run_on_a_php7_project()
    {
        $versionsToRun = $this->travis->phpVersionsToRun([
            'hhvm',
            '7.0',
        ]);
        $stringExpected = '  - hhvm' . PHP_EOL .
                          '  - 7.0';

        $this->assertEquals($versionsToRun, $stringExpected);
    }
}
