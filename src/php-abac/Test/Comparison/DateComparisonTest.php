<?php

namespace PhpAbac\Test\Comparison;

use PhpAbac\Comparison\DateComparison;

class DateComparisonTest extends \PHPUnit_Framework_TestCase {
    /** @var DateComparison **/
    protected $comparison;
    
    public function setUp() {
        $this->comparison = new DateComparison();
    }
    
    public function testIsBetween() {
        $start = new \DateTime(time() - 500);
        $end = new \DateTime(time());
        $rightValue = new \DateTime(time() - 200);
        $wrongValue = new \DateTime(time() + 150);
        
        $this->assertTrue($this->comparison->isBetween($start, $end, $rightValue));
        $this->assertFalse($this->comparison->isBetween($start, $end, $wrongValue));
    }
}