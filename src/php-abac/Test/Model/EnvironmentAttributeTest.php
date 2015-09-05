<?php

namespace PhpAbac\Test\Model;

use PhpAbac\Model\EnvironmentAttribute;

class EnvironmentAttributeTest extends \PHPUnit_Framework_TestCase {
    public function testEntity() {
        $attribute =
            (new EnvironmentAttribute())
            ->setId(1)
            ->setName('test-attribute')
            ->setVariableName('service-state')
            ->setValue(3)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
        ;
        $this->assertEquals(1, $attribute->getId());
        $this->assertEquals('test-attribute', $attribute->getName());
        $this->assertEquals('service-state', $attribute->getVariableName());
        $this->assertInstanceOf('DateTime', $attribute->getCreatedAt());
        $this->assertInstanceOf('DateTime', $attribute->getUpdatedAt());
        $this->assertEquals(3, $attribute->getValue());
    }
}