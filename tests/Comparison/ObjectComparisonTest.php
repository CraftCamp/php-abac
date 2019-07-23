<?php

namespace PhpAbac\Test\Comparison;

use PhpAbac\Comparison\ObjectComparison;

use PhpAbac\Manager\ComparisonManager;
use PhpAbac\Manager\AttributeManager;

use PhpAbac\Model\Attribute;

use PhpAbac\Example\{
    User,
    Vehicle
};

class ObjectComparisonTest extends \PHPUnit\Framework\TestCase
{
    /** @var ArrayComparison **/
    protected $comparison;

    public function setUp(): void
    {
        $this->comparison = new ObjectComparison($this->getComparisonManagerMock());
    }

    public function testIsFieldEqual()
    {
        $extraData = [
            'resource' =>
                (new Vehicle())
                ->setId(1)
                ->setOwner((new User())->setId(1))
        ];
        $this->assertFalse($this->comparison->isFieldEqual('vehicle.owner.id', 2, $extraData));
        $this->assertTrue($this->comparison->isFieldEqual('vehicle.owner.id', 1, $extraData));
    }
    
    public function getComparisonManagerMock()
    {
        $comparisonManagerMock = $this
            ->getMockBuilder(ComparisonManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $comparisonManagerMock
            ->expects($this->any())
            ->method('getAttributeManager')
            ->willReturnCallback([$this, 'getAttributeManagerMock'])
        ;
        return $comparisonManagerMock;
    }
    
    public function getAttributeManagerMock()
    {
        $attributeManagerMock = $this
            ->getMockBuilder(AttributeManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $attributeManagerMock
            ->expects($this->any())
            ->method('getAttribute')
            ->willReturn(new Attribute())
        ;
        $attributeManagerMock
            ->expects($this->any())
            ->method('retrieveAttribute')
            ->willReturn(1)
        ;
        return $attributeManagerMock;
    }
}
