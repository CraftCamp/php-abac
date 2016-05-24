<?php

namespace PhpAbac\Test\Model;

use PhpAbac\Model\Attribute;

class AttributeTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $attribute =
            (new Attribute())
            ->setId(1)
            ->setName('test-attribute')
            ->setProperty('userAttributes')
            ->setValue([])
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
        ;
        $this->assertEquals(1, $attribute->getId());
        $this->assertEquals('test-attribute', $attribute->getName());
        $this->assertEquals('userAttributes', $attribute->getProperty());
        $this->assertInstanceOf('DateTime', $attribute->getCreatedAt());
        $this->assertInstanceOf('DateTime', $attribute->getUpdatedAt());
        $this->assertEquals([], $attribute->getValue());
    }
}
