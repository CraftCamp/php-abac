<?php

namespace PhpAbac\Test\Comparison;

use PhpAbac\Comparison\BooleanComparison;

use PhpAbac\Manager\ComparisonManager;
use PhpAbac\Manager\AttributeManager;

class BooleanComparisonTest extends \PHPUnit_Framework_TestCase
{
    /** @var BooleanComparison **/
    protected $comparison;

    public function setUp()
    {
        $this->comparison = new BooleanComparison(new ComparisonManager(new AttributeManager([])));
    }

    public function testBoolAnd()
    {
        $this->assertTrue($this->comparison->boolAnd(true, true));
        $this->assertFalse($this->comparison->boolAnd(true, false));
        $this->assertFalse($this->comparison->boolAnd(false, false));
    }

    public function testBoolOr()
    {
        $this->assertTrue($this->comparison->boolOr(true, true));
        $this->assertTrue($this->comparison->boolOr(true, false));
        $this->assertFalse($this->comparison->boolOr(false, false));
    }
    
    public function testIsNull()
    {
        $this->assertTrue($this->comparison->isNull(true, null));
        $this->assertFalse($this->comparison->isNull(true, true));
    }
    
    public function testIsNotNull()
    {
        $this->assertTrue($this->comparison->isNotNull(true, true));
        $this->assertFalse($this->comparison->isNotNull(true, null));
    }
}
