<?php

namespace PhpAbac\Test\Manager;

use PhpAbac\Manager\{
    PolicyRuleManager,
    ConfigurationManager,
    AttributeManager
};
use PhpAbac\Model\{
    Attribute,
    PolicyRule,
    PolicyRuleAttribute
};
use Symfony\Component\Config\FileLocator;

class PolicyRuleManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @var PolicyRuleManager **/
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
        $countries = include('tests/fixtures/countries.php');
        $visas = include('tests/fixtures/visas.php');
        $users = include('tests/fixtures/users.php');
        $vehicles = include('tests/fixtures/vehicles.php');

        $policyRule_a = $this->manager->getRule('vehicle-homologation', $users[0], $vehicles[0]);

        $policyRule = $policyRule_a[0];

        $this->assertInstanceof(PolicyRule::class, $policyRule);
        $this->assertEquals('vehicle-homologation', $policyRule->getName());
        $this->assertCount(6, $policyRule->getPolicyRuleAttributes());

        $policyRuleAttribute = $policyRule->getPolicyRuleAttributes()[0];

        $this->assertInstanceOf(PolicyRuleAttribute::class, $policyRuleAttribute);
        $this->assertInstanceOf(Attribute::class, $policyRuleAttribute->getAttribute());
        $this->assertEquals('boolean', $policyRuleAttribute->getComparisonType());
        $this->assertEquals('boolAnd', $policyRuleAttribute->getComparison());
        $this->assertTrue($policyRuleAttribute->getValue());
    }
}
