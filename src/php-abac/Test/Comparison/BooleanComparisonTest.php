<?php

namespace PhpAbac\Test\Comparison;

use PhpAbac\Comparison\BooleanComparison;

class BooleanComparisonTest extends \PHPUnit_Framework_TestCase {
    /** @var BooleanComparison **/
    protected $comparison;
    
    public function setUp() {
        $this->comparison = new BooleanComparison();
    }
    
    public function testBoolAnd() {
        $this->assertTrue($this->comparison->boolAnd(true, true));
        $this->assertFalse($this->comparison->boolAnd(true, false));
        $this->assertFalse($this->comparison->boolAnd(false, false));
    }
    
    public function testBoolOr() {
        $this->assertTrue($this->comparison->boolOr(true, true));
        $this->assertTrue($this->comparison->boolOr(true, false));
        $this->assertFalse($this->comparison->boolOr(false, false));
    }
}