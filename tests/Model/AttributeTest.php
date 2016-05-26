<?php

namespace PhpAbac\Test\Model;

use PhpAbac\Model\Attribute;

class AttributeTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $attribute =
            (new Attribute())
            ->setName('test-attribute')
            ->setProperty('userAttributes')
            ->setType('resource')
            ->setValue([])
        ;
        $this->assertEquals('test-attribute', $attribute->getName());
        $this->assertEquals('userAttributes', $attribute->getProperty());
        $this->assertEquals('resource', $attribute->getType());
        $this->assertEquals([], $attribute->getValue());
    }
}
