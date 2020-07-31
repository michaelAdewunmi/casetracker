<?php
namespace CaseTrackerTest\Tests\Unit;

class TestSample extends \WP_UnitTestCase
{
    public function testSampleString()
    {
        $string = 'Unit tests are sweet';
        $this->assertEquals('Unit tests are sweet', $string);
    }

    public function testAnotherSampleString()
    {
        $string = 'Failing Unit tests are sad';
        $this->assertEquals('Failing Unit tests are dsad', $string);
    }
}
