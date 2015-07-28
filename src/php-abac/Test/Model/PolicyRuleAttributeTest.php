<?php

namespace PhpAbac\Test\Model;

use PhpAbac\Model\Attribute;
use PhpAbac\Model\PolicyRuleAttribute;

class PolicyRuleAttributeTest extends \PHPUnit_Framework_TestCase {
    public function testEntity() {
        $policyRuleAttribute =
            (new PolicyRuleAttribute())
            ->setAttribute(new Attribute())
            ->setComparison('string:length')
            ->setValue(true)
        ;
        $this->assertTrue($policyRuleAttribute->getValue());
        $this->assertInstanceof('PhpAbac\Model\Attribute', $policyRuleAttribute->getAttribute());
        $this->assertEquals('string:length', $policyRuleAttribute->getComparison());
    }
}