<?php

namespace PhpAbac\Test\Comparison;

use PhpAbac\Comparison\UserComparison;

use PhpAbac\Manager\{
    AttributeManager,
    ComparisonManager
};
use PhpAbac\Model\Attribute;

use PhpAbac\Example\User;

class UserComparisonTest extends \PHPUnit\Framework\TestCase
{
    /** @var ArrayComparison **/
    protected $comparison;

    public function setUp(): void
    {
        $this->comparison = new UserComparison($this->getComparisonManagerMock());
    }

    public function testIsFieldEqual()
    {
        $extraData = [
            'user' =>
                (new User())
                ->setId(1)
                ->setParentNationality('UK')
        ];
        $this->assertFalse($this->comparison->isFieldEqual('main_user.parentNationality', 'FR', $extraData));
        $this->assertTrue($this->comparison->isFieldEqual('main_user.parentNationality', 'UK', $extraData));
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
            ->willReturn((new Attribute()))
        ;
        $attributeManagerMock
            ->expects($this->any())
            ->method('retrieveAttribute')
            ->willReturn('UK')
        ;
        return $attributeManagerMock;
    }
}
