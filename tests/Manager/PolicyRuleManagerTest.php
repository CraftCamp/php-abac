<?php

namespace PhpAbac\Test\Manager;

use PhpAbac\Manager\PolicyRuleManager;
use PhpAbac\Manager\AttributeManager;
use PhpAbac\Manager\ConfigurationManager;

use Symfony\Component\Config\FileLocator;

use PhpAbac\Model\PolicyRule;
use PhpAbac\Model\PolicyRuleAttribute;
use PhpAbac\Model\Attribute;
use PhpAbac\Model\EnvironmentAttribute;

class PolicyRuleManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PhpAbac\Manager\PolicyRuleManager **/
    private $manager;

    public function setUp()
    {
        $configuration = new ConfigurationManager(new FileLocator());
        $configuration->parseConfigurationFile([__DIR__.'/../fixtures/policy_rules.yml']);
        
        $this->manager = new PolicyRuleManager(
            new AttributeManager($configuration->getAttributes()),
            $configuration->getRules()
        );
    }

    public function testGetRule()
    {
        $policyRule = $this->manager->getRule('vehicle-homologation');

        $this->assertInstanceof('PhpAbac\Model\PolicyRule', $policyRule);
        $this->assertEquals('vehicle-homologation', $policyRule->getName());
        $this->assertCount(5, $policyRule->getPolicyRuleAttributes());
        
        $policyRuleAttribute = $policyRule->getPolicyRuleAttributes()[0];
        
        $this->assertInstanceOf('PhpAbac\Model\PolicyRuleAttribute', $policyRuleAttribute);
        $this->assertInstanceOf('PhpAbac\Model\Attribute', $policyRuleAttribute->getAttribute());
        $this->assertEquals('boolean', $policyRuleAttribute->getComparisonType());
        $this->assertEquals('boolAnd', $policyRuleAttribute->getComparison());
        $this->assertTrue($policyRuleAttribute->getValue());
    }
}
