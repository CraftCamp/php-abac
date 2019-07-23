<?php

namespace PhpAbac\Test\Manager;

use PhpAbac\Manager\{
    AttributeManager,
    ComparisonManager
};
use PhpAbac\Model\{
    Attribute,
    PolicyRuleAttribute
};

class ComparisonManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @var ComparisonManager **/
    protected $manager;
    
    public function setUp(): void
    {
        $this->manager = new ComparisonManager($this->getAttributeManagerMock());
    }
    
    public function testCompare()
    {
        $this->assertTrue($this->manager->compare(
            (new PolicyRuleAttribute())
            ->setAttribute(
                (new Attribute())
                ->setName('Test')
                ->setSlug('test')
                ->setValue('Value')
            )
            ->setComparisonType('string')
            ->setComparison('isEqual')
            ->setValue('Value')
        ));
        $this->assertEquals([], $this->manager->getResult());
    }
    
    public function testCompareWithInvalidAttribute()
    {
        $this->assertFalse($this->manager->compare(
            (new PolicyRuleAttribute())
            ->setAttribute(
                (new Attribute())
                ->setName('Test')
                ->setSlug('test')
                ->setValue('Wrong value')
            )
            ->setComparisonType('string')
            ->setComparison('isEqual')
            ->setValue('Value')
        ));
        $this->assertEquals(['test'], $this->manager->getResult());
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The requested comparison class does not exist
     */
    public function testCompareWithInvalidType()
    {
        $this->manager->compare(
            (new PolicyRuleAttribute())
            ->setAttribute(
                (new Attribute())
                ->setName('Test')
                ->setSlug('test')
                ->setValue('Value')
            )
            ->setComparisonType('unknownType')
            ->setComparison('isEqual')
            ->setValue('Value')
        );
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The requested comparison method does not exist
     */
    public function testCompareWithInvalidMethod()
    {
        $this->manager->compare(
            (new PolicyRuleAttribute())
            ->setAttribute(
                (new Attribute())
                ->setName('Test')
                ->setSlug('test')
                ->setValue('Value')
            )
            ->setComparisonType('string')
            ->setComparison('equal')
            ->setValue('Value')
        );
    }
    
    public function testDynamicAttributes()
    {
        $this->manager->setDynamicAttributes(['owner-id' => 13]);
        $this->assertEquals(13, $this->manager->getDynamicAttribute('owner-id'));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The dynamic value for attribute owner-id was not given
     */
    public function testGetMissingDynamicAttribute()
    {
        $this->manager->getDynamicAttribute('owner-id');
    }
    
    public function getAttributeManagerMock()
    {
        $managerMock = $this
            ->getMockBuilder(AttributeManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        return $managerMock;
    }
}
