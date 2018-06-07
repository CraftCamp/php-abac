<?php

namespace PhpAbac\Test\Comparison;

use PhpAbac\Comparison\NumericComparison;

use PhpAbac\Manager\{
    AttributeManager,
    ComparisonManager
};

class NumericComparisonTest extends \PHPUnit\Framework\TestCase
{
    /** @var NumericComparison **/
    protected $comparison;

    public function setUp()
    {
        $this->comparison = new NumericComparison(new ComparisonManager(new AttributeManager([])));
    }

    public function testIsEqual()
    {
        $this->assertTrue($this->comparison->isEqual(4, 4));
        $this->assertFalse($this->comparison->isEqual(4, 5));
    }

    public function testIsLesserThan()
    {
        $this->assertTrue($this->comparison->isLesserThan(21, 18));
        $this->assertFalse($this->comparison->isLesserThan(21, 22));
        $this->assertFalse($this->comparison->isLesserThan(21, 21));
    }
    
    public function testIsLesserThanOrEqual()
    {
        $this->assertTrue($this->comparison->isLesserThanOrEqual(18, 18));
        $this->assertFalse($this->comparison->isLesserThanOrEqual(21, 22));
    }

    public function testIsGreaterThan()
    {
        $this->assertTrue($this->comparison->isGreaterThan(18, 21));
        $this->assertFalse($this->comparison->isGreaterThan(18, 18));
        $this->assertFalse($this->comparison->isGreaterThan(18, 14));
    }
    
    public function testIsGreaterThanOrEqual()
    {
        $this->assertTrue($this->comparison->isGreaterThanOrEqual(18, 18));
        $this->assertFalse($this->comparison->isGreaterThanOrEqual(21, 18));
    }
}
