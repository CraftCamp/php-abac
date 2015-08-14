<?php

namespace PhpAbac\Test\Comparison;

use PhpAbac\Comparison\NumericComparison;

class NumericComparisonTest extends \PHPUnit_Framework_TestCase {
    /** @var NumericComparison **/
    protected $comparison;
    
    public function setUp() {
        $this->comparison = new NumericComparison();
    }
    
    public function testIsEqual() {
        $this->assertTrue($this->comparison->isEqual(4, 4));
        $this->assertFalse($this->comparison->isEqual(4, 5));
    }
    
    public function testIsLesserThan() {
        $this->assertTrue($this->comparison->isLesserThan(21, 18));
        $this->assertTrue($this->comparison->isLesserThan(18, 18, false));
        $this->assertFalse($this->comparison->isLesserThan(21, 22));
        $this->assertFalse($this->comparison->isLesserThan(21, 21));
    }
    
    public function testIsGreaterThan() {
        $this->assertTrue($this->comparison->isGreaterThan(18, 21));
        $this->assertTrue($this->comparison->isGreaterThan(18, 18, false));
        $this->assertFalse($this->comparison->isGreaterThan(18, 18));
        $this->assertFalse($this->comparison->isGreaterThan(18, 14));
    }
}