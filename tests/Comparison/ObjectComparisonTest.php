<?php

namespace PhpAbac\Test\Comparison;

use PhpAbac\Comparison\ObjectComparison;

use Symfony\Component\Config\FileLocator;

use PhpAbac\Manager\ConfigurationManager;
use PhpAbac\Manager\ComparisonManager;
use PhpAbac\Manager\AttributeManager;

use PhpAbac\Model\PolicyRuleAttribute;
use PhpAbac\Model\Attribute;

use PhpAbac\Example\User;
use PhpAbac\Example\Vehicle;

class ObjectComparisonTest extends \PHPUnit\Framework\TestCase
{
    /** @var ArrayComparison **/
    protected $comparison;

    public function setUp()
    {
        $configuration = new ConfigurationManager(new FileLocator());
        $configuration->parseConfigurationFile([__DIR__.'/../fixtures/policy_rules.yml']);

        $this->comparison = new ObjectComparison(new ComparisonManager(new AttributeManager($configuration->getAttributes())));
    }

    public function testIsFieldEqual()
    {
        $countries = include(__DIR__ . '/../fixtures/countries.php');
        $visas = include(__DIR__ . '/../fixtures/visas.php');
        $extraData = [
            'resource' =>
                (new Vehicle())
                ->setId(1)
                ->setOwner((new User())->setId(1))
        ];
        $this->assertFalse($this->comparison->isFieldEqual('vehicle.owner.id', 2, $extraData));
        $this->assertTrue($this->comparison->isFieldEqual('vehicle.owner.id', 1, $extraData));
    }
}
