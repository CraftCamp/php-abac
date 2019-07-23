<?php

namespace PhpAbac\Test\Comparison;

use PhpAbac\Comparison\BooleanComparison;

use PhpAbac\Manager\ComparisonManager;

class BooleanComparisonTest extends \PHPUnit\Framework\TestCase
{
    /** @var BooleanComparison **/
    protected $comparison;

    public function setUp(): void
    {
        $this->comparison = new BooleanComparison($this->getComparisonManagerMock());
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
    
    public function getComparisonManagerMock()
    {
        $comparisonManagerMock = $this
            ->getMockBuilder(ComparisonManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        return $comparisonManagerMock;
    }
}
