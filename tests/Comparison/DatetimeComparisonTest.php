<?php

namespace PhpAbac\Test\Comparison;

use PhpAbac\Comparison\DatetimeComparison;

class DattimeeComparisonTest extends \PHPUnit_Framework_TestCase
{
    /** @var DateComparison **/
    protected $comparison;

    public function setUp()
    {
        $this->comparison = new DatetimeComparison();
    }

    public function testIsBetween()
    {
        $start = new \DateTime('2015-08-01');
        $end = new \DateTime('2015-08-16');

        $this->assertTrue($this->comparison->isBetween($start, $end, new \DateTime('2015-08-05')));
        $this->assertFalse($this->comparison->isBetween($start, $end, new \DateTime('2015-07-18')));
    }
    
    public function testIsMoreRecentThan() {
        $this->assertTrue($this->comparison->isMoreRecentThan('-2Y', new \DateTime()));
        $this->assertFalse($this->comparison->isMoreRecentThan('-2Y', new \DateTime('2010-01-02')));
    }
    
    public function testIsLessRecentThan() {
        $this->assertTrue($this->comparison->isLessRecentThan('-2Y', new \DateTime('2010-01-02')));
        $this->assertFalse($this->comparison->isLessRecentThan('-2Y', new \DateTime()));
    }
}
