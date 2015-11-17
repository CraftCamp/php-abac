<?php

namespace PhpAbac\Test\Manager;

use PhpAbac\Abac;
use PhpAbac\Test\AbacTestCase;
use PhpAbac\Model\PolicyRule;
use PhpAbac\Model\PolicyRuleAttribute;
use PhpAbac\Model\Attribute;
use PhpAbac\Model\EnvironmentAttribute;

class PolicyRuleManagerTest extends AbacTestCase
{
    /** @var \PhpAbac\Manager\PolicyRuleManager **/
    private $manager;

    /**
     * @var Abac
     */
    private $abac;

    public function setUp()
    {
        $this->abac = new Abac($this->getConnection());

        $this->loadFixture('policy_rules');

        $this->manager = Abac::get('policy-rule-manager');
    }

    public function testCreate()
    {
        $policyRule =
            (new PolicyRule())
            ->setName('citizenship')
            ->addPolicyRuleAttribute(
                (new PolicyRuleAttribute())
                ->setAttribute(
                    (new Attribute())
                    ->setName('Age')
                    ->setTable('user')
                    ->setColumn('age')
                    ->setCriteriaColumn('id')
                )
                ->setType('user')
                ->setComparisonType('Numeric')
                ->setComparison('greaterThan')
                ->setValue(18)
            )
            ->addPolicyRuleAttribute(
                (new PolicyRuleAttribute())
                ->setAttribute(
                    (new EnvironmentAttribute())
                    ->setName('Service State')
                    ->setVariableName('SERVICE_STATE')
                )
                ->setType('environment')
                ->setComparisonType('String')
                ->setComparison('isEqual')
                ->setValue('OPEN')
            )
        ;
        $this->manager->create($policyRule);
        // Test the policy rule
        $this->assertEquals('citizenship', $policyRule->getName());
        $this->assertInstanceof('DateTime', $policyRule->getCreatedAt());
        $this->assertInstanceof('DateTime', $policyRule->getUpdatedAt());

        // Test the cascade created PolicyRuleAttribute entities
        $policyRuleAttributes = $policyRule->getPolicyRuleAttributes();

        $this->assertCount(2, $policyRuleAttributes);
        $this->assertInstanceof('PhpAbac\Model\PolicyRuleAttribute', $policyRuleAttributes[0]);
        $this->assertEquals('greaterThan', $policyRuleAttributes[0]->getComparison());
        $this->assertEquals(18, $policyRuleAttributes[0]->getValue());

        // Test the created Attribute entity
        $attribute = $policyRuleAttributes[0]->getAttribute();

        $this->assertInstanceof('PhpAbac\Model\Attribute', $attribute);
        $this->assertEquals('Age', $attribute->getName());
        $this->assertEquals('user', $attribute->getTable());
        $this->assertEquals('age', $attribute->getColumn());
        $this->assertEquals('id', $attribute->getCriteriaColumn());
    }

    public function testGetRuleByName()
    {
        $policyRule = $this->manager->getRuleByName('vehicle-homologation');

        $this->assertInstanceof('PhpAbac\Model\PolicyRule', $policyRule);
        $this->assertEquals(2, $policyRule->getId(2));
        $this->assertEquals('vehicle-homologation', $policyRule->getName());
        $this->assertInstanceOf('DateTime', $policyRule->getCreatedAt());
        $this->assertInstanceOf('DateTime', $policyRule->getUpdatedAt());
    }
}
