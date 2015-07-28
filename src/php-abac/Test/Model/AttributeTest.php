<?php

namespace PhpAbac\Test\Model;

use PhpAbac\Model\Attribute;

class AttributeTest extends \PHPUnit_Framework_TestCase {
    public function testEntity() {
        $attribute =
            (new Attribute())
            ->setColumn('attribute_column')
            ->setTable('user_attributes')
            ->setIdColumn('user_id')
            ->setValue(3)
        ;
        $this->assertNull($attribute->getId());
        $this->assertEquals('attribute_column', $attribute->getColumn());
        $this->assertEquals('user_attributes', $attribute->getTable());
        $this->assertEquals('user_id', $attribute->getIdColumn());
        $this->assertEquals(3, $attribute->getValue());
    }
}