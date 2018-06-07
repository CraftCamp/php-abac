<?php

namespace PhpAbac\Test\Model;

use PhpAbac\Model\{
    PolicyRule,
    PolicyRuleAttribute
};

class PolicyRuleTest extends \PHPUnit\Framework\TestCase
{
    public function testEntity()
    {
        $pra1 = new PolicyRuleAttribute();
        $pra2 = new PolicyRuleAttribute();
        $pra3 = new PolicyRuleAttribute();
        $pra4 = new PolicyRuleAttribute();

        $policyRule =
            (new PolicyRule())
            ->setName('citizenship')
            ->addPolicyRuleAttribute($pra1)
            ->addPolicyRuleAttribute($pra2)
            ->addPolicyRuleAttribute($pra3)
            ->addPolicyRuleAttribute($pra4)
            ->removePolicyRuleAttribute($pra4)
        ;
        $this->assertEquals('citizenship', $policyRule->getName());
        $this->assertCount(3, $policyRule->getPolicyRuleAttributes());
    }
}
