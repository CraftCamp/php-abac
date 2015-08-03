<?php

namespace PhpAbac\Test\Model;

use PhpAbac\Model\Attribute;

class AttributeTest extends \PHPUnit_Framework_TestCase {
    public function testEntity() {
        $attribute =
            (new Attribute())
            ->setName('test-attribute')
            ->setColumn('attribute_column')
            ->setTable('user_attributes')
            ->setCriteriaColumn('user_id')
            ->setValue(3)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
        ;
        $this->assertNull($attribute->getId());
        $this->assertEquals('test-attribute', $attribute->getName());
        $this->assertEquals('attribute_column', $attribute->getColumn());
        $this->assertEquals('user_attributes', $attribute->getTable());
        $this->assertEquals('user_id', $attribute->getCriteriaColumn());
        $this->assertInstanceOf('DateTime', $attribute->getCreatedAt());
        $this->assertInstanceOf('DateTime', $attribute->getUpdatedAt());
        $this->assertEquals(3, $attribute->getValue());
    }
}