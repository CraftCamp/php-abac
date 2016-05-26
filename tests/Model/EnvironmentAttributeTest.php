<?php

namespace PhpAbac\Test\Model;

use PhpAbac\Model\EnvironmentAttribute;

class EnvironmentAttributeTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $attribute =
            (new EnvironmentAttribute())
            ->setType('environment')
            ->setName('test-attribute')
            ->setVariableName('service-state')
            ->setValue(3)
        ;
        $this->assertEquals('environment', $attribute->getType());
        $this->assertEquals('test-attribute', $attribute->getName());
        $this->assertEquals('service-state', $attribute->getVariableName());
        $this->assertEquals(3, $attribute->getValue());
    }
}
