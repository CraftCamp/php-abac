<?php

namespace PhpAbac\Test\Model;

use PhpAbac\Model\Attribute;
use PhpAbac\Model\PolicyRuleAttribute;

class PolicyRuleAttributeTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $policyRuleAttribute =
            (new PolicyRuleAttribute())
            ->setAttribute(new Attribute())
            ->setType('object')
            ->setComparisonType('String')
            ->setComparison('isEqual')
            ->setValue(true)
        ;
        $this->assertTrue($policyRuleAttribute->getValue());
        $this->assertInstanceof('PhpAbac\Model\Attribute', $policyRuleAttribute->getAttribute());
        $this->assertEquals('object', $policyRuleAttribute->getType());
        $this->assertEquals('String', $policyRuleAttribute->getComparisonType());
        $this->assertEquals('isEqual', $policyRuleAttribute->getComparison());
    }
}
