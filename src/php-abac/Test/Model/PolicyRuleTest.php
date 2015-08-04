<?php

namespace PhpAbac\Test\Model;

use PhpAbac\Model\PolicyRule;
use PhpAbac\Model\PolicyRuleAttribute;

class PolicyRuleTest extends \PHPUnit_Framework_TestCase {
    public function testEntity() {
        $pra1 = new PolicyRuleAttribute();
        $pra2 = new PolicyRuleAttribute();
        $pra3 = new PolicyRuleAttribute();
        $pra4 = new PolicyRuleAttribute();
        
        $policyRule =
            (new PolicyRule())
            ->setId(1)
            ->setName('citizenship')
            ->addPolicyRuleAttribute($pra1)
            ->addPolicyRuleAttribute($pra2)
            ->addPolicyRuleAttribute($pra3)
            ->addPolicyRuleAttribute($pra4)
            ->removePolicyRuleAttribute($pra4)
        ;
        $this->assertEquals(1, $policyRule->getId());
        $this->assertEquals('citizenship', $policyRule->getName());
        $this->assertCount(3, $policyRule->getPolicyRuleAttributes());
    }
}